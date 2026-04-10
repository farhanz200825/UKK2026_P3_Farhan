@extends('layouts.admin')

@section('title', 'Detail Aspirasi')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Aspirasi</h3>
            <a href="{{ route('admin.pengaduan') }}" class="btn btn-secondary float-right">Kembali</a>
        </div>
        <div class="card-body">
            @if(isset($aspirasi))
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID Aspirasi</th>
                        <td>{{ $aspirasi->id_aspirasi }}</td>
                    </tr>
                    <tr>
                        <th>Nama Siswa</th>
                        <td>{{ $aspirasi->siswa->nama ?? $aspirasi->user->siswa->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $aspirasi->kategori->nama_kategori ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $aspirasi->lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $aspirasi->keterangan }}</td>
                    </tr>
                    <tr>
                        <th>Foto</th>
                        <td>
                            @if($aspirasi->foto)
                                <img src="{{ asset('storage/' . $aspirasi->foto) }}" alt="Foto" width="200">
                            @else
                                Tidak ada foto
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge badge-{{ $aspirasi->status == 'Selesai' ? 'success' : ($aspirasi->status == 'Proses' ? 'warning' : 'secondary') }}">
                                {{ $aspirasi->status }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $aspirasi->created_at }}</td>
                    </tr>
                </table>

                <hr>

                <h4>History Status</h4>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Status Lama</th>
                            <th>Status Baru</th>
                            <th>Diubah Oleh</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aspirasi->historyStatus as $history)
                        <tr>
                            <td>{{ $history->status_lama }}</td>
                            <td>{{ $history->status_baru }}</td>
                            <td>{{ $history->pengubah->email ?? '-' }}</td>
                            <td>{{ $history->created_at }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">Tidak ada history</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <h4>Progres</h4>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Keterangan Progres</th>
                            <th>Dibuat Oleh</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aspirasi->progres as $p)
                        <tr>
                            <td>{{ $p->keterangan_progres }}</td>
                            <td>{{ $p->user->email ?? '-' }}</td>
                            <td>{{ $p->created_at }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">Belum ada progres</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($aspirasi->status != 'Selesai')
                <hr>
                <h4>Update Status</h4>
                <form action="{{ route('admin.pengaduan.update-status', $aspirasi->id_aspirasi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Menunggu" {{ $aspirasi->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Proses" {{ $aspirasi->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                            <option value="Selesai" {{ $aspirasi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan Progres</label>
                        <textarea name="keterangan_progres" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
                @endif
            @else
                <div class="alert alert-danger">Data tidak ditemukan</div>
            @endif
        </div>
    </div>
</div>
@endsection