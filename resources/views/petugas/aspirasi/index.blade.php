@extends('layouts.admin')

@section('title', 'Data Aspirasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ph ph-list"></i> Data Aspirasi</h5>
                <small>Menampilkan aspirasi dengan status Menunggu dan Proses</small>
            </div>
            <div class="card-body">
                <!-- Alert Info -->
                <div class="alert alert-info mb-3">
                    <i class="ph ph-info"></i> 
                    <strong>Informasi:</strong> Halaman ini hanya menampilkan aspirasi dengan status 
                    <strong>Menunggu</strong> dan <strong>Proses</strong>. 
                    Aspirasi yang sudah <strong>Selesai</strong> dapat dilihat di menu 
                    <strong>History Selesai</strong>.
                </div>
                
                <!-- Filter -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Diproses</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kategori</label>
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
                        <input type="text" name="search" class="form-control" placeholder="Cari keterangan atau lokasi..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="8%">ID</th>
                                <th width="20%">Pengirim</th>
                                <th width="15%">Kategori</th>
                                <th width="20%">Ruangan</th>
                                <th width="12%">Status</th>
                                <th width="12%">Tanggal</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aspirasi as $index => $a)
                            <tr>
                                <td>{{ $aspirasi->firstItem() + $index }}</td>
                                <td>{{ $a->id_aspirasi }}</td>
                                <td>
                                    @php
                                        $pengirim = $a->user->siswa ?? $a->user->guru;
                                        $namaPengirim = $pengirim->nama ?? $a->user->email;
                                        $rolePengirim = $a->user->role;
                                        $detailPengirim = '';
                                        
                                        if($rolePengirim == 'siswa') {
                                            $detailPengirim = 'Siswa - ' . ($pengirim->kelas ?? '-');
                                        } elseif($rolePengirim == 'guru') {
                                            $detailPengirim = 'Guru - ' . ($pengirim->jabatan ?? '-');
                                        } else {
                                            $detailPengirim = ucfirst($rolePengirim);
                                        }
                                    @endphp
                                    <span class="fw-bold">{{ $namaPengirim }}</span>
                                    <br>
                                    <small class="text-muted">{{ $detailPengirim }}</small>
                                
                                
                                <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                <td>
                                    <span class="badge bg-{{ $a->status == 'Proses' ? 'info' : 'warning' }}">
                                        {{ $a->status }}
                                    </span>
                                
                                
                                <td>{{ $a->created_at ? $a->created_at->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('petugas.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-info btn-sm">
                                        <i class="ph ph-eye"></i>
                                    </a>
                                
                              
                            @empty
                                32
                                    <td colspan="8" class="text-center">
                                        <div class="py-4">
                                            <i class="ph ph-check-circle ph-2x text-success"></i>
                                            <p class="mt-2">Semua aspirasi sudah selesai ditangani!</p>
                                            <a href="{{ route('petugas.history') }}" class="btn btn-sm btn-success">
                                                <i class="ph ph-clock-counter-clockwise"></i> Lihat History Selesai
                                            </a>
                                        </div>
                                    </span>
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $aspirasi->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection