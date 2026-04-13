@extends('layouts.admin')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $totalAspirasi }}</h3>
                <small>Total Aspirasi</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3>{{ $aspirasiMenunggu }}</h3>
                <small>Menunggu</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>{{ $aspirasiProses }}</h3>
                <small>Diproses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $aspirasiSelesai }}</h3>
                <small>Selesai</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ph ph-list"></i> Aspirasi Aktif</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Siswa/Guru</th>
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aspirasiAktif as $a)
                            <tr>
                                <td>{{ $a->id_aspirasi }}</td>
                                <td>
                                    @php
                                        $pengirim = $a->user->siswa ?? $a->user->guru;
                                    @endphp
                                    {{ $pengirim->nama ?? $a->user->email }}
                                    <br><small class="text-muted">{{ $pengirim->kelas ?? $pengirim->jabatan ?? '-' }}</small>
                                
                                
                                <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                <td>
                                    <span class="badge bg-{{ $a->status == 'Selesai' ? 'success' : ($a->status == 'Proses' ? 'info' : 'warning') }}">
                                        {{ $a->status }}
                                    </span>
                                
                                
                                <td>{{ $a->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('petugas.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-sm btn-info">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                
                              
                            @empty
                                32
                                    <td colspan="7" class="text-center">Tidak ada aspirasi aktif</td>
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('petugas.aspirasi.index') }}" class="btn btn-sm btn-primary">
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
                <h6 class="mb-0"><i class="ph ph-info"></i> Informasi Petugas</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Nama</th><td>{{ $petugas->nama }}</td></tr>
                    <tr><th>NIP</th><td>{{ $petugas->nip ?? '-' }}</td></tr>
                    <tr><th>Status</th><td><span class="badge bg-{{ $petugas->status_badge }}">{{ $petugas->status }}</span></td></tr>
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