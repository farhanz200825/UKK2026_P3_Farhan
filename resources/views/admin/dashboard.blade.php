@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-2 text-white">Total Siswa</h5>
                        <h3 class="mb-0 text-white">{{ $totalSiswa }}</h3>
                    </div>
                    <i class="ph ph-users" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-2 text-white">Total Guru</h5>
                        <h3 class="mb-0 text-white">{{ $totalGuru }}</h3>
                    </div>
                    <i class="ph ph-chalkboard-teacher" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-2 text-white">Total Aspirasi</h5>
                        <h3 class="mb-0 text-white">{{ $totalAspirasi }}</h3>
                    </div>
                    <i class="ph ph-chat-circle-text" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-2 text-white">Total Admin</h5>
                        <h3 class="mb-0 text-white">{{ $totalAdmin }}</h3>
                    </div>
                    <i class="ph ph-shield-check" style="font-size: 2.5rem;"></i>
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
                <p>Anda login sebagai <strong>Administrator</strong>. Silakan kelola data siswa, guru, dan aspirasi melalui menu yang tersedia.</p>
            </div>
        </div>
    </div>
</div>
@endsection