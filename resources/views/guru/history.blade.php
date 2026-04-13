@extends('layouts.admin')

@section('title', 'History Perubahan Status')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ph ph-clock-counter-clockwise"></i> History Perubahan Status Aspirasi</h5>
                <small>
                    @if($guru->canCreateAspirasi())
                        Menampilkan history aspirasi yang Anda buat sendiri
                    @elseif($guru->canManageAspirasi())
                        Menampilkan semua history aspirasi (Wali Kelas)
                    @else
                        Menampilkan semua history aspirasi
                    @endif
                </small>
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

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>ID Aspirasi</th>
                                @if(!$guru->canCreateAspirasi())
                                <th>Pengirim</th>
                                @endif
                                <th>Kategori</th>
                                <th>Status Lama</th>
                                <th>Status Baru</th>
                                <th>Diubah Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $index => $h)
                            <tr>
                                <td>{{ $history->firstItem() + $index }}</td>
                                <td>{{ $h->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $h->id_aspirasi }}</td>
                                
                                @if(!$guru->canCreateAspirasi())
                                <td>
                                    @php
                                        $pengirim = $h->aspirasi->user->siswa ?? $h->aspirasi->user->guru;
                                    @endphp
                                    {{ $pengirim->nama ?? $h->aspirasi->user->email }}
                                    <br><small class="text-muted">{{ $pengirim->kelas ?? $pengirim->jabatan ?? '-' }}</small>
                                
                                
                                @endif
                                
                                <td>{{ $h->aspirasi->kategori->nama_kategori ?? '-' }}</td>
                                <td><span class="badge bg-secondary">{{ $h->status_lama }}</span></td>
                                <td>
                                    @php
                                        $statusClass = 'secondary';
                                        if($h->status_baru == 'Menunggu') $statusClass = 'warning';
                                        if($h->status_baru == 'Proses') $statusClass = 'info';
                                        if($h->status_baru == 'Selesai') $statusClass = 'success';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $h->status_baru }}</span>
                                
                                
                                <td>
                                    @php
                                        $pengubah = $h->pengubah;
                                        $namaPengubah = '-';
                                        if($pengubah) {
                                            if($pengubah->role == 'guru' && $pengubah->guru) {
                                                $namaPengubah = $pengubah->guru->nama . ' (' . $pengubah->guru->jabatan . ')';
                                            } elseif($pengubah->role == 'petugas' && $pengubah->petugas) {
                                                $namaPengubah = $pengubah->petugas->nama . ' (Petugas)';
                                            } elseif($pengubah->role == 'admin') {
                                                $namaPengubah = $pengubah->email . ' (Admin)';
                                            } else {
                                                $namaPengubah = $pengubah->email;
                                            }
                                        }
                                    @endphp
                                    {{ $namaPengubah }}
                                
                                
                                <td>
                                    <a href="{{ route('guru.aspirasi.detail', $h->id_aspirasi) }}" class="btn btn-sm btn-info">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                
                              
                            @empty
                                32
                                    <td colspan="{{ $guru->canCreateAspirasi() ? '8' : '9' }}" class="text-center">
                                        @if($guru->canCreateAspirasi())
                                            Belum ada riwayat perubahan status untuk aspirasi yang Anda buat
                                        @else
                                            Belum ada riwayat perubahan status
                                        @endif
                                    
                                
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