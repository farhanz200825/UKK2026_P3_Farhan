@extends('layouts.admin')

@section('title', 'Detail Aspirasi')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">Detail Aspirasi #{{ $aspirasi->id_aspirasi }}</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="30%">Kategori</th><td>{{ $aspirasi->kategori->nama_kategori ?? '-' }}</td></tr>
                    <tr><th>Ruangan</th><td>{{ $aspirasi->ruangan->nama_ruangan ?? $aspirasi->lokasi }}</td></tr>
                    
                    <!-- TAMBAHKAN BARIS SAKSI DI SINI -->
                    <tr>
                        <th>Saksi</th>
                        <td>
                            @if($aspirasi->saksi)
                                {{ $aspirasi->saksi->nama }}
                                <br><small class="text-muted">{{ $aspirasi->saksi->nis }} - Kelas {{ $aspirasi->saksi->kelasRelasi->nama_kelas ?? $aspirasi->saksi->kelas }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th>Pengirim</th>
                        <td>
                            @php
                                $pengirim = $aspirasi->user->siswa ?? $aspirasi->user->guru;
                            @endphp
                            {{ $pengirim->nama ?? $aspirasi->user->email }}
                            <br><small class="text-muted">{{ $pengirim->kelas ?? $pengirim->jabatan ?? '-' }}</small>
                        

                    
                    <tr><th>Keterangan</th><td>{{ $aspirasi->keterangan }}</td></tr>
                    @if($aspirasi->foto)
                    <tr><th>Foto Awal</th><td><img src="{{ asset('storage/' . $aspirasi->foto) }}" width="200" class="img-thumbnail">Ru
                    @endif
                    
                    <!-- TAMPILKAN FOTO BUKTI SELESAI -->
                    @php
                        $fotoBukti = null;
                        $fotoBuktiKeterangan = null;
                        foreach($aspirasi->progres as $progres) {
                            if(str_contains($progres->keterangan_progres, '📎 Foto bukti:')) {
                                preg_match('/📎 Foto bukti: (.*)/', $progres->keterangan_progres, $matches);
                                if(isset($matches[1])) {
                                    $fotoBukti = $matches[1];
                                    $fotoBuktiKeterangan = $progres->keterangan_progres;
                                    break;
                                }
                            }
                        }
                    @endphp
                    
                    @if($fotoBukti)
                    <tr>
                        <th>Foto Bukti Selesai</th>
                        <td>
                            <img src="{{ $fotoBukti }}" alt="Foto Bukti" width="300" class="img-thumbnail">
                            <br>
                            <small class="text-muted">Foto bukti penanganan setelah selesai</small>
                            @if($fotoBuktiKeterangan)
                            <br>
                            <small class="text-muted">Keterangan: {{ Str::limit(str_replace('📎 Foto bukti: ' . $fotoBukti, '', $fotoBuktiKeterangan), 100) }}</small>
                            @endif
                        

                     
                    @endif
                    
                    <tr><th>Status</th>
                        <td>
                            <span class="badge bg-{{ $aspirasi->status == 'Selesai' ? 'success' : ($aspirasi->status == 'Proses' ? 'info' : 'warning') }}">
                                {{ $aspirasi->status }}
                            </span>
                        

                    
                    <tr><th>Dibuat Pada</th><td>{{ $aspirasi->created_at->format('d/m/Y H:i:s') }}</td></tr>
                    @if($aspirasi->status == 'Selesai')
                    <tr><th>Selesai Pada</th><td>{{ $aspirasi->updated_at->format('d/m/Y H:i:s') }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Form Feedback & Progres untuk Admin -->
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="ph ph-chat"></i> Kelola Aspirasi (Admin)</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pengaduan.feedback', $aspirasi->id_aspirasi) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Feedback untuk Pengirim</label>
                        <textarea name="feedback" class="form-control" rows="2" placeholder="Tulis feedback untuk pengirim..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="ph ph-paper-plane"></i> Kirim Feedback
                    </button>
                </form>
                
                <hr>
                
                <form action="{{ route('admin.pengaduan.progres', $aspirasi->id_aspirasi) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Update Progres Penanganan</label>
                        <textarea name="keterangan_progres" class="form-control" rows="2" placeholder="Update progres penanganan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-info btn-sm">
                        <i class="ph ph-progress"></i> Update Progres
                    </button>
                </form>
                
                <hr>
                
                <!-- FORM UPDATE STATUS DENGAN FOTO -->
                <form action="{{ route('admin.pengaduan.status', $aspirasi->id_aspirasi) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ubah Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" id="statusSelect" required>
                                <option value="Menunggu" {{ $aspirasi->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Proses" {{ $aspirasi->status == 'Proses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Selesai" {{ $aspirasi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        
                        <!-- Form Keterangan (wajib jika status Selesai) -->
                        <div class="col-md-12 mb-3" id="keteranganDiv">
                            <label class="form-label">Keterangan Penanganan <span class="text-danger" id="keteranganRequired" style="display: none;">*</span></label>
                            <textarea name="keterangan_progres" class="form-control" rows="3" id="keteranganText" placeholder="Jelaskan tindakan yang telah dilakukan..."></textarea>
                            <small class="text-muted">Isikan keterangan detail tentang penanganan aspirasi</small>
                        </div>
                        
                        <!-- Upload Foto (wajib jika status Selesai) -->
                        <div class="col-md-12 mb-3" id="fotoDiv">
                            <label class="form-label">Foto Bukti Penanganan <span class="text-danger" id="fotoRequired" style="display: none;">*</span></label>
                            <input type="file" name="foto_bukti" class="form-control" id="fotoBukti" accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Upload foto bukti setelah selesai menangani (max 2MB)</small>
                            <div id="fotoPreview" class="mt-2" style="display: none;">
                                <img id="previewImg" src="#" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning" id="warningAlert" style="display: none;">
                        <i class="ph ph-warning"></i> 
                        <strong>Perhatian!</strong> Mengubah status menjadi <strong>Selesai</strong> akan memindahkan aspirasi ini ke History.
                        Pastikan Anda mengisi keterangan dan upload foto bukti penanganan.
                    </div>
                    
                    <button type="submit" class="btn btn-warning w-100" id="submitBtn">
                        <i class="ph ph-arrow-circle-right"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Riwayat Progres -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ph ph-list"></i> Riwayat Progres</h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($aspirasi->progres as $progres)
                <div class="border-start border-primary ps-3 mb-3">
                    <small class="text-muted">{{ $progres->created_at->format('d/m/Y H:i') }}</small>
                    <p class="mb-0 small">{{ $progres->keterangan_progres }}</p>
                    <small class="text-muted">- {{ $progres->user->petugas->nama ?? $progres->user->guru->nama ?? $progres->user->email }}</small>
                </div>
                @empty
                <p class="text-muted text-center">Belum ada progres</p>
                @endforelse
            </div>
        </div>
        
        <!-- Riwayat Status -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ph ph-clock-counter-clockwise"></i> Riwayat Status</h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($aspirasi->historyStatus as $history)
                <div class="border-start border-info ps-3 mb-3">
                    <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                    <p class="mb-0 small">{{ $history->status_lama }} → {{ $history->status_baru }}</p>
                    <small class="text-muted">- {{ $history->pengubah->petugas->nama ?? $history->pengubah->guru->nama ?? $history->pengubah->email }}</small>
                </div>
                @empty
                <p class="text-muted text-center">Belum ada riwayat</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview foto
    document.getElementById('fotoBukti').addEventListener('change', function(e) {
        const preview = document.getElementById('fotoPreview');
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
    
    // Status change handler
    const statusSelect = document.getElementById('statusSelect');
    const warningAlert = document.getElementById('warningAlert');
    const keteranganRequired = document.getElementById('keteranganRequired');
    const fotoRequired = document.getElementById('fotoRequired');
    const keteranganText = document.getElementById('keteranganText');
    const fotoBukti = document.getElementById('fotoBukti');
    const submitBtn = document.getElementById('submitBtn');
    
    function checkStatus() {
        const isSelesai = statusSelect.value === 'Selesai';
        
        if (isSelesai) {
            warningAlert.style.display = 'block';
            keteranganText.required = true;
            fotoBukti.required = true;
            keteranganRequired.style.display = 'inline';
            fotoRequired.style.display = 'inline';
            validateForm();
        } else {
            warningAlert.style.display = 'none';
            keteranganText.required = false;
            fotoBukti.required = false;
            keteranganRequired.style.display = 'none';
            fotoRequired.style.display = 'none';
            keteranganText.classList.remove('is-invalid');
            fotoBukti.classList.remove('is-invalid');
        }
    }
    
    function validateForm() {
        if (statusSelect.value === 'Selesai') {
            if (!keteranganText.value.trim()) {
                keteranganText.classList.add('is-invalid');
            } else {
                keteranganText.classList.remove('is-invalid');
            }
            
            if (!fotoBukti.files.length) {
                fotoBukti.classList.add('is-invalid');
            } else {
                fotoBukti.classList.remove('is-invalid');
            }
        }
    }
    
    statusSelect.addEventListener('change', checkStatus);
    keteranganText.addEventListener('input', validateForm);
    fotoBukti.addEventListener('change', validateForm);
    
    submitBtn.addEventListener('click', function(e) {
        if (statusSelect.value === 'Selesai') {
            if (!keteranganText.value.trim()) {
                e.preventDefault();
                keteranganText.classList.add('is-invalid');
                alert('Harap isi keterangan penanganan!');
                return false;
            }
            if (!fotoBukti.files.length) {
                e.preventDefault();
                fotoBukti.classList.add('is-invalid');
                alert('Harap upload foto bukti penanganan!');
                return false;
            }
        }
    });
    
    checkStatus();
</script>
@endpush
@endsection