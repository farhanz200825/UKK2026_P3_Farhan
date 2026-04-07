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
                <h5>Grafik Aspirasi per Bulan</h5>
            </div>
            <div class="card-body">
                <canvas id="aspirasiChart" height="200"></canvas>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('aspirasiChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($bulanLabels) !!},
            datasets: [{
                label: 'Jumlah Aspirasi',
                data: {!! json_encode($bulanData) !!},
                borderColor: '#4680ff',
                backgroundColor: 'rgba(70, 128, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
@endsection