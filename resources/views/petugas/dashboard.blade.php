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
                                <th width="5%">ID</th>
                                <th width="20%">Pengirim</th>
                                <th width="15%">Kategori</th>
                                <th width="20%">Ruangan</th>
                                <th width="10%">Status</th>
                                <th width="15%">Tanggal</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aspirasiAktif as $a)
                            <tr>
                                <td>{{ $a->id_aspirasi }}</td>
                                <td>
                                    @php
                                        $pengirim = $a->user->siswa ?? $a->user->guru;
                                        $namaPengirim = $pengirim->nama ?? $a->user->email;
                                        $rolePengirim = $a->user->role;
                                        $detailPengirim = '';
                                        
                                        if($rolePengirim == 'siswa') {
                                            $detailPengirim = 'Siswa - ' . ($pengirim->kelas ?? '-');
                                        } elseif($rolePengirim == 'guru') {
                                            $detailPengirim = 'Guru - ' . ($pengirim->jabatan ?? '-');
                                        } else {
                                            $detailPengirim = ucfirst($rolePengirim);
                                        }
                                    @endphp
                                    <span class="fw-bold">{{ $namaPengirim }}</span>
                                    <br>
                                    <small class="text-muted">{{ $detailPengirim }}</small>
                                
                                
                                <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                <td>
                                    <span class="badge bg-{{ $a->status == 'Proses' ? 'info' : 'warning' }}">
                                        {{ $a->status }}
                                    </span>
                                
                                
                                <td>{{ $a->created_at ? $a->created_at->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('petugas.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-sm btn-info">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                
                              
                            @empty
                                32
                                    <td colspan="7" class="text-center">Tidak ada aspirasi aktif</span>
                                
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
        <!-- Statistik Persentase Status -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="ph ph-pie-chart"></i> Persentase Status</h6>
            </div>
            <div class="card-body">
                @php
                    $total = $totalAspirasi > 0 ? $totalAspirasi : 1;
                    $persenMenunggu = round(($aspirasiMenunggu / $total) * 100);
                    $persenProses = round(($aspirasiProses / $total) * 100);
                    $persenSelesai = round(($aspirasiSelesai / $total) * 100);
                @endphp
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span><i class="ph ph-clock"></i> Menunggu</span>
                        <span class="badge bg-warning">{{ $aspirasiMenunggu }} ({{ $persenMenunggu }}%)</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ $persenMenunggu }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span><i class="ph ph-spinner"></i> Diproses</span>
                        <span class="badge bg-info">{{ $aspirasiProses }} ({{ $persenProses }}%)</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: {{ $persenProses }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span><i class="ph ph-check-circle"></i> Selesai</span>
                        <span class="badge bg-success">{{ $aspirasiSelesai }} ({{ $persenSelesai }}%)</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $persenSelesai }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informasi Petugas -->
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="ph ph-info"></i> Informasi Petugas</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Nama</th><td>{{ $petugas->nama }}</td></tr>
                    <tr><th>NIP</th><td>{{ $petugas->nip ?? '-' }}</td></tr>
                    <tr><th>Status</th><td><span class="badge bg-{{ $petugas->status_badge }}">{{ $petugas->status }}</span></span>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection