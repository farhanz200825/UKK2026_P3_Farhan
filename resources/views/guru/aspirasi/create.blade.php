@extends('layouts.admin')

@section('title', 'Buat Aspirasi - Guru')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ph ph-pencil-line"></i> Form Pengajuan Aspirasi (Guru)</h5>
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
                
                <form method="POST" action="{{ route('guru.aspirasi.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori Aspirasi <span class="text-danger">*</span></label>
                        <select name="id_kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                        <select name="id_ruangan" class="form-select" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($ruangans as $ruangan)
                                <option value="{{ $ruangan->id_ruangan }}" {{ old('id_ruangan') == $ruangan->id_ruangan ? 'selected' : '' }}>
                                    {{ $ruangan->kode_ruangan }} - {{ $ruangan->nama_ruangan }} ({{ $ruangan->jenis_ruangan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control" rows="5" 
                                  placeholder="Jelaskan secara detail kondisi sarana yang bermasalah..." required>{{ old('keterangan') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Foto (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted">Upload foto bukti (max 2MB, format: JPG, PNG)</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="ph ph-info"></i> <strong>Informasi:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Sebagai Guru, Anda dapat membuat aspirasi terkait sarana sekolah</li>
                            <li>Setelah dikirim, aspirasi akan ditindaklanjuti oleh Wali Kelas</li>
                            <li>Status aspirasi dapat dilihat di Dashboard Guru</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex justify-content-between gap-2">
                        <a href="{{ route('guru.aspirasi.index') }}" class="btn btn-secondary">
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