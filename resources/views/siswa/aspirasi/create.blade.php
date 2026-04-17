@extends('layouts.admin')

@section('title', 'Buat Aspirasi')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ph ph-pencil-line"></i> Form Pengajuan Aspirasi</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ph ph-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ph ph-warning-circle me-2"></i>
                    {{ session('error') }}
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

                <!-- Informasi Kuota -->
                @php
                $today = \Carbon\Carbon::now()->startOfDay();
                $countToday = \App\Models\Aspirasi::where('user_id', Auth::id())
                ->whereDate('created_at', '>=', $today)
                ->count();
                $sisaKuota = 3 - $countToday;
                @endphp

                <div class="alert alert-info mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="ph ph-info"></i>
                            <strong>Kuota Aspirasi Hari Ini</strong>
                        </div>
                        <div>
                            <span class="badge bg-{{ $sisaKuota > 0 ? 'success' : 'danger' }} fs-6 p-2">
                                {{ $sisaKuota }} / 3
                            </span>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-{{ $sisaKuota > 0 ? 'success' : 'danger' }}"
                            style="width: {{ ($countToday / 3) * 100 }}%"></div>
                    </div>
                    <small class="text-muted mt-1 d-block">
                        @if($sisaKuota > 0)
                        Anda masih dapat mengirim {{ $sisaKuota }} aspirasi lagi hari ini.
                        @else
                        Anda telah mencapai batas maksimal 3 aspirasi hari ini. Silakan coba lagi besok.
                        @endif
                    </small>
                </div>

                <form method="POST" action="{{ route('siswa.aspirasi.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label">Kategori Aspirasi <span class="text-danger">*</span></label>
                        <select name="id_kategori" class="form-select" id="kategoriSelect" required {{ $sisaKuota <= 0 ? 'disabled' : '' }}>
                            <option value="">-- Pilih Kategori --</option>
                            @if(isset($kategoris) && $kategoris->count() > 0)
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}"
                                data-deskripsi="{{ $kategori->deskripsi }}"
                                {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                            @endforeach
                            @else
                            <option value="" disabled>Belum ada kategori, hubungi admin</option>
                            @endif
                        </select>
                        <div id="kategoriDeskripsi" class="small text-muted mt-1"></div>
                    </div>

                    <!-- Ruangan -->
                    <div class="mb-3">
                        <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph ph-building"></i></span>
                            <select name="id_ruangan" class="form-select" required {{ $sisaKuota <= 0 ? 'disabled' : '' }}>
                                <option value="">-- Pilih Ruangan --</option>
                                @if(isset($ruangans) && $ruangans->count() > 0)
                                @foreach($ruangans as $ruangan)
                                <option value="{{ $ruangan->id_ruangan }}"
                                    data-jenis="{{ $ruangan->jenis_ruangan }}"
                                    data-lokasi="{{ $ruangan->lokasi }}"
                                    {{ old('id_ruangan') == $ruangan->id_ruangan ? 'selected' : '' }}>
                                    {{ $ruangan->kode_ruangan }} - {{ $ruangan->nama_ruangan }}
                                    ({{ $ruangan->jenis_ruangan }})
                                </option>
                                @endforeach
                                @else
                                <option value="" disabled>Belum ada ruangan, hubungi admin</option>
                                @endif
                            </select>
                        </div>
                        <div id="ruanganDetail" class="small text-muted mt-1"></div>
                    </div>

                    <!-- SAKSI - Pilih teman sekelas -->
                    <div class="mb-3">
                        <label class="form-label">Saksi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph ph-user-check"></i></span>
                            <select name="saksi_id" class="form-select" required {{ $sisaKuota <= 0 ? 'disabled' : '' }}>
                                <option value="">-- Pilih Saksi --</option>
                                @if(isset($calonSaksi) && $calonSaksi->count() > 0)
                                @foreach($calonSaksi as $saksi)
                                <option value="{{ $saksi->id }}" {{ old('saksi_id') == $saksi->id ? 'selected' : '' }}>
                                    {{ $saksi->nama }} ({{ $saksi->nis }}) - Kelas {{ $saksi->kelasRelasi->nama_kelas ?? $saksi->kelas }}
                                </option>
                                @endforeach
                                @else
                                <option value="" disabled>Tidak ada siswa lain di kelas Anda</option>
                                @endif
                            </select>
                        </div>
                        <small class="text-muted">Pilih teman sekelas sebagai saksi untuk aspirasi ini (wajib)</small>
                    </div>

                    <!-- PIN Verifikasi -->
                    <div class="mb-3">
                        <label class="form-label">PIN Verifikasi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph ph-key"></i></span>
                            <input type="password" name="pin" class="form-control"
                                placeholder="Masukkan PIN 6 digit"
                                maxlength="6"
                                pattern="[0-9]{6}"
                                title="Harus 6 digit angka"
                                required {{ $sisaKuota <= 0 ? 'disabled' : '' }}>
                            <button type="button" class="btn btn-outline-secondary" id="togglePin">
                                <i class="ph ph-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Masukkan PIN yang sudah Anda buat</small>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control" rows="5"
                            placeholder="Jelaskan secara detail kondisi sarana yang bermasalah..."
                            required {{ $sisaKuota <= 0 ? 'disabled' : '' }}>{{ old('keterangan') }}</textarea>
                    </div>

                    <!-- Foto -->
                    <div class="mb-3">
                        <label class="form-label">Foto (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg" id="fotoInput" {{ $sisaKuota <= 0 ? 'disabled' : '' }}>
                        <small class="text-muted">Upload foto bukti (max 2MB, format: JPG, PNG)</small>
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="previewImg" src="#" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ph ph-info"></i> <strong>Informasi:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Setiap siswa maksimal <strong>3 kali</strong> mengirim aspirasi dalam 1 hari</li>
                            <li>Saksi harus berasal dari <strong>kelas yang sama</strong> dengan Anda</li>
                            <li>Setelah dikirim, aspirasi akan masuk ke admin/petugas untuk ditindaklanjuti</li>
                            <li>Status aspirasi dapat dilihat di menu "Status Aspirasi"</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between gap-2">
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">
                            <i class="ph ph-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary" {{ $sisaKuota <= 0 ? 'disabled' : '' }}>
                            <i class="ph ph-paper-plane"></i> Kirim Aspirasi
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
    // Preview foto
    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const img = document.getElementById('previewImg');

        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.style.display = 'none';
        }
    });

    // Tampilkan deskripsi kategori
    const kategoriSelect = document.getElementById('kategoriSelect');
    const kategoriDeskripsi = document.getElementById('kategoriDeskripsi');

    function showKategoriDeskripsi() {
        const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
        const deskripsi = selectedOption.getAttribute('data-deskripsi');
        if (deskripsi && kategoriSelect.value) {
            kategoriDeskripsi.innerHTML = '<i class="ph ph-info"></i> ' + deskripsi;
        } else {
            kategoriDeskripsi.innerHTML = '';
        }
    }

    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', showKategoriDeskripsi);
        showKategoriDeskripsi();
    }

    // Tampilkan detail ruangan
    const ruanganSelect = document.querySelector('select[name="id_ruangan"]');
    const ruanganDetail = document.getElementById('ruanganDetail');

    function showRuanganDetail() {
        const selectedOption = ruanganSelect.options[ruanganSelect.selectedIndex];
        if (selectedOption && ruanganSelect.value) {
            const jenis = selectedOption.getAttribute('data-jenis');
            const lokasi = selectedOption.getAttribute('data-lokasi');
            ruanganDetail.innerHTML = '<i class="ph ph-info"></i> <strong>Jenis:</strong> ' + jenis + ' | <strong>Lokasi:</strong> ' + (lokasi || '-');
        } else {
            ruanganDetail.innerHTML = '';
        }
    }

    if (ruanganSelect) {
        ruanganSelect.addEventListener('change', showRuanganDetail);
        showRuanganDetail();
    }

    // Toggle visibility PIN
    const togglePin = document.getElementById('togglePin');
    const pinInput = document.querySelector('input[name="pin"]');

    if (togglePin && pinInput) {
        togglePin.addEventListener('click', function() {
            const type = pinInput.getAttribute('type') === 'password' ? 'text' : 'password';
            pinInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('ph-eye');
            this.querySelector('i').classList.toggle('ph-eye-slash');
        });
    }
</script>
@endpush