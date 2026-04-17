    @extends('layouts.admin')

    @section('title', 'Manajemen Master Data')

    @section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="masterDataTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="kategori-tab" data-bs-toggle="tab"
                                data-bs-target="#kategori" type="button" role="tab">
                                <i class="ph ph-tag"></i> Kategori
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="jurusan-tab" data-bs-toggle="tab"
                                data-bs-target="#jurusan" type="button" role="tab">
                                <i class="ph ph-book"></i> Jurusan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="kelas-tab" data-bs-toggle="tab"
                                data-bs-target="#kelas" type="button" role="tab">
                                <i class="ph ph-users"></i> Kelas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ruangan-tab" data-bs-toggle="tab"
                                data-bs-target="#ruangan" type="button" role="tab">
                                <i class="ph ph-building"></i> Ruangan
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="masterDataTabContent">

                        <!-- ==================== TAB KATEGORI ==================== -->
                        <div class="tab-pane fade show active" id="kategori" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="ph ph-tag"></i> Daftar Kategori</h6>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addKategoriModal">
                                    <i class="ph ph-plus-circle"></i> Tambah Kategori
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Nama Kategori</th>
                                            <th>Deskripsi</th>
                                            <th width="15%">Total Aspirasi</th>
                                            <th width="25%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kategoris as $index => $kategori)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $kategori->nama_kategori }}</td>
                                            <td>{{ Str::limit($kategori->deskripsi, 50) ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $kategori->aspirasi_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewKategoriModal{{ $kategori->id_kategori }}">
                                                    <i class="ph ph-eye"></i> Detail
                                                </button>
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editKategoriModal{{ $kategori->id_kategori }}">
                                                    <i class="ph ph-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteKategoriModal{{ $kategori->id_kategori }}">
                                                    <i class="ph ph-trash"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada数据</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- ==================== TAB JURUSAN ==================== -->
                        <div class="tab-pane fade" id="jurusan" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="ph ph-book"></i> Daftar Jurusan</h6>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJurusanModal">
                                    <i class="ph ph-plus-circle"></i> Tambah Jurusan
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Kode</th>
                                            <th>Nama Jurusan</th>
                                            <th>Deskripsi</th>
                                            <th width="15%">Total Kelas</th>
                                            <th width="25%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jurusans as $index => $jurusan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><span class="badge bg-secondary">{{ $jurusan->kode_jurusan }}</span></td>
                                            <td>{{ $jurusan->nama_jurusan }}</td>
                                            <td>{{ Str::limit($jurusan->deskripsi, 40) ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $jurusan->kelas_count ?? 0 }} Kelas</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewJurusanModal{{ $jurusan->id_jurusan }}">
                                                    <i class="ph ph-eye"></i> Detail
                                                </button>
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editJurusanModal{{ $jurusan->id_jurusan }}">
                                                    <i class="ph ph-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteJurusanModal{{ $jurusan->id_jurusan }}">
                                                    <i class="ph ph-trash"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- ==================== TAB KELAS ==================== -->
                        <div class="tab-pane fade" id="kelas" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="ph ph-users"></i> Daftar Kelas</h6>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addKelasModal">
                                    <i class="ph ph-plus-circle"></i> Tambah Kelas
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Nama Kelas</th>
                                            <th>Tingkat</th>
                                            <th>Jurusan</th>
                                            <th>Kapasitas</th>
                                            <th>Jml Siswa</th>
                                            <th width="25%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kelas as $index => $k)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $k->nama_kelas }}</td>
                                            <td><span class="badge bg-primary">{{ $k->tingkat }}</span></td>
                                            <td>{{ $k->jurusan->kode_jurusan ?? '-' }}</td>
                                            <td>{{ $k->kapasitas }} org</td>
                                            <td>
                                                <span class="badge bg-{{ ($k->siswa_count ?? 0) > ($k->kapasitas ?? 0) ? 'danger' : 'success' }}">
                                                    {{ $k->siswa_count ?? 0 }}/{{ $k->kapasitas ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewKelasModal{{ $k->id_kelas }}">
                                                    <i class="ph ph-eye"></i> Detail
                                                </button>
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editKelasModal{{ $k->id_kelas }}">
                                                    <i class="ph ph-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteKelasModal{{ $k->id_kelas }}">
                                                    <i class="ph ph-trash"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- ==================== TAB RUANGAN ==================== -->
                        <div class="tab-pane fade" id="ruangan" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="ph ph-building"></i> Daftar Ruangan</h6>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRuanganModal">
                                    <i class="ph ph-plus-circle"></i> Tambah Ruangan
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Kode</th>
                                            <th>Nama Ruangan</th>
                                            <th>Jenis</th>
                                            <th>Lokasi</th>
                                            <th>Kondisi</th>
                                            <th width="25%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ruangans as $index => $ruangan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><span class="badge bg-secondary">{{ $ruangan->kode_ruangan }}</span></td>
                                            <td>{{ $ruangan->nama_ruangan }}</td>
                                            <td>{{ $ruangan->jenis_ruangan }}</td>
                                            <td>{{ $ruangan->lokasi ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $ruangan->status_badge ?? 'secondary' }}">
                                                    {{ $ruangan->kondisi }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewRuanganModal{{ $ruangan->id_ruangan }}">
                                                    <i class="ph ph-eye"></i> Detail
                                                </button>
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editRuanganModal{{ $ruangan->id_ruangan }}">
                                                    <i class="ph ph-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteRuanganModal{{ $ruangan->id_ruangan }}">
                                                    <i class="ph ph-trash"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL VIEW RUANGAN - FIXED -->
    @foreach($ruangans as $ruangan)
    <div class="modal fade" id="viewRuanganModal{{ $ruangan->id_ruangan }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-eye"></i> Detail Ruangan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Kode Ruangan</th>
                                <td width="5%">:</td>
                                <td><span class="badge bg-secondary">{{ $ruangan->kode_ruangan }}</span></td>
                            </tr>
                            <tr>
                                <th>Nama Ruangan</th>
                                <td>:</td>
                                <td><strong>{{ $ruangan->nama_ruangan }}</strong></td>
                            </tr>
                            <tr>
                                <th>Jenis Ruangan</th>
                                <td>:</td>
                                <td>{{ $ruangan->jenis_ruangan }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td>:</td>
                                <td>{{ $ruangan->lokasi ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kapasitas</th>
                                <td>:</td>
                                <td>{{ $ruangan->kapasitas ?: '-' }} orang</td>
                            </tr>
                            <tr>
                                <th>Kondisi</th>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-{{ $ruangan->status_badge ?? 'secondary' }}">
                                        {{ $ruangan->kondisi }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>:</td>
                                <td>{{ $ruangan->deskripsi ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>:</td>
                                <td>
                                    @php
                                    $createdAt = $ruangan->created_at;
                                    if ($createdAt && $createdAt != '0000-00-00 00:00:00') {
                                    if ($createdAt instanceof \Carbon\Carbon) {
                                    echo $createdAt->format('d/m/Y H:i');
                                    } else {
                                    echo date('d/m/Y H:i', strtotime($createdAt));
                                    }
                                    } else {
                                    echo '-';
                                    }
                                    @endphp
                                </td>
                            </tr>
                            <tr>
                                <th>Terakhir Diupdate</th>
                                <td>:</td>
                                <td>
                                    @php
                                    $updatedAt = $ruangan->updated_at;
                                    if ($updatedAt && $updatedAt != '0000-00-00 00:00:00') {
                                    if ($updatedAt instanceof \Carbon\Carbon) {
                                    echo $updatedAt->format('d/m/Y H:i');
                                    } else {
                                    echo date('d/m/Y H:i', strtotime($updatedAt));
                                    }
                                    } else {
                                    echo '-';
                                    }
                                    @endphp
                                </td>
                            </tr>
                        </table>
                    </div>
                    @if(($ruangan->aspirasi_count ?? 0) > 0)
                    <div class="alert alert-info mt-2">
                        <i class="ph ph-info"></i>
                        <strong>Informasi:</strong> Ruangan ini telah digunakan dalam {{ $ruangan->aspirasi_count }} aspirasi.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- ==================== MODAL VIEW JURUSAN ==================== -->
    @foreach($jurusans as $jurusan)
    <div class="modal fade" id="viewJurusanModal{{ $jurusan->id_jurusan }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-eye"></i> Detail Jurusan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Kode Jurusan</th>
                                <td width="5%">:</td>
                                <td><span class="badge bg-secondary">{{ $jurusan->kode_jurusan }}</span></td>
                            </tr>
                            <tr>
                                <th>Nama Jurusan</th>
                                <td>:</td>
                                <td><strong>{{ $jurusan->nama_jurusan }}</strong></td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>:</td>
                                <td>{{ $jurusan->deskripsi ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Total Kelas</th>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-info">{{ $jurusan->kelas_count ?? 0 }} Kelas</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>:</td>
                                <td>{{ $jurusan->created_at ? $jurusan->created_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Terakhir Diupdate</th>
                                <td>:</td>
                                <td>{{ $jurusan->updated_at ? $jurusan->updated_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    @if(($jurusan->kelas_count ?? 0) > 0)
                    <div class="alert alert-info mt-2">
                        <i class="ph ph-info"></i>
                        <strong>Informasi:</strong> Jurusan ini memiliki {{ $jurusan->kelas_count }} kelas.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- ==================== MODAL VIEW KELAS ==================== -->
    @foreach($kelas as $k)
    <div class="modal fade" id="viewKelasModal{{ $k->id_kelas }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-eye"></i> Detail Kelas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Nama Kelas</th>
                                <td width="5%">:</td>
                                <td><strong>{{ $k->nama_kelas }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tingkat</th>
                                <td>:</td>
                                <td><span class="badge bg-primary">{{ $k->tingkat }}</span></td>
                            </tr>
                            <tr>
                                <th>Jurusan</th>
                                <td>:</td>
                                <td>{{ $k->jurusan->nama_jurusan ?? '-' }} ({{ $k->jurusan->kode_jurusan ?? '-' }})</td>
                            </tr>
                            <tr>
                                <th>Kapasitas</th>
                                <td>:</td>
                                <td>{{ $k->kapasitas }} orang</td>
                            </tr>
                            <tr>
                                <th>Jumlah Siswa</th>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-{{ ($k->siswa_count ?? 0) > ($k->kapasitas ?? 0) ? 'danger' : 'success' }}">
                                        {{ $k->siswa_count ?? 0 }}/{{ $k->kapasitas ?? 0 }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>:</td>
                                <td>{{ $k->deskripsi ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>:</td>
                                <td>{{ $k->created_at ? $k->created_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Terakhir Diupdate</th>
                                <td>:</td>
                                <td>{{ $k->updated_at ? $k->updated_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    @if(($k->siswa_count ?? 0) > 0)
                    <div class="alert alert-info mt-2">
                        <i class="ph ph-info"></i>
                        <strong>Informasi:</strong> Kelas ini memiliki {{ $k->siswa_count }} siswa.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- ==================== MODAL VIEW RUANGAN ==================== -->
    @foreach($ruangans as $ruangan)
    <div class="modal fade" id="viewRuanganModal{{ $ruangan->id_ruangan }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-eye"></i> Detail Ruangan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Kode Ruangan</th>
                                <td width="5%">:</td>
                                <td><span class="badge bg-secondary">{{ $ruangan->kode_ruangan }}</span></td>
                            </tr>
                            <tr>
                                <th>Nama Ruangan</th>
                                <td>:</td>
                                <td><strong>{{ $ruangan->nama_ruangan }}</strong></td>
                            </tr>
                            <tr>
                                <th>Jenis Ruangan</th>
                                <td>:</td>
                                <td>{{ $ruangan->jenis_ruangan }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td>:</td>
                                <td>{{ $ruangan->lokasi ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kapasitas</th>
                                <td>:</td>
                                <td>{{ $ruangan->kapasitas ?: '-' }} orang</td>
                            </tr>
                            <tr>
                                <th>Kondisi</th>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-{{ $ruangan->status_badge ?? 'secondary' }}">
                                        {{ $ruangan->kondisi }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>:</td>
                                <td>{{ $ruangan->deskripsi ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>:</td>
                                <td>
                                    @php
                                    $createdAt = $ruangan->created_at;
                                    if ($createdAt) {
                                    if ($createdAt instanceof \Carbon\Carbon) {
                                    echo $createdAt->format('d/m/Y H:i');
                                    } else {
                                    echo \Carbon\Carbon::parse($createdAt)->format('d/m/Y H:i');
                                    }
                                    } else {
                                    echo '-';
                                    }
                                    @endphp
                                </td>
                            </tr>
                            <tr>
                                <th>Terakhir Diupdate</th>
                                <td>:</td>
                                <td>
                                    @php
                                    $updatedAt = $ruangan->updated_at;
                                    if ($updatedAt) {
                                    if ($updatedAt instanceof \Carbon\Carbon) {
                                    echo $updatedAt->format('d/m/Y H:i');
                                    } else {
                                    echo \Carbon\Carbon::parse($updatedAt)->format('d/m/Y H:i');
                                    }
                                    } else {
                                    echo '-';
                                    }
                                    @endphp
                                </td>
                            </tr>
                        </table>
                    </div>
                    @if(($ruangan->aspirasi_count ?? 0) > 0)
                    <div class="alert alert-info mt-2">
                        <i class="ph ph-info"></i>
                        <strong>Informasi:</strong> Ruangan ini telah digunakan dalam {{ $ruangan->aspirasi_count }} aspirasi.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- ==================== MODAL TAMBAH KATEGORI ==================== -->
    <div class="modal fade" id="addKategoriModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-plus-circle"></i> Tambah Kategori Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" class="form-control"
                                placeholder="Contoh: Meja, Kursi, Papan Tulis" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"
                                placeholder="Jelaskan tentang kategori ini..."></textarea>
                            <small class="text-muted">Deskripsi akan membantu siswa memahami kategori ini</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-floppy-disk"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL EDIT & DELETE KATEGORI ==================== -->
    @foreach($kategoris as $kategori)
    <div class="modal fade" id="editKategoriModal{{ $kategori->id_kategori }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="ph ph-pencil"></i> Edit Kategori
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.kategori.update', $kategori->id_kategori) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control"
                                value="{{ $kategori->nama_kategori }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ $kategori->deskripsi }}</textarea>
                        </div>
                        @if(($kategori->aspirasi_count ?? 0) > 0)
                        <div class="alert alert-warning">
                            <i class="ph ph-warning"></i>
                            <strong>Perhatian:</strong> Kategori ini sudah digunakan dalam
                            {{ $kategori->aspirasi_count }} aspirasi.
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="ph ph-floppy-disk"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteKategoriModal{{ $kategori->id_kategori }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-trash"></i> Hapus Kategori
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="ph ph-warning-circle" style="font-size: 48px; color: #dc2626;"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin menghapus kategori <strong>"{{ $kategori->nama_kategori }}"</strong>?</p>
                    @if(($kategori->aspirasi_count ?? 0) > 0)
                    <div class="alert alert-danger">
                        <i class="ph ph-warning"></i>
                        <strong>Peringatan!</strong><br>
                        Kategori ini memiliki <strong>{{ $kategori->aspirasi_count }} aspirasi</strong>.
                        Menghapus kategori akan mengosongkan kategori pada aspirasi tersebut.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.kategori.destroy', $kategori->id_kategori) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- ==================== MODAL VIEW KATEGORI ==================== -->
    @foreach($kategoris as $kategori)
    <div class="modal fade" id="viewKategoriModal{{ $kategori->id_kategori }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-eye"></i> Detail Kategori
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Nama Kategori</th>
                                <td width="5%">:</td>
                                <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>:</td>
                                <td>{{ $kategori->deskripsi ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Total Aspirasi</th>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-info">{{ $kategori->aspirasi_count ?? 0 }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>:</td>
                                <td>{{ $kategori->created_at ? $kategori->created_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Terakhir Diupdate</th>
                                <td>:</td>
                                <td>{{ $kategori->updated_at ? $kategori->updated_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    @if(($kategori->aspirasi_count ?? 0) > 0)
                    <div class="alert alert-info mt-2">
                        <i class="ph ph-info"></i>
                        <strong>Informasi:</strong> Kategori ini telah digunakan dalam {{ $kategori->aspirasi_count }} aspirasi.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- ==================== MODAL TAMBAH JURUSAN ==================== -->
    <div class="modal fade" id="addJurusanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-plus-circle"></i> Tambah Jurusan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.jurusan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Jurusan <span class="text-danger">*</span></label>
                            <input type="text" name="kode_jurusan" class="form-control"
                                placeholder="Contoh: RPL, TKJ, MM" required>
                            <small class="text-muted">Kode jurusan harus unik (max 20 karakter)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Jurusan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_jurusan" class="form-control"
                                placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"
                                placeholder="Deskripsi jurusan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-floppy-disk"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL EDIT & DELETE JURUSAN ==================== -->
    @foreach($jurusans as $jurusan)
    <div class="modal fade" id="editJurusanModal{{ $jurusan->id_jurusan }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="ph ph-pencil"></i> Edit Jurusan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.jurusan.update', $jurusan->id_jurusan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Jurusan</label>
                            <input type="text" name="kode_jurusan" class="form-control"
                                value="{{ $jurusan->kode_jurusan }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Jurusan</label>
                            <input type="text" name="nama_jurusan" class="form-control"
                                value="{{ $jurusan->nama_jurusan }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ $jurusan->deskripsi }}</textarea>
                        </div>
                        @if(($jurusan->kelas_count ?? 0) > 0)
                        <div class="alert alert-warning">
                            <i class="ph ph-warning"></i>
                            <strong>Perhatian:</strong> Jurusan ini memiliki {{ $jurusan->kelas_count }} kelas.
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="ph ph-floppy-disk"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteJurusanModal{{ $jurusan->id_jurusan }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-trash"></i> Hapus Jurusan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="ph ph-warning-circle" style="font-size: 48px; color: #dc2626;"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin menghapus jurusan <strong>"{{ $jurusan->nama_jurusan }}"</strong>?</p>
                    @if(($jurusan->kelas_count ?? 0) > 0)
                    <div class="alert alert-danger">
                        <i class="ph ph-warning"></i>
                        <strong>Peringatan!</strong><br>
                        Jurusan ini memiliki <strong>{{ $jurusan->kelas_count }} kelas</strong>.
                        Menghapus jurusan akan menghapus semua kelas yang terkait.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.jurusan.destroy', $jurusan->id_jurusan) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- ==================== MODAL TAMBAH KELAS ==================== -->
    <div class="modal fade" id="addKelasModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-plus-circle"></i> Tambah Kelas Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.kelas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kelas" class="form-control"
                                placeholder="Contoh: 10 RPL, 11 TKJ" required>
                            <small class="text-muted">Contoh: 10 RPL, 11 TKJ, 12 MM</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                            <select name="tingkat" class="form-select" required>
                                <option value="">Pilih Tingkat</option>
                                <option value="10">10 (Kelas 10)</option>
                                <option value="11">11 (Kelas 11)</option>
                                <option value="12">12 (Kelas 12)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <select name="id_jurusan" class="form-select" required>
                                <option value="">Pilih Jurusan</option>
                                @foreach($allJurusans as $j)
                                <option value="{{ $j->id_jurusan }}">{{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" name="kapasitas" class="form-control" value="30" min="1">
                            <small class="text-muted">Jumlah maksimal siswa dalam kelas</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"
                                placeholder="Informasi tambahan tentang kelas ini"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-floppy-disk"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL EDIT & DELETE KELAS ==================== -->
    @foreach($kelas as $k)
    <div class="modal fade" id="editKelasModal{{ $k->id_kelas }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="ph ph-pencil"></i> Edit Kelas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.kelas.update', $k->id_kelas) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control"
                                value="{{ $k->nama_kelas }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tingkat</label>
                            <select name="tingkat" class="form-select" required>
                                <option value="10" {{ $k->tingkat == '10' ? 'selected' : '' }}>10</option>
                                <option value="11" {{ $k->tingkat == '11' ? 'selected' : '' }}>11</option>
                                <option value="12" {{ $k->tingkat == '12' ? 'selected' : '' }}>12</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <select name="id_jurusan" class="form-select" required>
                                @foreach($allJurusans as $j)
                                <option value="{{ $j->id_jurusan }}" {{ $k->id_jurusan == $j->id_jurusan ? 'selected' : '' }}>
                                    {{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" name="kapasitas" class="form-control" value="{{ $k->kapasitas }}" min="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2">{{ $k->deskripsi }}</textarea>
                        </div>
                        @if(($k->siswa_count ?? 0) > 0)
                        <div class="alert alert-warning">
                            <i class="ph ph-warning"></i>
                            <strong>Perhatian:</strong> Kelas ini memiliki {{ $k->siswa_count }} siswa.
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="ph ph-floppy-disk"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteKelasModal{{ $k->id_kelas }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-trash"></i> Hapus Kelas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="ph ph-warning-circle" style="font-size: 48px; color: #dc2626;"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin menghapus kelas <strong>"{{ $k->nama_kelas }}"</strong>?</p>
                    @if(($k->siswa_count ?? 0) > 0)
                    <div class="alert alert-danger">
                        <i class="ph ph-warning"></i>
                        <strong>Peringatan!</strong><br>
                        Kelas ini memiliki <strong>{{ $k->siswa_count }} siswa</strong>.
                        Menghapus kelas akan mengosongkan data kelas pada siswa tersebut.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.kelas.destroy', $k->id_kelas) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- ==================== MODAL TAMBAH RUANGAN ==================== -->
    <div class="modal fade" id="addRuanganModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-plus-circle"></i> Tambah Ruangan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.ruangan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kode Ruangan <span class="text-danger">*</span></label>
                                    <input type="text" name="kode_ruangan" class="form-control"
                                        placeholder="Contoh: R-01, LAB-01" required>
                                    <small class="text-muted">Kode unik untuk ruangan</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_ruangan" class="form-control"
                                        placeholder="Contoh: Ruang Kelas 10 RPL" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Ruangan <span class="text-danger">*</span></label>
                                    <select name="jenis_ruangan" class="form-select" required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="Kelas">Kelas</option>
                                        <option value="Laboratorium">Laboratorium</option>
                                        <option value="Perpustakaan">Perpustakaan</option>
                                        <option value="Kantin">Kantin</option>
                                        <option value="Kamar Mandi">Kamar Mandi</option>
                                        <option value="Lapangan">Lapangan</option>
                                        <option value="Ruang Guru">Ruang Guru</option>
                                        <option value="Ruang Kepala Sekolah">Ruang Kepala Sekolah</option>
                                        <option value="Ruang UKS">Ruang UKS</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Lokasi</label>
                                    <input type="text" name="lokasi" class="form-control"
                                        placeholder="Contoh: Lantai 1, Gedung A">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kapasitas</label>
                                    <input type="number" name="kapasitas" class="form-control"
                                        placeholder="Jumlah orang" min="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                                    <select name="kondisi" class="form-select" required>
                                        <option value="Baik">Baik</option>
                                        <option value="Rusak Ringan">Rusak Ringan</option>
                                        <option value="Rusak Berat">Rusak Berat</option>
                                        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"
                                placeholder="Informasi tambahan tentang ruangan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-floppy-disk"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL EDIT & DELETE RUANGAN ==================== -->
    @foreach($ruangans as $ruangan)
    <div class="modal fade" id="editRuanganModal{{ $ruangan->id_ruangan }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="ph ph-pencil"></i> Edit Ruangan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.ruangan.update', $ruangan->id_ruangan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kode Ruangan</label>
                                    <input type="text" name="kode_ruangan" class="form-control"
                                        value="{{ $ruangan->kode_ruangan }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Ruangan</label>
                                    <input type="text" name="nama_ruangan" class="form-control"
                                        value="{{ $ruangan->nama_ruangan }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Ruangan</label>
                                    <select name="jenis_ruangan" class="form-select" required>
                                        <option value="Kelas" {{ $ruangan->jenis_ruangan == 'Kelas' ? 'selected' : '' }}>Kelas</option>
                                        <option value="Laboratorium" {{ $ruangan->jenis_ruangan == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                                        <option value="Perpustakaan" {{ $ruangan->jenis_ruangan == 'Perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                                        <option value="Kantin" {{ $ruangan->jenis_ruangan == 'Kantin' ? 'selected' : '' }}>Kantin</option>
                                        <option value="Kamar Mandi" {{ $ruangan->jenis_ruangan == 'Kamar Mandi' ? 'selected' : '' }}>Kamar Mandi</option>
                                        <option value="Lapangan" {{ $ruangan->jenis_ruangan == 'Lapangan' ? 'selected' : '' }}>Lapangan</option>
                                        <option value="Ruang Guru" {{ $ruangan->jenis_ruangan == 'Ruang Guru' ? 'selected' : '' }}>Ruang Guru</option>
                                        <option value="Ruang Kepala Sekolah" {{ $ruangan->jenis_ruangan == 'Ruang Kepala Sekolah' ? 'selected' : '' }}>Ruang Kepala Sekolah</option>
                                        <option value="Ruang UKS" {{ $ruangan->jenis_ruangan == 'Ruang UKS' ? 'selected' : '' }}>Ruang UKS</option>
                                        <option value="Lainnya" {{ $ruangan->jenis_ruangan == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Lokasi</label>
                                    <input type="text" name="lokasi" class="form-control" value="{{ $ruangan->lokasi }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kapasitas</label>
                                    <input type="number" name="kapasitas" class="form-control" value="{{ $ruangan->kapasitas }}" min="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kondisi</label>
                                    <select name="kondisi" class="form-select" required>
                                        <option value="Baik" {{ $ruangan->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Rusak Ringan" {{ $ruangan->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="Rusak Berat" {{ $ruangan->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                        <option value="Dalam Perbaikan" {{ $ruangan->kondisi == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2">{{ $ruangan->deskripsi }}</textarea>
                        </div>
                        @if(($ruangan->aspirasi_count ?? 0) > 0)
                        <div class="alert alert-warning">
                            <i class="ph ph-warning"></i>
                            <strong>Perhatian:</strong> Ruangan ini memiliki {{ $ruangan->aspirasi_count }} aspirasi.
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="ph ph-floppy-disk"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteRuanganModal{{ $ruangan->id_ruangan }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="ph ph-trash"></i> Hapus Ruangan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="ph ph-warning-circle" style="font-size: 48px; color: #dc2626;"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin menghapus ruangan <strong>"{{ $ruangan->nama_ruangan }}"</strong>?</p>
                    @if(($ruangan->aspirasi_count ?? 0) > 0)
                    <div class="alert alert-danger">
                        <i class="ph ph-warning"></i>
                        <strong>Peringatan!</strong><br>
                        Ruangan ini memiliki <strong>{{ $ruangan->aspirasi_count }} aspirasi</strong>.
                        Menghapus ruangan akan mengosongkan data ruangan pada aspirasi tersebut.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.ruangan.destroy', $ruangan->id_ruangan) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Info Card -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="d-flex">
                        <i class="ph ph-info ph-2x text-primary me-3"></i>
                        <div>
                            <h6 class="mb-1">Informasi Master Data</h6>
                            <p class="mb-0 text-muted small">
                                <strong>Kategori:</strong> Digunakan untuk mengelompokkan jenis sarana/prasarana yang diaspirasikan.<br>
                                <strong>Jurusan & Kelas:</strong> Data ini akan digunakan saat pendaftaran siswa baru.<br>
                                <strong>Ruangan:</strong> Data ruangan akan menjadi referensi lokasi dalam aspirasi sarana sekolah.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // ====== SIMPAN TAB AKTIF PAKAI SESSION STORAGE ======

            // Cek apakah ada tab yang tersimpan
            var savedTab = sessionStorage.getItem('masterDataActiveTab');
            var urlHash = window.location.hash;

            // Prioritas: URL hash dulu, kalau tidak ada pakai sessionStorage
            var activeTab = urlHash || savedTab;

            if (activeTab) {
                var tab = $('.nav-tabs button[data-bs-target="' + activeTab + '"]');
                if (tab.length) {
                    tab.tab('show');
                }
            }

            // Simpan tab aktif setiap kali tab berganti
            $('.nav-tabs button').on('shown.bs.tab', function(e) {
                var target = $(e.target).data('bs-target');
                sessionStorage.setItem('masterDataActiveTab', target);
                window.location.hash = target;
            });
        });

        // Auto close alert after 3 seconds
        setTimeout(function() {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('alert-success') || alert.classList.contains('alert-danger')) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }
            });
        }, 3000);
    </script>
    @endpush
    @endsection