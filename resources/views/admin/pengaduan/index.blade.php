@extends('layouts.admin')

@section('title', 'Data Aspirasi Aktif')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ph ph-list"></i> Data Aspirasi Aktif</h5>
                <small>Menampilkan aspirasi dengan status Menunggu dan Proses</small>
            </div>
            <div class="card-body">
                
                <!-- Filter -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">Semua</option>
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ruangan</label>
                        <select name="ruangan" class="form-select">
                            <option value="">Semua</option>
                            @foreach($ruangans as $ruangan)
                            <option value="{{ $ruangan->id_ruangan }}" {{ request('ruangan') == $ruangan->id_ruangan ? 'selected' : '' }}>
                                {{ $ruangan->kode_ruangan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dari Tgl</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sampai Tgl</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cari</label>
                        <input type="text" name="search" class="form-control" placeholder="Cari keterangan/lokasi..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('admin.pengaduan') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="5%">ID</th>
                                <th width="20%">Pengirim</th>
                                <th width="12%">Kategori</th>
                                <th width="15%">Ruangan</th>
                                <th width="20%">Keterangan</th>
                                <th width="8%">Status</th>
                                <th width="10%">Tanggal</th>
                                <th width="5%">Aksi</th>
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
                                
                                
                                <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                <td>{{ Str::limit($a->keterangan, 50) }}</span>
                                <td>
                                    @php
                                        $statusBadge = 'warning';
                                        $statusIcon = 'ph-clock';
                                        if($a->status == 'Proses') {
                                            $statusBadge = 'info';
                                            $statusIcon = 'ph-spinner';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $statusBadge }}">
                                        <i class="{{ $statusIcon }} me-1"></i> {{ $a->status }}
                                    </span>
                                
                                
                                <td>{{ $a->created_at ? $a->created_at->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.pengaduan.detail', $a->id_aspirasi) }}" class="btn btn-info btn-sm" title="Detail">
                                            <i class="ph ph-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $a->id_aspirasi }}">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </div>
                                
                              
                            @empty
                                32
                                    <td colspan="9" class="text-center">
                                        <div class="py-4">
                                            <i class="ph ph-check-circle ph-2x text-success"></i>
                                            <p class="mt-2">Semua aspirasi sudah selesai ditangani!</p>
                                            <a href="{{ route('admin.history') }}" class="btn btn-sm btn-success">
                                                <i class="ph ph-clock-counter-clockwise"></i> Lihat History Selesai
                                            </a>
                                        </div>
                                    </td>
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $aspirasi->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete untuk setiap aspirasi -->
@foreach($aspirasi as $a)
<div class="modal fade" id="deleteModal{{ $a->id_aspirasi }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus Aspirasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @php
                    $pengirim = $a->user->siswa ?? $a->user->guru;
                    $namaPengirim = $pengirim->nama ?? $a->user->email;
                @endphp
                <p>Apakah Anda yakin ingin menghapus aspirasi dari <strong>{{ $namaPengirim }}</strong>?</p>
                <p class="text-danger"><small>Data yang dihapus tidak dapat dikembalikan!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.pengaduan.destroy', $a->id_aspirasi) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection