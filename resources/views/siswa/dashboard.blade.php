@extends('layouts.admin')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="row g-3">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 text-white">Aspirasi Saya</h6>
                        <h3 class="mb-0 text-white">{{ $totalAspirasi ?? 0 }}</h3>
                    </div>
                    <i class="ph ph-chat-circle-text" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 text-white">Menunggu</h6>
                        <h3 class="mb-0 text-white">{{ $aspirasiMenunggu ?? 0 }}</h3>
                    </div>
                    <i class="ph ph-clock" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 text-white">Selesai</h6>
                        <h3 class="mb-0 text-white">{{ $aspirasiSelesai ?? 0 }}</h3>
                    </div>
                    <i class="ph ph-check-circle" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Selamat Datang, {{ Auth::user()->email }}</h5>
            </div>
            <div class="card-body">
                <p>Anda login sebagai <strong>Siswa</strong>. Silakan buat aspirasi atau lihat status pengaduan Anda.</p>
                <hr>
                <div class="alert alert-success">
                    <i class="ph ph-info"></i> <strong>Informasi:</strong>
                    Anda dapat mengajukan aspirasi/pengaduan terkait sarana dan prasarana sekolah.
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
                    <a href="{{ route('siswa.aspirasi.create') }}" class="btn btn-primary">
                        <i class="ph ph-pencil-line"></i> Buat Aspirasi
                    </a>
                    <a href="{{ route('siswa.aspirasi.status') }}" class="btn btn-info">
                        <i class="ph ph-chart-line"></i> Lihat Status
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection