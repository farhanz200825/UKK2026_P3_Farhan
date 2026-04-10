@extends('layouts.admin')

@section('title', 'Daftar Aspirasi')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Aspirasi Sarana Sekolah</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <table class="table table-bordered table-striped">
                <thead>
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
                        <td>{{ $a->siswa->nama ?? $a->user->siswa->nama ?? '-' }}</td>
                        <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $a->lokasi }}</td>
                        <td>{{ Str::limit($a->keterangan, 50) }}</td>
                        <td>
                            <span class="badge badge-{{ $a->status == 'Selesai' ? 'success' : ($a->status == 'Proses' ? 'warning' : 'secondary') }}">
                                {{ $a->status }}
                            </span>
                        </td>
                        <td>{{ $a->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.pengaduan.detail', $a->id_aspirasi) }}" class="btn btn-sm btn-info">Detail</a>
                            <form action="{{ route('admin.pengaduan.destroy', $a->id_aspirasi) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data aspirasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{ $aspirasi->links() }}
        </div>
    </div>
</div>
@endsection