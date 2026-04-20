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
                    <div class="col-md-4">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ph ph-funnel"></i> Filter
                        </button>
                        <a href="{{ route('admin.history') }}" class="btn btn-secondary w-100">
                            <i class="ph ph-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Tanggal Selesai</th>
                                <th width="5%">ID</th>
                                <th width="20%">Pengirim</th>
                                <th width="12%">Kategori</th>
                                <th width="15%">Ruangan</th>
                                <th width="8%">Status</th>
                                <th width="15%">Ditangani Oleh</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $index => $h)
                            <tr>
                                <td>{{ $history->firstItem() + $index }}</td>
                                <td>
                                    @php
                                        $tanggal = '-';
                                        if($h->created_at) {
                                            if($h->created_at instanceof \Carbon\Carbon) {
                                                $tanggal = $h->created_at->format('d/m/Y H:i:s');
                                            } else {
                                                $tanggal = date('d/m/Y H:i:s', strtotime($h->created_at));
                                            }
                                        }
                                    @endphp
                                    {{ $tanggal }}
                                
                                
                                <td>{{ $h->id_aspirasi }}</td>
                                
                                <!-- PENGIRIM -->
                                <td>
                                    @php
                                        $pengirim = $h->aspirasi->user->siswa ?? $h->aspirasi->user->guru;
                                        $namaPengirim = $pengirim->nama ?? $h->aspirasi->user->email;
                                        $rolePengirim = $h->aspirasi->user->role;
                                        $detailPengirim = '';
                                        
                                        if($rolePengirim == 'siswa') {
                                            $kelas = $pengirim->kelasRelasi->nama_kelas ?? $pengirim->kelas ?? '-';
                                            $detailPengirim = 'Siswa - ' . $kelas;
                                        } elseif($rolePengirim == 'guru') {
                                            $jabatan = $pengirim->jabatan ?? '-';
                                            $detailPengirim = 'Guru - ' . $jabatan;
                                        } else {
                                            $detailPengirim = ucfirst($rolePengirim);
                                        }
                                    @endphp
                                    <span class="fw-bold">{{ $namaPengirim }}</span>
                                    <br>
                                    <small class="text-muted">{{ $detailPengirim }}</small>
                                
                                
                                <td>{{ $h->aspirasi->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $h->aspirasi->ruangan->nama_ruangan ?? $h->aspirasi->lokasi }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="ph ph-check-circle me-1"></i> Selesai
                                    </span>
                                
                                
                                <!-- DITANGANI OLEH -->
                                <td>
                                    @php
                                        $pengubah = $h->pengubah;
                                        $namaPengubah = '-';
                                        $detailPengubah = '';
                                        
                                        if($pengubah) {
                                            if($pengubah->role == 'admin') {
                                                $namaPengubah = 'Admin';
                                                $detailPengubah = 'Administrator';
                                            } elseif($pengubah->role == 'petugas' && $pengubah->petugas) {
                                                $namaPengubah = $pengubah->petugas->nama;
                                                $detailPengubah = 'Petugas';
                                            } elseif($pengubah->role == 'guru' && $pengubah->guru) {
                                                $namaPengubah = $pengubah->guru->nama;
                                                $detailPengubah = 'Guru - ' . ($pengubah->guru->jabatan ?? '-');
                                            } else {
                                                $namaPengubah = $pengubah->email;
                                                $detailPengubah = ucfirst($pengubah->role);
                                            }
                                        }
                                    @endphp
                                    <span class="fw-bold">{{ $namaPengubah }}</span>
                                    <br>
                                    <small class="text-muted">{{ $detailPengubah }}</small>
                                
                                
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.pengaduan.detail', $h->id_aspirasi) }}" class="btn btn-info btn-sm" title="Detail">
                                            <i class="ph ph-eye"></i>
                                        </a>
                                    </div>
                                
                              
                            @empty
                                32
                                    <td colspan="9" class="text-center">
                                        <div class="py-4">
                                            <i class="ph ph-check-circle ph-2x text-muted"></i>
                                            <p class="mt-2">Belum ada aspirasi yang selesai</p>
                                            <a href="{{ route('admin.pengaduan') }}" class="btn btn-sm btn-primary">
                                                <i class="ph ph-list"></i> Lihat Data Aspirasi Aktif
                                            </a>
                                        </div>
                                    </td>
                                
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