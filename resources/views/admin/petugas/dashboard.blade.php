@extends('layouts.admin')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Aspirasi</h6>
                        <h2 class="mb-0">{{ $totalAspirasi }}</h2>
                    </div>
                    <i class="ph ph-chat-circle ph-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Menunggu</h6>
                        <h2 class="mb-0">{{ $aspirasiMenunggu }}</h2>
                    </div>
                    <i class="ph ph-clock ph-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Diproses</h6>
                        <h2 class="mb-0">{{ $aspirasiProses }}</h2>
                    </div>
                    <i class="ph ph-spinner ph-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Selesai</h6>
                        <h2 class="mb-0">{{ $aspirasiSelesai }}</h2>
                    </div>
                    <i class="ph ph-check-circle ph-2x"></i>
                </div>
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Siswa</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aspirasiAktif as $aspirasi)
                            <tr>
                                <td>{{ $aspirasi->id_aspirasi }}</td>
                                <td>{{ $aspirasi->user->siswa->nama ?? '-' }}</td>
                                <td>{{ $aspirasi->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $aspirasi->lokasi }}</td>
                                <td>
                                    <span class="badge bg-{{ $aspirasi->status == 'Selesai' ? 'success' : ($aspirasi->status == 'Proses' ? 'warning' : 'secondary') }}">
                                        {{ $aspirasi->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('petugas.aspirasi.detail', $aspirasi->id_aspirasi) }}" class="btn btn-sm btn-info">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center">Tidak ada aspirasi aktif</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $aspirasiAktif->links() }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ph ph-chart-line"></i> Statistik Bulanan</h6>
            </div>
            <div class="card-body">
                <canvas id="chartAspirasi" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body bg-light">
                <div class="d-flex align-items-center">
                    <i class="ph ph-info ph-2x text-primary me-3"></i>
                    <div>
                        <h6 class="mb-1">Selamat Datang, {{ $petugas->nama }}</h6>
                        <p class="mb-0 text-muted small">
                            Anda dapat mengelola aspirasi yang masuk, memberikan feedback, 
                            mengupdate progres, dan mengubah status aspirasi menjadi Proses atau Selesai.
                        </p>
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