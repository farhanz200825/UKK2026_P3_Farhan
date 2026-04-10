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
                
                <form method="POST" action="{{ route('siswa.aspirasi.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label">Kategori Aspirasi <span class="text-danger">*</span></label>
                        <select name="id_kategori" class="form-select" id="kategoriSelect" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}" 
                                        data-deskripsi="{{ $kategori->deskripsi }}"
                                        {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <div id="kategoriDeskripsi" class="small text-muted mt-1"></div>
                    </div>
                    
                    <!-- Lokasi -->
                    <div class="mb-3">
                        <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph ph-map-pin"></i></span>
                            <input type="text" name="lokasi" class="form-control" 
                                   value="{{ old('lokasi') }}" 
                                   placeholder="Contoh: Ruang Kelas X IPA 1" required>
                        </div>
                        <small class="text-muted">Tentukan lokasi yang jelas agar mudah ditemukan</small>
                    </div>
                    
                    <!-- Keterangan -->
                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control" rows="5" 
                                  placeholder="Jelaskan secara detail kondisi sarana yang bermasalah..." required>{{ old('keterangan') }}</textarea>
                    </div>
                    
                    <!-- Foto (Opsional) -->
                    <div class="mb-3">
                        <label class="form-label">Foto (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg" id="fotoInput">
                        <small class="text-muted">Upload foto bukti (max 2MB, format: JPG, PNG)</small>
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="previewImg" src="#" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="ph ph-info"></i> <strong>Informasi:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Setelah dikirim, aspirasi akan masuk ke admin/guru untuk ditindaklanjuti</li>
                            <li>Status aspirasi dapat dilihat di menu "Status Aspirasi"</li>
                            <li>Anda akan mendapatkan feedback dari guru/admin melalui menu "Feedback"</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex justify-content-between gap-2">
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">
                            <i class="ph ph-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
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
    
    kategoriSelect.addEventListener('change', showKategoriDeskripsi);
    showKategoriDeskripsi();
</script>
@endpush