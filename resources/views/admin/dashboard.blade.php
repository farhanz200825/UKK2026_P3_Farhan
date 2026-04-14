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
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Status Aspirasi</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Menunggu</span>
                    <span class="badge bg-warning">{{ $aspirasiMenunggu }}</span>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar bg-warning" style="width: {{ $totalAspirasi > 0 ? ($aspirasiMenunggu/$totalAspirasi)*100 : 0 }}%"></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Diproses</span>
                    <span class="badge bg-info">{{ $aspirasiProses }}</span>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar bg-info" style="width: {{ $totalAspirasi > 0 ? ($aspirasiProses/$totalAspirasi)*100 : 0 }}%"></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Selesai</span>
                    <span class="badge bg-success">{{ $aspirasiSelesai }}</span>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar bg-success" style="width: {{ $totalAspirasi > 0 ? ($aspirasiSelesai/$totalAspirasi)*100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="ph ph-chart-bar"></i> Grafik Aspirasi per Bulan</h5>
            </div>
            <div class="card-body">
                @if(isset($bulanLabels) && count($bulanLabels) > 0)
                    <!-- Grafik CSS Sederhana -->
                    <div style="overflow-x: auto;">
                        <div style="min-width: 500px;">
                            @foreach($bulanLabels as $index => $label)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>{{ $label }}</span>
                                    <span><strong>{{ $bulanData[$index] }}</strong> aspirasi</span>
                                </div>
                                <div class="progress">
                                    @php
                                        $maxData = max($bulanData) > 0 ? max($bulanData) : 1;
                                        $persen = ($bulanData[$index] / $maxData) * 100;
                                    @endphp
                                    <div class="progress-bar bg-primary" style="width: {{ $persen }}%">
                                        {{ $bulanData[$index] > 0 ? $bulanData[$index] : '' }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ph ph-chart-line ph-2x text-muted"></i>
                        <p class="mt-2">Belum ada data untuk grafik</p>
                    </div>
                @endif
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
                <p>Anda login sebagai <strong>Administrator</strong>. Silakan kelola data siswa, guru, kategori, dan aspirasi melalui menu yang tersedia.</p>
            </div>
        </div>
    </div>
</div>
@endsection