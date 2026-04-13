@extends('layouts.admin')

@section('title', 'Data Aspirasi Aktif')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Statistik Cards -->
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
                <h5 class="mb-0"><i class="ph ph-list"></i> Data Aspirasi Aktif</h5>
                <small>Menampilkan aspirasi dengan status Menunggu dan Proses</small>
            </div>
            <div class="card-body">
                <!-- Alert Info -->
                <div class="alert alert-info mb-3">
                    <i class="ph ph-info"></i> 
                    <strong>Informasi:</strong> Halaman ini hanya menampilkan aspirasi dengan status 
                    <strong>Menunggu</strong> dan <strong>Proses</strong>. 
                    Aspirasi yang sudah <strong>Selesai</strong> dapat dilihat di menu 
                    <strong>History Selesai</strong>.
                </div>
                
                <!-- FORM FILTER -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ph ph-funnel"></i> Filter Data</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Diproses</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ruangan</label>
                                <select name="ruangan" class="form-select">
                                    <option value="">Semua Ruangan</option>
                                    @foreach($ruangans as $ruangan)
                                    <option value="{{ $ruangan->id_ruangan }}" {{ request('ruangan') == $ruangan->id_ruangan ? 'selected' : '' }}>
                                        {{ $ruangan->kode_ruangan }} - {{ $ruangan->nama_ruangan }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Dari">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Sampai">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pencarian</label>
                                <input type="text" name="search" class="form-control" placeholder="Cari keterangan atau lokasi..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ph ph-magnifying-glass"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <a href="{{ route('guru.aspirasi.index') }}" class="btn btn-secondary w-100">
                                    <i class="ph ph-arrow-clockwise"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                @if($guru->canCreateAspirasi())
                    <!-- GURU: bisa buat aspirasi -->
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('guru.aspirasi.create') }}" class="btn btn-primary">
                            <i class="ph ph-plus"></i> Buat Aspirasi Baru
                        </a>
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                @if(!$guru->canCreateAspirasi())
                                <th>Pengirim</th>
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
                                    <td colspan="{{ $guru->canCreateAspirasi() ? '7' : '8' }}" class="text-center">
                                        <div class="py-4">
                                            <i class="ph ph-check-circle ph-2x text-success"></i>
                                            <p class="mt-2">Semua aspirasi sudah selesai ditangani!</p>
                                            <a href="{{ route('guru.history') }}" class="btn btn-sm btn-success">
                                                <i class="ph ph-clock-counter-clockwise"></i> Lihat History Selesai
                                            </a>
                                        </div>
                                    
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $aspirasi->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection