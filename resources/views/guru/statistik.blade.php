@extends('layouts.admin')

@section('title', 'Statistik Aspirasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ph ph-chart-line"></i> Statistik Aspirasi Sekolah</h5>
                <small>Data aspirasi per bulan, per kategori, dan per ruangan</small>
            </div>
            <div class="card-body">
                <!-- Statistik Ringkasan -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3>{{ $statusStat['Menunggu'] ?? 0 }}</h3>
                                <small>Menunggu</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3>{{ $statusStat['Proses'] ?? 0 }}</h3>
                                <small>Diproses</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3>{{ $statusStat['Selesai'] ?? 0 }}</h3>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>{{ array_sum($statusStat) }}</h3>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Grafik Aspirasi per Bulan</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chartBulanan" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Grafik Aspirasi per Kategori</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chartKategori" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Statistik per Ruangan</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Ruangan</th>
                                                <th>Jumlah Aspirasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ruanganStat as $r)
                                            <tr>
                                                <td>{{ $r->nama_ruangan }} ({{ $r->kode_ruangan }})</td>
                                                <td>{{ $r->aspirasi_count }} aspirasi</td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="2" class="text-center">Belum ada data</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Statistik per Kategori</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kategori</th>
                                                <th>Jumlah Aspirasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($kategoriStat as $k)
                                            <tr>
                                                <td>{{ $k->nama_kategori }}</td>
                                                <td>{{ $k->aspirasi_count }} aspirasi</td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="2" class="text-center">Belum ada data</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Bulanan
    new Chart(document.getElementById('chartBulanan'), {
        type: 'line',
        data: {
            labels: {!! json_encode($bulanLabels) !!},
            datasets: [{
                label: 'Jumlah Aspirasi',
                data: {!! json_encode($bulanData) !!},
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
    
    // Grafik Kategori
    new Chart(document.getElementById('chartKategori'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($kategoriStat->pluck('nama_kategori')) !!},
            datasets: [{
                data: {!! json_encode($kategoriStat->pluck('aspirasi_count')) !!},
                backgroundColor: ['#4f46e5', '#eab308', '#22c55e', '#ef4444', '#06b6d4', '#8b5cf6', '#ec4899']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush