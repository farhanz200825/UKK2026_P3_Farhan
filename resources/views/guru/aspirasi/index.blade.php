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
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ph ph-chalkboard-teacher"></i> 
                    Dashboard - {{ $guru->jabatan }}
                </h5>
                <small>
                    @if($guru->canCreateAspirasi())
                        <span class="text-primary">✓ Anda dapat membuat aspirasi</span>
                    @elseif($guru->canManageAspirasi())
                        <span class="text-success">✓ Anda dapat mengelola aspirasi (feedback, progres, update status)</span>
                    @elseif($guru->canViewAllAspirasi())
                        <span class="text-info">✓ Anda dapat melihat semua data aspirasi</span>
                    @endif
                    
                    @if($guru->canViewStatistik())
                        <span class="text-secondary"> | 📊 Dapat melihat statistik</span>
                    @endif
                </small>
            </div>
            <div class="card-body">
                @if($guru->canCreateAspirasi())
                    <!-- GURU: bisa buat aspirasi dan lihat aspirasi sendiri -->
                    <div class="d-flex justify-content-between mb-3">
                        <div></div>
                        <a href="{{ route('guru.aspirasi.create') }}" class="btn btn-primary">
                            <i class="ph ph-plus"></i> Buat Aspirasi Baru
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kategori</th>
                                    <th>Ruangan</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($aspirasi as $index => $a)
                                <tr>
                                    <td>{{ $aspirasi->firstItem() + $index }}</td>
                                    <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                    <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                    <td>{{ Str::limit($a->keterangan, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $a->status == 'Selesai' ? 'success' : ($a->status == 'Proses' ? 'info' : 'warning') }}">
                                            {{ $a->status }}
                                        </span>
                                    </td>
                                    <td>{{ $a->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('guru.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-info btn-sm">
                                            <i class="ph ph-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center">Belum ada aspirasi yang diajukan</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $aspirasi->links() }}
                    
                @elseif($guru->canViewAllAspirasi())
                    <!-- WALI KELAS, KEPALA SEKOLAH, WAKIL, KEPALA JURUSAN: bisa lihat semua aspirasi -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Pengirim</th>
                                    <th>Kategori</th>
                                    <th>Ruangan</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($aspirasi as $index => $a)
                                <tr>
                                    <td>{{ $aspirasi->firstItem() + $index }}</td>
                                    <td>
                                        @php
                                            $pengirim = $a->user->siswa ?? $a->user->guru;
                                        @endphp
                                        {{ $pengirim->nama ?? $a->user->email }}
                                        <br><small class="text-muted">{{ $pengirim->kelas ?? $pengirim->jabatan ?? '-' }}</small>
                                    </td>
                                    <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                    <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                    <td>{{ Str::limit($a->keterangan, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $a->status == 'Selesai' ? 'success' : ($a->status == 'Proses' ? 'info' : 'warning') }}">
                                            {{ $a->status }}
                                        </span>
                                    </td>
                                    <td>{{ $a->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('guru.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-info btn-sm">
                                            <i class="ph ph-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center">Belum ada aspirasi</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $aspirasi->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection