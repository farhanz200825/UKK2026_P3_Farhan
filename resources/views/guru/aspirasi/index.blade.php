@extends('layouts.admin')

@section('title', 'Data Aspirasi')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Statistik Cards (sama seperti sebelumnya) -->
        <div class="row mb-3">
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
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ph ph-list"></i> 
                    @if($guru->jabatan == 'Wali Kelas')
                        @if($currentType == 'saya')
                            Aspirasi Saya
                        @else
                            Data Aspirasi Kelas
                        @endif
                    @elseif($guru->canCreateAspirasi())
                        Aspirasi Saya
                    @else
                        Data Aspirasi
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <!-- TABS KHUSUS UNTUK WALI KELAS -->
                @if($guru->jabatan == 'Wali Kelas')
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link {{ $currentType == 'kelas' ? 'active' : '' }}" 
                           href="{{ route('guru.aspirasi.index', ['type' => 'kelas']) }}">
                            <i class="ph ph-users"></i> Data Aspirasi Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentType == 'saya' ? 'active' : '' }}" 
                           href="{{ route('guru.aspirasi.index', ['type' => 'saya']) }}">
                            <i class="ph ph-user"></i> Aspirasi Saya
                        </a>
                    </li>
                </ul>
                @endif
                
                <!-- Alert Info -->
                <div class="alert alert-info mb-3">
                    <i class="ph ph-info"></i> 
                    <strong>Informasi:</strong> Halaman ini hanya menampilkan aspirasi dengan status 
                    <strong>Menunggu</strong> dan <strong>Proses</strong>.
                </div>
                
                <!-- Tombol Buat Aspirasi (untuk Guru dan Wali Kelas) -->
                @if($guru->canCreateAspirasi())
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('guru.aspirasi.create') }}" class="btn btn-primary">
                        <i class="ph ph-plus"></i> Buat Aspirasi Baru
                    </a>
                </div>
                @endif
                
                <!-- Tabel Aspirasi -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                @if($guru->jabatan == 'Wali Kelas' && $currentType == 'kelas')
                                <th>Siswa</th>
                                @endif
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
                                
                                @if($guru->jabatan == 'Wali Kelas' && $currentType == 'kelas')
                                <td>
                                    {{ $a->user->siswa->nama ?? '-' }}
                                    <br><small class="text-muted">{{ $a->user->siswa->kelas ?? '-' }}</small>
                                
                                
                                @endif
                                
                                <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                <td>{{ Str::limit($a->keterangan, 50) }}</td>
                                <td>
                                    <span class="badge bg-{{ $a->status == 'Proses' ? 'info' : 'warning' }}">
                                        {{ $a->status }}
                                    </span>
                                
                                
                                <td>{{ $a->created_at ? $a->created_at->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('guru.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-info btn-sm">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                
                              
                            @empty
                                32
                                    <td colspan="{{ ($guru->jabatan == 'Wali Kelas' && $currentType == 'kelas') ? '7' : '6' }}" class="text-center">
                                        Belum ada data aspirasi
                                    </td>
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $aspirasi->links() }}
            </div>
        </div>
    </div>
</div>
@endsection