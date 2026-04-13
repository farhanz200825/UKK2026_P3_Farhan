@extends('layouts.admin')

@section('title', 'History Perubahan Status')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Statistik Cards -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistik['total'] }}</h3>
                        <small>Total Perubahan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistik['menunggu'] }}</h3>
                        <small>Menjadi Menunggu</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistik['proses'] }}</h3>
                        <small>Menjadi Proses</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistik['selesai'] }}</h3>
                        <small>Menjadi Selesai</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ph ph-clock-counter-clockwise"></i> Riwayat Perubahan Status Aspirasi</h5>
                <small>Semua riwayat perubahan status dari waktu ke waktu</small>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Filter Status Baru</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
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
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal Perubahan</th>
                                <th>ID Aspirasi</th>
                                <th>Pengirim</th>
                                <th>Kategori</th>
                                <th>Ruangan</th>
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
                                <td>
                                    @php
                                        $pengirim = $h->aspirasi->user->siswa ?? $h->aspirasi->user->guru;
                                    @endphp
                                    {{ $pengirim->nama ?? $h->aspirasi->user->email }}
                                    <br><small class="text-muted">{{ $pengirim->kelas ?? $pengirim->jabatan ?? '-' }}</small>
                                
                                
                                <td>{{ $h->aspirasi->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $h->aspirasi->ruangan->nama_ruangan ?? $h->aspirasi->lokasi }}</td>
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
                                            if($pengubah->role == 'admin') {
                                                $namaPengubah = 'Admin';
                                            } elseif($pengubah->role == 'petugas' && $pengubah->petugas) {
                                                $namaPengubah = $pengubah->petugas->nama . ' (Petugas)';
                                            } elseif($pengubah->role == 'guru' && $pengubah->guru) {
                                                $namaPengubah = $pengubah->guru->nama . ' (' . $pengubah->guru->jabatan . ')';
                                            } else {
                                                $namaPengubah = $pengubah->email;
                                            }
                                        }
                                    @endphp
                                    {{ $namaPengubah }}
                                
                                
                                <td>
                                    <a href="{{ route('admin.pengaduan.detail', $h->id_aspirasi) }}" class="btn btn-sm btn-info">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                
                              
                            @empty
                                32
                                    <td colspan="10" class="text-center">Belum ada riwayat perubahan status</td>
                                
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