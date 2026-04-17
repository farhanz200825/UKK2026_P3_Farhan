@extends('layouts.admin')

@section('title', 'Statistik Aspirasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ph ph-chart-line"></i> Statistik Aspirasi Sekolah</h5>
                <small>Data aspirasi per status, per bulan, per kategori, dan per ruangan</small>
            </div>
            <div class="card-body">
                <!-- Statistik Ringkasan Cards -->
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
                
                <!-- Grafik Aspirasi per Bulan (CSS Progress Bar) -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="ph ph-chart-bar"></i> Grafik Aspirasi per Bulan</h6>
                            </div>
                            <div class="card-body">
                                @if(isset($bulanLabels) && count($bulanLabels) > 0)
                                    @php
                                        $maxData = max($bulanData) > 0 ? max($bulanData) : 1;
                                    @endphp
                                    <div style="overflow-x: auto;">
                                        <div style="min-width: 500px;">
                                            @foreach($bulanLabels as $index => $label)
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span><strong>{{ $label }}</strong></span>
                                                    <span>{{ $bulanData[$index] }} aspirasi</span>
                                                </div>
                                                <div class="progress" style="height: 25px;">
                                                    <div class="progress-bar bg-primary" 
                                                         role="progressbar" 
                                                         style="width: {{ ($bulanData[$index] / $maxData) * 100 }}%"
                                                         aria-valuenow="{{ $bulanData[$index] }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="{{ $maxData }}">
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
                                        <p class="mt-2">Belum ada data aspirasi</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik per Kategori (Progress Bar) -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="ph ph-tag"></i> Statistik per Kategori</h6>
                            </div>
                            <div class="card-body">
                                @if($kategoriStat->count() > 0)
                                    @php
                                        $maxKategori = $kategoriStat->max('aspirasi_count') > 0 ? $kategoriStat->max('aspirasi_count') : 1;
                                    @endphp
                                    @foreach($kategoriStat as $k)
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>{{ $k->nama_kategori }}</span>
                                            <span>{{ $k->aspirasi_count }} aspirasi</span>
                                        </div>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-info" 
                                                 role="progressbar" 
                                                 style="width: {{ ($k->aspirasi_count / $maxKategori) * 100 }}%">
                                                {{ $k->aspirasi_count > 0 ? $k->aspirasi_count : '' }}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4">
                                        <p class="text-muted">Belum ada data kategori</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistik per Ruangan (Progress Bar) -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="ph ph-building"></i> Statistik per Ruangan</h6>
                            </div>
                            <div class="card-body">
                                @if($ruanganStat->count() > 0)
                                    @php
                                        $maxRuangan = $ruanganStat->max('aspirasi_count') > 0 ? $ruanganStat->max('aspirasi_count') : 1;
                                    @endphp
                                    <div style="max-height: 400px; overflow-y: auto;">
                                        @foreach($ruanganStat as $r)
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>{{ $r->nama_ruangan }} <small class="text-muted">({{ $r->kode_ruangan }})</small></span>
                                                <span>{{ $r->aspirasi_count }} aspirasi</span>
                                            </div>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" 
                                                     role="progressbar" 
                                                     style="width: {{ ($r->aspirasi_count / $maxRuangan) * 100 }}%">
                                                    {{ $r->aspirasi_count > 0 ? $r->aspirasi_count : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <p class="text-muted">Belum ada data ruangan</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik Persentase Status (Donut dengan CSS) -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="ph ph-pie-chart"></i> Persentase Status</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        @php
                                            $total = array_sum($statusStat);
                                            $persenMenunggu = $total > 0 ? round(($statusStat['Menunggu'] / $total) * 100) : 0;
                                            $persenProses = $total > 0 ? round(($statusStat['Proses'] / $total) * 100) : 0;
                                            $persenSelesai = $total > 0 ? round(($statusStat['Selesai'] / $total) * 100) : 0;
                                        @endphp
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <span><i class="ph ph-clock text-warning"></i> Menunggu</span>
                                                <span>{{ $persenMenunggu }}% ({{ $statusStat['Menunggu'] }})</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-warning" style="width: {{ $persenMenunggu }}%"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <span><i class="ph ph-spinner text-info"></i> Diproses</span>
                                                <span>{{ $persenProses }}% ({{ $statusStat['Proses'] }})</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-info" style="width: {{ $persenProses }}%"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <span><i class="ph ph-check-circle text-success"></i> Selesai</span>
                                                <span>{{ $persenSelesai }}% ({{ $statusStat['Selesai'] }})</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" style="width: {{ $persenSelesai }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="border rounded p-3 bg-light">
                                            <h1 class="text-primary mb-0">{{ $total }}</h1>
                                            <small class="text-muted">Total Aspirasi</small>
                                        </div>
                                    </div>
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