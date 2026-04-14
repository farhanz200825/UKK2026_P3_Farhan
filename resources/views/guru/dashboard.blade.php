@extends('layouts.admin')

@section('title', 'Dashboard Guru')

@section('content')
<div class="row">
    <!-- Statistik Cards -->
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $statistik['total'] }}</h3>
                <small>Total Aspirasi</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3>{{ $statistik['menunggu'] }}</h3>
                <small>Menunggu</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>{{ $statistik['proses'] }}</h3>
                <small>Diproses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $statistik['selesai'] }}</h3>
                <small>Selesai</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ph ph-list"></i> Aspirasi Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @if(!$guru->canCreateAspirasi())
                                <th>Pengirim</th>
                                @endif
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aspirasiTerbaru as $a)
                            <tr>
                                <td>{{ $a->id_aspirasi }}</td>
                                
                                @if(!$guru->canCreateAspirasi())
                                <td>
                                    @php
                                        $pengirim = $a->user->siswa ?? $a->user->guru;
                                    @endphp
                                    {{ $pengirim->nama ?? $a->user->email }}
                                    <br><small class="text-muted">{{ $pengirim->kelas ?? $pengirim->jabatan ?? '-' }}</small>
                                
                                
                                @endif
                                
                                <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                <td>
                                    <span class="badge bg-{{ $a->status == 'Selesai' ? 'success' : ($a->status == 'Proses' ? 'info' : 'warning') }}">
                                        {{ $a->status }}
                                    </span>
                                
                                
                                <td>{{ $a->created_at ? $a->created_at->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('guru.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-sm btn-info">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                
                              
                            @empty
                                32
                                    <td colspan="{{ $guru->canCreateAspirasi() ? '6' : '7' }}" class="text-center">
                                        Belum ada aspirasi
                                    </td>
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('guru.aspirasi.index') }}" class="btn btn-sm btn-primary">
                        <i class="ph ph-list"></i> Lihat Semua Aspirasi
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4"> 
        <!-- PERSENTASE STATUS - HANYA UNTUK JABATAN TERTENTU -->
        @if($guru->canViewStatistik() || $guru->jabatan == 'Kepala Sekolah' || $guru->jabatan == 'Wakil Kepala Sekolah' || $guru->jabatan == 'Kepala Jurusan')
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="ph ph-pie-chart"></i> Persentase Status</h6>
            </div>
            <div class="card-body">
                @php
                    $total = $statistik['total'] > 0 ? $statistik['total'] : 1;
                    $persenMenunggu = round(($statistik['menunggu'] / $total) * 100);
                    $persenProses = round(($statistik['proses'] / $total) * 100);
                    $persenSelesai = round(($statistik['selesai'] / $total) * 100);
                @endphp
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Menunggu</span>
                        <span>{{ $persenMenunggu }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ $persenMenunggu }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Diproses</span>
                        <span>{{ $persenProses }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: {{ $persenProses }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Selesai</span>
                        <span>{{ $persenSelesai }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $persenSelesai }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- INFORMASI AKUN - UNTUK SEMUA JABATAN -->
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="ph ph-info"></i> Informasi Akun</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Nama</th><td>{{ $guru->nama }}</td></tr>
                    <tr><th>Jabatan</th><td><span class="badge bg-{{ $guru->jabatan_badge }}">{{ $guru->jabatan }}</span></td></tr>
                    <tr><th>NIP</th><td>{{ $guru->nip ?? '-' }}</td></tr>
                    <tr><th>Mata Pelajaran</th><td>{{ $guru->mata_pelajaran ?? '-' }}Ru
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('chartAspirasi');
        if (canvas) {
            const labels = {!! json_encode($bulanLabels ?? []) !!};
            const data = {!! json_encode($bulanData ?? []) !!};
            
            if (labels.length > 0 && data.length > 0) {
                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Aspirasi',
                            data: data,
                            backgroundColor: 'rgba(79, 70, 229, 0.7)',
                            borderColor: '#4f46e5',
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Jumlah: ' + context.raw + ' aspirasi';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1 },
                                title: { display: true, text: 'Jumlah Aspirasi' }
                            },
                            x: {
                                title: { display: true, text: 'Bulan' }
                            }
                        }
                    }
                });
            }
        }
    });
</script>
@endpush