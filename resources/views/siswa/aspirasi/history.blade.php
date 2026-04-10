@extends('layouts.admin')

@section('title', 'History Aspirasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="ph ph-clock-counter-clockwise"></i> History Aspirasi Selesai</h5>
                <small class="text-muted">Menampilkan aspirasi yang sudah selesai ditangani</small>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ph ph-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <!-- Tampilan Desktop -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Tanggal Selesai</th>
                                <th width="20%">Kategori</th>
                                <th width="30%">Lokasi</th>
                                <th width="15%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aspirasi as $index => $item)
                            <tr>
                                <td>{{ $index + $aspirasi->firstItem() }}</td>
                                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ Str::limit($item->lokasi, 40) }}</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>
                                    <a href="{{ route('siswa.aspirasi.detail', $item->id_aspirasi) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                 </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="py-4">
                                        <i class="ph ph-clock-counter-clockwise" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="mt-2 text-muted">Belum ada history aspirasi yang selesai</p>
                                        <a href="{{ route('siswa.aspirasi.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ph ph-pencil-line"></i> Buat Aspirasi Baru
                                        </a>
                                    </div>
                                 </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Tampilan Mobile -->
                <div class="d-md-none">
                    @forelse($aspirasi as $index => $item)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <small class="text-muted">
                                        <i class="ph ph-calendar"></i> Selesai: {{ $item->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                    <span class="badge bg-success">Selesai</span>
                                </div>
                                <div class="mb-2">
                                    <strong><i class="ph ph-tag"></i> Kategori:</strong> {{ $item->kategori->nama_kategori ?? '-' }}
                                </div>
                                <div class="mb-2">
                                    <strong><i class="ph ph-map-pin"></i> Lokasi:</strong> {{ $item->lokasi }}
                                </div>
                                <hr>
                                <a href="{{ route('siswa.aspirasi.detail', $item->id_aspirasi) }}" 
                                   class="btn btn-info btn-sm w-100">
                                    <i class="ph ph-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="ph ph-clock-counter-clockwise" style="font-size: 48px; color: #ccc;"></i>
                            <p class="mt-2 text-muted">Belum ada history aspirasi yang selesai</p>
                            <a href="{{ route('siswa.aspirasi.create') }}" class="btn btn-primary">
                                <i class="ph ph-pencil-line"></i> Buat Aspirasi Baru
                            </a>
                        </div>
                    @endforelse
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $aspirasi->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection