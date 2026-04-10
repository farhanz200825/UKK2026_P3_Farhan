@extends('layouts.petugas')

@section('title', 'Daftar Aspirasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0"><i class="ph ph-filters"></i> Filter Aspirasi</h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Cari keterangan atau lokasi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h6 class="mb-0"><i class="ph ph-list"></i> Daftar Aspirasi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Siswa</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aspirasi as $a)
                    <tr>
                        <td>{{ $a->id_aspirasi }}</td>
                        <td>{{ $a->user->siswa->nama ?? '-' }} <br>
                            <small class="text-muted">{{ $a->user->siswa->kelas ?? '-' }}</small>
                        </td>
                        <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $a->lokasi }}</td>
                        <td>{{ Str::limit($a->keterangan, 50) }}</td>
                        <td>
                            <span class="badge bg-{{ $a->status == 'Selesai' ? 'success' : ($a->status == 'Proses' ? 'warning' : 'secondary') }}">
                                {{ $a->status }}
                            </span>
                        </td>
                        <td>{{ $a->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('petugas.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-info btn-sm">
                                <i class="ph ph-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data aspirasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $aspirasi->withQueryString()->links() }}
    </div>
</div>
@endsection