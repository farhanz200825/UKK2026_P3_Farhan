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
                                
                                
                                <td>{{ $a->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('guru.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-sm btn-info">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                
                              
                            @empty
                                32
                                    <td colspan="{{ $guru->canCreateAspirasi() ? '6' : '7' }}" class="text-center">
                                        Belum ada aspirasi
                                    
                                
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
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ph ph-chart-line"></i> Statistik Bulanan</h6>
            </div>
            <div class="card-body">
                <canvas id="chartAspirasi" height="200"></canvas>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="ph ph-info"></i> Informasi Akun</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Nama</th><td>{{ $guru->nama }}</td></tr>
                    <tr><th>Jabatan</th><td><span class="badge bg-{{ $guru->jabatan_badge }}">{{ $guru->jabatan }}</span></td></tr>
                    <tr><th>NIP</th><td>{{ $guru->nip ?? '-' }}</td></tr>
                    <tr><th>Mata Pelajaran</th><td>{{ $guru->mata_pelajaran ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartAspirasi').getContext('2d');
    new Chart(ctx, {
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
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush