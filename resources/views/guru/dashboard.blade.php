@extends('layouts.admin')

@section('title', 'Dashboard Guru')

@section('content')
<div class="row">
    <div class="col-md-6 col-xl-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-2 text-white">Total Aspirasi</h5>
                        <h3 class="mb-0 text-white">{{ $totalAspirasi ?? 0 }}</h3>
                    </div>
                    <i class="ph ph-chat-circle-text" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-2 text-white">Menunggu</h5>
                        <h3 class="mb-0 text-white">{{ $aspirasiMenunggu ?? 0 }}</h3>
                    </div>
                    <i class="ph ph-clock" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-2 text-white">Diproses</h5>
                        <h3 class="mb-0 text-white">{{ $aspirasiProses ?? 0 }}</h3>
                    </div>
                    <i class="ph ph-spinner" style="font-size: 2.5rem;"></i>
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
                <p>Anda login sebagai <strong>Guru</strong>. Silakan kelola aspirasi siswa melalui menu yang tersedia.</p>
                <hr>
                <div class="alert alert-info">
                    <i class="ph ph-info"></i> <strong>Informasi:</strong>
                    Anda dapat memberikan feedback dan mengupdate progres perbaikan pada setiap aspirasi yang diajukan oleh siswa.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection