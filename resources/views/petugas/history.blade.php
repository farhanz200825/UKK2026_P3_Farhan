@extends('layouts.admin')

@section('title', 'History Aspirasi Selesai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="ph ph-check-circle"></i> History Aspirasi Selesai</h5>
                <small>Daftar aspirasi yang sudah selesai ditangani</small>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Filter Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ph ph-funnel"></i> Filter
                        </button>
                    </div>
                </form>

                <div class="alert alert-info">
                    <i class="ph ph-info"></i> 
                    Hanya aspirasi dengan status <strong>Selesai</strong> yang muncul di halaman ini.
                    Aspirasi yang masih <strong>Menunggu</strong> atau <strong>Proses</strong> dapat dilihat di menu <strong>Data Aspirasi</strong>.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal Selesai</th>
                                <th>ID Aspirasi</th>
                                <th>Pengirim</th>
                                <th>Kategori</th>
                                <th>Status Akhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history->where('status_baru', 'Selesai') as $index => $h)
                            <tr>
                                <td>{{ $history->firstItem() + $index }}</td>
                                <td>{{ $h->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $h->id_aspirasi }}</td>
                                <td>
                                    @php
                                        $pengirim = $h->aspirasi->user->siswa ?? $h->aspirasi->user->guru;
                                    @endphp
                                    {{ $pengirim->nama ?? $h->aspirasi->user->email }}
                                    <br><small class="text-muted">{{ $pengirim->kelas ?? $pengirim->jabatan ?? '-' }}</small>
                                
                                
                                <td>{{ $h->aspirasi->kategori->nama_kategori ?? '-' }}</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>
                                    <a href="{{ route('petugas.aspirasi.detail', $h->id_aspirasi) }}" class="btn btn-sm btn-info">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                
                              
                            @empty
                                32
                                    <td colspan="7" class="text-center">Belum ada aspirasi yang selesai</td>
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $history->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection