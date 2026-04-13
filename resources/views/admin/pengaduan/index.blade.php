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
                    <strong>History</strong>.
                </div>
                
                <!-- Filter -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">Semua</option>
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ruangan</label>
                        <select name="ruangan" class="form-select">
                            <option value="">Semua</option>
                            @foreach($ruangans as $ruangan)
                            <option value="{{ $ruangan->id_ruangan }}" {{ request('ruangan') == $ruangan->id_ruangan ? 'selected' : '' }}>
                                {{ $ruangan->kode_ruangan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dari Tgl</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sampai Tgl</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cari</label>
                        <input type="text" name="search" class="form-control" placeholder="Cari keterangan/lokasi..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('admin.pengaduan') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>ID</th>
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
                                <td>{{ $a->id_aspirasi }}</td>
                                <td>
                                    @php
                                        $pengirim = $a->user->siswa ?? $a->user->guru;
                                    @endphp
                                    {{ $pengirim->nama ?? $a->user->email }}
                                    <br><small class="text-muted">{{ $pengirim->kelas ?? $pengirim->jabatan ?? '-' }}</small>
                                
                                
                                <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                <td>{{ Str::limit($a->keterangan, 50) }}</td>
                                <td>
                                    <span class="badge bg-{{ $a->status == 'Proses' ? 'info' : 'warning' }}">
                                        {{ $a->status }}
                                    </span>
                                
                                
                                <td>{{ $a->created_at ? $a->created_at->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.pengaduan.detail', $a->id_aspirasi) }}" class="btn btn-info btn-sm">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $a->id_aspirasi }}">
                                        <i class="ph ph-trash"></i> Hapus
                                    </button>
                                
                              
                            @empty
                                32
                                    <td colspan="9" class="text-center">
                                        <div class="py-4">
                                            <i class="ph ph-check-circle ph-2x text-success"></i>
                                            <p class="mt-2">Semua aspirasi sudah selesai ditangani!</p>
                                            <a href="{{ route('admin.history') }}" class="btn btn-sm btn-success">
                                                <i class="ph ph-clock-counter-clockwise"></i> Lihat History Selesai
                                            </a>
                                        </div>
                                    </td>
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $aspirasi->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete untuk setiap aspirasi -->
@foreach($aspirasi as $a)
<div class="modal fade" id="deleteModal{{ $a->id_aspirasi }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus Aspirasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus aspirasi ini?</p>
                <p class="text-danger"><small>Data yang dihapus tidak dapat dikembalikan!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.pengaduan.destroy', $a->id_aspirasi) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection