@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <!-- Foto Profil -->
                <div class="mb-3">
                    @php
                        $fotoPath = null;
                        if($user->role == 'siswa' && $user->siswa && $user->siswa->foto) {
                            $fotoPath = asset('storage/' . $user->siswa->foto);
                        } elseif($user->role == 'guru' && $user->guru && $user->guru->foto) {
                            $fotoPath = asset('storage/' . $user->guru->foto);
                        }
                    @endphp
                    
                    @if($fotoPath)
                        <img src="{{ $fotoPath }}" alt="Profile" class="rounded-circle" 
                             style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #4680ff;">
                    @else
                        <div class="mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 150px; height: 150px; font-size: 64px;">
                            {{ strtoupper(substr($user->email, 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <!-- Form Upload Foto -->
                <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2">
                        <input type="file" name="foto" class="form-control form-control-sm" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="ph ph-camera"></i> Ganti Foto
                    </button>
                </form>
                
                <hr>
                <h5>{{ $user->email }}</h5>
                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="ph ph-gear"></i> Pengaturan Profil</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ph ph-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Data Diri (berdasarkan role) -->
                        @if($user->role == 'siswa')
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" class="form-control" value="{{ $profile->nis ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $profile->nama ?? '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="kelas" class="form-control" value="{{ old('kelas', $profile->kelas ?? '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $profile->jurusan ?? '') }}" required>
                        </div>
                        @elseif($user->role == 'guru')
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" class="form-control" value="{{ $profile->nip ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $profile->nama ?? '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" name="mata_pelajaran" class="form-control" value="{{ old('mata_pelajaran', $profile->mata_pelajaran ?? '') }}" required>
                        </div>
                        @endif
                        
                        <!-- Field yang sama untuk semua role -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="L" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        
                        <!-- PERBAIKAN: Tanggal Lahir -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            @php
                                $tanggalLahir = '';
                                if(isset($profile->tanggal_lahir) && $profile->tanggal_lahir) {
                                    // Cek apakah sudah dalam format Y-m-d
                                    if(strpos($profile->tanggal_lahir, '-') !== false) {
                                        $tanggalLahir = $profile->tanggal_lahir;
                                    } else {
                                        // Konversi dari format lain ke Y-m-d
                                        $tanggalLahir = date('Y-m-d', strtotime($profile->tanggal_lahir));
                                    }
                                }
                            @endphp
                           <input type="date" name="tanggal_lahir" class="form-control" 
       value="{{ old('tanggal_lahir', $profile->tanggal_lahir_formatted ?? '') }}" required>
                            <small class="text-muted">Format: YYYY-MM-DD</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $profile->no_hp ?? '') }}" required>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru">
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label">Theme</label>
                        <select id="themeSelect" class="form-select">
                            <option value="light">Light Mode</option>
                            <option value="dark">Dark Mode</option>
                            <option value="default">Default</option>
                        </select>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Theme switcher
    const themeSelect = document.getElementById('themeSelect');
    if (themeSelect) {
        themeSelect.addEventListener('change', function() {
            const theme = this.value;
            if (theme === 'dark') {
                layout_change('dark');
            } else if (theme === 'light') {
                layout_change('light');
            } else {
                layout_change_default();
            }
        });
    }
</script>
@endpush