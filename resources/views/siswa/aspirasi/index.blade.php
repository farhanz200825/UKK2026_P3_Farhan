@extends('layouts.siswa')

@section('title', 'Daftar Aspirasi Saya')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="ph ph-list"></i> Daftar Aspirasi Saya</h6>
        <a href="{{ route('siswa.aspirasi.create') }}" class="btn btn-primary btn-sm">
            <i class="ph ph-plus"></i> Buat Aspirasi Baru
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
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
                        <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $a->lokasi }}</td>
                        <td>{{ Str::limit($a->keterangan, 50) }}</td>
                        <td>
                            <span class="badge bg-{{ $a->status == 'Selesai' ? 'success' : ($a->status == 'Proses' ? 'warning' : 'secondary') }}">
                                {{ $a->status }}
                            </span>
                        </td>
                        <td>{{ $a->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('siswa.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-info btn-sm">
                                <i class="ph ph-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Belum ada aspirasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $aspirasi->links() }}
    </div>
</div>
@endsection