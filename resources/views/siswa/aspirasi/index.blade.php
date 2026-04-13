@extends('layouts.admin')

@section('title', 'Daftar Aspirasi Saya')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ph ph-list"></i> Daftar Aspirasi Saya</h5>
                <a href="{{ route('siswa.aspirasi.create') }}" class="btn btn-primary btn-sm">
                    <i class="ph ph-plus"></i> Buat Aspirasi Baru
                </a>
            </div>
            <div class="card-body">
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
                                <!-- Gunakan $loop->iteration atau $index + 1 -->
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                <td>{{ Str::limit($a->keterangan, 50) }}</td>
                                <td>
                                    @php
                                        $statusClass = 'secondary';
                                        if($a->status == 'Menunggu') $statusClass = 'warning';
                                        if($a->status == 'Proses') $statusClass = 'info';
                                        if($a->status == 'Selesai') $statusClass = 'success';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $a->status }}</span>
                                </td>
                                <td>{{ $a->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('siswa.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-info btn-sm">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada aspirasi yang diajukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination - HANYA jika menggunakan paginate() -->
                @if(method_exists($aspirasi, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $aspirasi->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection