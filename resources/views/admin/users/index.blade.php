@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="userTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="admin-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-admin" type="button" role="tab">
                            <i class="ph ph-shield-check"></i> Admin
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="guru-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-guru" type="button" role="tab">
                            <i class="ph ph-chalkboard-teacher"></i> Guru
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="siswa-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-siswa" type="button" role="tab">
                            <i class="ph ph-users"></i> Siswa
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="petugas-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-petugas" type="button" role="tab">
                            <i class="ph ph-user-switch"></i> Petugas
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">

                    <!-- ==================== TAB ADMIN ==================== -->
                    <div class="tab-pane fade show active" id="tab-admin" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="ph ph-shield-check"></i> Data Admin</h6>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                                <i class="ph ph-plus"></i> Tambah Admin
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Tanggal Daftar</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admins as $index => $admin)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td><span class="badge bg-primary">Admin</span></td>
                                        <td>{{ $admin->created_at ? $admin->created_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editAdminModal{{ $admin->id }}">
                                                <i class="ph ph-pencil"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteAdminModal{{ $admin->id }}">
                                                <i class="ph ph-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data admin</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ==================== TAB GURU ==================== -->
                    <div class="tab-pane fade" id="tab-guru" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="ph ph-chalkboard-teacher"></i> Data Guru</h6>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createGuruModal">
                                <i class="ph ph-plus"></i> Tambah Guru
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Foto</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Jabatan</th>
                                        <th>Kelas</th>
                                        <th>Email</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($gurus as $index => $guru)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @php
                                            $fotoUrl = null;
                                            if($guru->foto && file_exists(public_path('storage/' . $guru->foto))) {
                                            $fotoUrl = asset('storage/' . $guru->foto);
                                            } else {
                                            $fotoUrl = asset('assets/images/user/avatar-1.jpg');
                                            }
                                            @endphp
                                            <img src="{{ $fotoUrl }}" alt="Foto Guru" width="40" height="40" class="rounded-circle" style="object-fit: cover;">
                                        </td>
                                        <td>{{ $guru->nip ?? '-' }}</td>
                                        <td>{{ $guru->nama }}</td>
                                        <td>{{ $guru->mata_pelajaran ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $guru->jabatan_badge ?? 'secondary' }}">
                                                {{ $guru->jabatan ?? 'Guru' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($guru->jabatan == 'Wali Kelas' && $guru->kelas)
                                            <span class="badge bg-info">{{ $guru->kelas->nama_kelas }}</span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $guru->user->email ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewGuruModal{{ $guru->id }}">
                                                <i class="ph ph-eye"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editGuruModal{{ $guru->id }}">
                                                <i class="ph ph-pencil"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteGuruModal{{ $guru->id }}">
                                                <i class="ph ph-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada data guru</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ==================== TAB SISWA ==================== -->
                    <div class="tab-pane fade" id="tab-siswa" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h6 class="mb-0"><i class="ph ph-users"></i> Data Siswa</h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#createSiswaModal">
                                    <i class="ph ph-plus"></i> Tambah Siswa
                                </button>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importSiswaModal">
                                    <i class="ph ph-file-xls"></i> Import Excel
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="8%">Foto</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Jurusan</th>
                                        <th>Email</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siswas as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($siswa->foto)
                                            <img src="{{ asset('storage/' . $siswa->foto) }}" width="40" height="40" class="rounded-circle" style="object-fit: cover;">
                                            @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px; color: white;">
                                                {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                            </div>
                                            @endif
                                        </td>
                                        <td>{{ $siswa->nis ?? '-' }}</td>
                                        <td>{{ $siswa->nama }}</td>
                                        <td>{{ $siswa->kelasRelasi->nama_kelas ?? $siswa->kelas ?? '-' }}</td>
                                        <td>{{ $siswa->jurusanRelasi->nama_jurusan ?? $siswa->jurusan ?? '-' }}</td>
                                        <td>{{ $siswa->user->email ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewSiswaModal{{ $siswa->id }}">
                                                    <i class="ph ph-eye"></i>
                                                </button>
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSiswaModal{{ $siswa->id }}">
                                                    <i class="ph ph-pencil"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSiswaModal{{ $siswa->id }}">
                                                    <i class="ph ph-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada data siswa</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ==================== TAB PETUGAS ==================== -->
                    <div class="tab-pane fade" id="tab-petugas" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="ph ph-user-switch"></i> Data Petugas</h6>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#createPetugasModal">
                                <i class="ph ph-plus"></i> Tambah Petugas
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Foto</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($petugas as $index => $p)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><img src="{{ $p->foto_url }}" width="40" height="40" class="rounded-circle"></td>
                                        <td>{{ $p->nip ?? '-' }}</td>
                                        <td>{{ $p->nama }}</td>
                                        <td>{{ $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $p->user->email ?? '-' }}</td>
                                        <td><span class="badge bg-{{ $p->status_badge }}">{{ $p->status }}</span></td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewPetugasModal{{ $p->id }}"><i class="ph ph-eye"></i></button>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPetugasModal{{ $p->id }}"><i class="ph ph-pencil"></i></button>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deletePetugasModal{{ $p->id }}"><i class="ph ph-trash"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada data petugas</td>
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

<!-- ==================== MODAL CREATE ADMIN ==================== -->
<div class="modal fade" id="createAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Admin</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.admin.store') }}" method="POST">@csrf
                <div class="modal-body">
                    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL CREATE GURU ==================== -->
<div class="modal fade" id="createGuruModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="ph ph-plus-circle"></i> Tambah Guru Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>NIP</label><input type="text" name="nip" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Nama <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Mata Pelajaran</label><input type="text" name="mata_pelajaran" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Jabatan <span class="text-danger">*</span></label>
                            <select name="jabatan" class="form-select" id="jabatanSelectCreate" required>
                                <option value="Guru">Guru</option>
                                <option value="Kepala Sekolah">Kepala Sekolah</option>
                                <option value="Wakil Kepala Sekolah">Wakil Kepala Sekolah</option>
                                <option value="Wali Kelas">Wali Kelas</option>
                                <option value="Kepala Jurusan">Kepala Jurusan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="kelasFieldCreate" style="display: none;">
                            <label class="form-label">Kelas yang Diampu <span class="text-danger">*</span></label>
                            <select name="id_kelas" class="form-select" id="kelasSelectCreate">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasTersedia as $kelas)
                                <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Kelas yang menjadi tanggung jawab sebagai Wali Kelas</small>
                            <div id="kelasWarningCreate" class="text-warning small mt-1" style="display: none;">
                                <i class="ph ph-warning"></i> Kelas ini sudah memiliki Wali Kelas!
                            </div>
                        </div>
                        <div class="col-md-6 mb-3"><label>Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>No HP</label><input type="text" name="no_hp" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Email <span class="text-danger">*</span></label><input type="email" name="email" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Password <span class="text-danger">*</span></label><input type="password" name="password" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
                        <div class="col-12 mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL CREATE SISWA ==================== -->
<div class="modal fade" id="createSiswaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="ph ph-plus-circle"></i> Tambah Siswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.siswa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>NIS <span class="text-danger">*</span></label><input type="text" name="nis" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Nama <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Kelas <span class="text-danger">*</span></label>
                            <select name="id_kelas" class="form-select" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($allKelas as $kelas)
                                <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3"><label>Jurusan <span class="text-danger">*</span></label>
                            <select name="id_jurusan" class="form-select" required>
                                <option value="">Pilih Jurusan</option>
                                @foreach($allJurusan as $jurusan)
                                <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->kode_jurusan }} - {{ $jurusan->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3"><label>Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>No HP</label><input type="text" name="no_hp" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Email <span class="text-danger">*</span></label><input type="email" name="email" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Password <span class="text-danger">*</span></label><input type="password" name="password" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
                        <div class="col-12 mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL IMPORT SISWA ==================== -->
<div class="modal fade" id="importSiswaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="ph ph-file-xls"></i> Import Data Siswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">File Excel</label><input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls,.csv" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL CREATE PETUGAS ==================== -->
<div class="modal fade" id="createPetugasModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Tambah Petugas</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.petugas.store') }}" method="POST" enctype="multipart/form-data">@csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>NIP</label><input type="text" name="nip" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Jenis Kelamin</label><select name="jenis_kelamin" class="form-select" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select></div>
                        <div class="col-md-6 mb-3"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>No HP</label><input type="text" name="no_hp" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Status</label><select name="status" class="form-select" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select></div>
                        <div class="col-md-6 mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
                        <div class="col-12 mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning">Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL EDIT, DELETE UNTUK ADMIN ==================== -->
@foreach($admins as $admin)
<div class="modal fade" id="editAdminModal{{ $admin->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Admin</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.admin.update', $admin->id) }}" method="POST">@csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $admin->email }}" required></div>
                    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning">Update</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteAdminModal{{ $admin->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus Admin</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Yakin hapus admin <strong>{{ $admin->email }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.admin.destroy', $admin->id) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Hapus</button></form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- ==================== MODAL VIEW, EDIT, DELETE UNTUK GURU ==================== -->
@foreach($gurus as $guru)
<div class="modal fade" id="viewGuruModal{{ $guru->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detail Guru</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ $guru->foto ? asset('storage/' . $guru->foto) : asset('assets/images/user/avatar-1.jpg') }}" width="100" height="100" class="rounded-circle">
                    </div>
                    <div class="col-md-9">
                        <p><strong>NIP:</strong> {{ $guru->nip ?? '-' }}</p>
                        <p><strong>Nama:</strong> {{ $guru->nama }}</p>
                        <p><strong>Mata Pelajaran:</strong> {{ $guru->mata_pelajaran ?? '-' }}</p>
                        <p><strong>Jabatan:</strong> {{ $guru->jabatan ?? 'Guru' }}</p>
                        @if($guru->jabatan == 'Wali Kelas')
                        <p><strong>Kelas Diampu:</strong> {{ $guru->kelas->nama_kelas ?? '-' }}</p>
                        @endif
                        <p><strong>Email:</strong> {{ $guru->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>

<!-- ==================== MODAL EDIT GURU ==================== -->
@foreach($gurus as $guru)
<div class="modal fade" id="editGuruModal{{ $guru->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Guru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>NIP</label><input type="text" name="nip" class="form-control" value="{{ $guru->nip }}"></div>
                        <div class="col-md-6 mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" value="{{ $guru->nama }}" required></div>
                        <div class="col-md-6 mb-3"><label>Mata Pelajaran</label><input type="text" name="mata_pelajaran" class="form-control" value="{{ $guru->mata_pelajaran }}"></div>
                        <div class="col-md-6 mb-3"><label>Jabatan</label>
                            <select name="jabatan" class="form-select" id="jabatanSelectEdit{{ $guru->id }}" required>
                                <option value="Guru" {{ $guru->jabatan == 'Guru' ? 'selected' : '' }}>Guru</option>
                                <option value="Kepala Sekolah" {{ $guru->jabatan == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="Wakil Kepala Sekolah" {{ $guru->jabatan == 'Wakil Kepala Sekolah' ? 'selected' : '' }}>Wakil Kepala Sekolah</option>
                                <option value="Wali Kelas" {{ $guru->jabatan == 'Wali Kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                <option value="Kepala Jurusan" {{ $guru->jabatan == 'Kepala Jurusan' ? 'selected' : '' }}>Kepala Jurusan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="kelasFieldEdit{{ $guru->id }}" style="display: {{ $guru->jabatan == 'Wali Kelas' ? 'block' : 'none' }};">
                            <label class="form-label">Kelas yang Diampu</label>
                            <select name="id_kelas" class="form-select" id="kelasSelectEdit{{ $guru->id }}">
                                <option value="">Pilih Kelas</option>
                                @php
                                    // Kelas yang tersedia (belum dipakai wali kelas lain) + kelas milik sendiri
                                    $kelasTerpakaiLain = \App\Models\Guru::where('jabatan', 'Wali Kelas')
                                        ->where('id', '!=', $guru->id)
                                        ->whereNotNull('id_kelas')
                                        ->pluck('id_kelas')
                                        ->toArray();
                                @endphp
                                @foreach($allKelas as $kelas)
                                    @if(!in_array($kelas->id_kelas, $kelasTerpakaiLain) || $guru->id_kelas == $kelas->id_kelas)
                                    <option value="{{ $kelas->id_kelas }}" {{ $guru->id_kelas == $kelas->id_kelas ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="text-muted">Kelas yang menjadi tanggung jawab sebagai Wali Kelas</small>
                            <div id="kelasWarningEdit{{ $guru->id }}" class="text-warning small mt-1" style="display: none;">
                                <i class="ph ph-warning"></i> Kelas ini sudah memiliki Wali Kelas lain!
                            </div>
                        </div>
                        <div class="col-md-6 mb-3"><label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="L" {{ $guru->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $guru->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="{{ $guru->tanggal_lahir ? date('Y-m-d', strtotime($guru->tanggal_lahir)) : '' }}"></div>
                        <div class="col-md-6 mb-3"><label>No HP</label><input type="text" name="no_hp" class="form-control" value="{{ $guru->no_hp }}"></div>
                        <div class="col-md-6 mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $guru->user->email }}" required></div>
                        <div class="col-md-6 mb-3"><label>Password</label><input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah"></div>
                        <div class="col-md-6 mb-3"><label>Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
                        <div class="col-12 mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ $guru->alamat }}</textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="deleteGuruModal{{ $guru->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus Guru</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Yakin hapus guru <strong>{{ $guru->nama }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Hapus</button></form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- ==================== MODAL VIEW, EDIT, DELETE UNTUK SISWA ==================== -->
@foreach($siswas as $siswa)
<div class="modal fade" id="viewSiswaModal{{ $siswa->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detail Siswa</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>NIS:</strong> {{ $siswa->nis ?? '-' }}</p>
                <p><strong>Nama:</strong> {{ $siswa->nama }}</p>
                <p><strong>Kelas:</strong> {{ $siswa->kelas ?? '-' }}</p>
                <p><strong>Jurusan:</strong> {{ $siswa->jurusan ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $siswa->user->email ?? '-' }}</p>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="editSiswaModal{{ $siswa->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Siswa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>NIS</label><input type="text" name="nis" class="form-control" value="{{ $siswa->nis }}" required></div>
                        <div class="col-md-6 mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" value="{{ $siswa->nama }}" required></div>
                        <div class="col-md-6 mb-3"><label>Kelas</label><select name="id_kelas" class="form-select" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($allKelas as $kelas)
                                <option value="{{ $kelas->id_kelas }}" {{ $siswa->id_kelas == $kelas->id_kelas ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select></div>
                        <div class="col-md-6 mb-3"><label>Jurusan</label><select name="id_jurusan" class="form-select" required>
                                <option value="">Pilih Jurusan</option>
                                @foreach($allJurusan as $jurusan)
                                <option value="{{ $jurusan->id_jurusan }}" {{ $siswa->id_jurusan == $jurusan->id_jurusan ? 'selected' : '' }}>{{ $jurusan->kode_jurusan }} - {{ $jurusan->nama_jurusan }}</option>
                                @endforeach
                            </select></div>
                        <div class="col-md-6 mb-3"><label>Jenis Kelamin</label><select name="jenis_kelamin" class="form-select" required>
                                <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select></div>
                        <div class="col-md-6 mb-3"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="{{ $siswa->tanggal_lahir ? date('Y-m-d', strtotime($siswa->tanggal_lahir)) : '' }}"></div>
                        <div class="col-md-6 mb-3"><label>No HP</label><input type="text" name="no_hp" class="form-control" value="{{ $siswa->no_hp }}"></div>
                        <div class="col-md-6 mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $siswa->user->email }}" required></div>
                        <div class="col-md-6 mb-3"><label>Password</label><input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah"></div>
                        <div class="col-md-6 mb-3"><label>Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
                        <div class="col-12 mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ $siswa->alamat }}</textarea></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning">Update</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSiswaModal{{ $siswa->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus Siswa</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Yakin hapus siswa <strong>{{ $siswa->nama }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Hapus</button></form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- ==================== MODAL VIEW, EDIT, DELETE UNTUK PETUGAS ==================== -->
@foreach($petugas as $p)
<div class="modal fade" id="viewPetugasModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detail Petugas</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>NIP:</strong> {{ $p->nip ?? '-' }}</p>
                <p><strong>Nama:</strong> {{ $p->nama }}</p>
                <p><strong>Email:</strong> {{ $p->user->email ?? '-' }}</p>
                <p><strong>Status:</strong> <span class="badge bg-{{ $p->status_badge }}">{{ $p->status }}</span></p>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="editPetugasModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Petugas</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.petugas.update', $p->id) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>NIP</label><input type="text" name="nip" class="form-control" value="{{ $p->nip }}"></div>
                        <div class="col-md-6 mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" value="{{ $p->nama }}" required></div>
                        <div class="col-md-6 mb-3"><label>Jenis Kelamin</label><select name="jenis_kelamin" class="form-select" required>
                                <option value="L" {{ $p->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $p->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select></div>
                        <div class="col-md-6 mb-3"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="{{ $p->tanggal_lahir ? date('Y-m-d', strtotime($p->tanggal_lahir)) : '' }}"></div>
                        <div class="col-md-6 mb-3"><label>No HP</label><input type="text" name="no_hp" class="form-control" value="{{ $p->no_hp }}"></div>
                        <div class="col-md-6 mb-3"><label>Status</label><select name="status" class="form-select" required>
                                <option value="Aktif" {{ $p->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ $p->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select></div>
                        <div class="col-md-6 mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $p->user->email }}" required></div>
                        <div class="col-md-6 mb-3"><label>Password</label><input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah"></div>
                        <div class="col-md-6 mb-3"><label>Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
                        <div class="col-12 mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ $p->alamat }}</textarea></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning">Update</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePetugasModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus Petugas</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Yakin hapus petugas <strong>{{ $p->nama }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.petugas.destroy', $p->id) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Hapus</button></form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- JavaScript untuk menampilkan field kelas hanya jika jabatan = Wali Kelas -->
<script>
    // Untuk Create Modal
    const jabatanSelectCreate = document.getElementById('jabatanSelectCreate');
    const kelasFieldCreate = document.getElementById('kelasFieldCreate');

    if (jabatanSelectCreate) {
        jabatanSelectCreate.addEventListener('change', function() {
            if (this.value === 'Wali Kelas') {
                kelasFieldCreate.style.display = 'block';
                // Set required attribute pada select kelas
                document.querySelector('#kelasFieldCreate select').required = true;
            } else {
                kelasFieldCreate.style.display = 'none';
                document.querySelector('#kelasFieldCreate select').required = false;
            }
        });
        // Trigger on load
        if (jabatanSelectCreate.value === 'Wali Kelas') {
            kelasFieldCreate.style.display = 'block';
            document.querySelector('#kelasFieldCreate select').required = true;
        }
    }

    // Untuk setiap Edit Modal
    @foreach($gurus as $guru)
    (function() {
        const jabatanSelect = document.getElementById('jabatanSelectEdit{{ $guru->id }}');
        const kelasField = document.getElementById('kelasFieldEdit{{ $guru->id }}');
        
        if (jabatanSelect) {
            jabatanSelect.addEventListener('change', function() {
                if (this.value === 'Wali Kelas') {
                    kelasField.style.display = 'block';
                    // Set required attribute pada select kelas
                    const kelasSelect = kelasField.querySelector('select');
                    if (kelasSelect) kelasSelect.required = true;
                } else {
                    kelasField.style.display = 'none';
                    const kelasSelect = kelasField.querySelector('select');
                    if (kelasSelect) kelasSelect.required = false;
                }
            });
        }
    })();
    @endforeach
</script>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var hash = window.location.hash;
        if (hash) {
            var tab = $('.nav-tabs button[data-bs-target="' + hash + '"]');
            if (tab.length) tab.tab('show');
        }
        $('.nav-tabs button').on('shown.bs.tab', function(e) {
            window.location.hash = $(e.target).data('bs-target');
        });
    });
</script>
@endpush