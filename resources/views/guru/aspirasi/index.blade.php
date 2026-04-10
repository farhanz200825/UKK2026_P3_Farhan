@extends('layouts.admin')

@section('title', 'Data Aspirasi Aktif')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="ph ph-chat-circle-text"></i> Data Aspirasi Aktif</h5>
                <small class="text-muted">Menampilkan aspirasi yang belum selesai (Menunggu / Diproses)</small>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ph ph-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ph ph-x-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <!-- Filter Form -->
                <form method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Diproses</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cari</label>
                            <input type="text" name="search" class="form-control" placeholder="Cari lokasi atau keterangan..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ph ph-magnifying-glass"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Tanggal</th>
                                <th width="15%">Siswa</th>
                                <th width="10%">Kategori</th>
                                <th width="15%">Lokasi</th>
                                <th width="8%">Status</th>
                                <th width="22%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aspirasi as $index => $item)
                            <tr>
                                <td>{{ $index + $aspirasi->firstItem() }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <strong>{{ $item->user->siswa->nama ?? $item->user->email }}</strong><br>
                                    <small class="text-muted">NIS: {{ $item->user->siswa->nis ?? '-' }}</small>
                                    <br>
                                    <small class="text-muted">{{ $item->user->siswa->kelas ?? '-' }} - {{ $item->user->siswa->jurusan ?? '-' }}</small>
                                 </td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td>
                                    <i class="ph ph-map-pin"></i> {{ Str::limit($item->lokasi, 25) }}
                                 </td>
                                <td>
                                    @if($item->status == 'Menunggu')
                                        <span class="badge bg-warning text-dark">
                                            <i class="ph ph-clock"></i> Menunggu
                                        </span>
                                    @else
                                        <span class="badge bg-info">
                                            <i class="ph ph-spinner"></i> Diproses
                                        </span>
                                    @endif
                                 </td>
                                <td>
                                    <a href="{{ route('guru.aspirasi.detail', $item->id_aspirasi) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                    @if($item->status == 'Proses')
                                        <button type="button" class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" data-bs-target="#selesaiModal{{ $item->id_aspirasi }}">
                                            <i class="ph ph-check-circle"></i> Selesai
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id_aspirasi }}">
                                        <i class="ph ph-trash"></i> Hapus
                                    </button>
                                 </td>
                            </tr>
                             
                             <!-- Modal Selesai -->
                             @if($item->status == 'Proses')
                             <div class="modal fade" id="selesaiModal{{ $item->id_aspirasi }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">
                                                <i class="ph ph-check-circle"></i> Selesaikan Aspirasi
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menyelesaikan aspirasi ini?</p>
                                            <p><strong>Lokasi:</strong> {{ $item->lokasi }}</p>
                                            <p><strong>Siswa:</strong> {{ $item->user->siswa->nama ?? '-' }}</p>
                                            <div class="alert alert-info">
                                                <i class="ph ph-info"></i> Aspirasi yang sudah selesai akan dipindahkan ke History.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <a href="{{ route('guru.aspirasi.selesai', $item->id_aspirasi) }}" 
                                               class="btn btn-success">
                                                Ya, Selesaikan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Modal Delete -->
                            <div class="modal fade" id="deleteModal{{ $item->id_aspirasi }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="ph ph-trash"></i> Hapus Aspirasi
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-3">
                                                <i class="ph ph-warning-circle" style="font-size: 48px; color: #dc2626;"></i>
                                            </div>
                                            <p class="text-center">Apakah Anda yakin ingin menghapus aspirasi ini?</p>
                                            <p><strong>Lokasi:</strong> {{ $item->lokasi }}</p>
                                            <p><strong>Siswa:</strong> {{ $item->user->siswa->nama ?? '-' }}</p>
                                            <p><strong>Tanggal:</strong> {{ $item->created_at->format('d/m/Y H:i') }}</p>
                                            @if($item->foto)
                                            <div class="alert alert-warning">
                                                <i class="ph ph-warning"></i> Foto terkait juga akan dihapus.
                                            </div>
                                            @endif
                                            <div class="alert alert-danger">
                                                <i class="ph ph-warning"></i> <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('guru.aspirasi.destroy', $item->id_aspirasi) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="ph ph-trash"></i> Ya, Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="py-4">
                                        <i class="ph ph-check-circle" style="font-size: 48px; color: #28a745;"></i>
                                        <p class="mt-2 text-muted">Semua aspirasi sudah selesai</p>
                                        <a href="{{ route('guru.history') }}" class="btn btn-info btn-sm">
                                            <i class="ph ph-clock-counter-clockwise"></i> Lihat History
                                        </a>
                                    </div>
                                 </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $aspirasi->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection