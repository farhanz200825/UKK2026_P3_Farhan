@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Data Admin</h5>
                <div class="card-header-right">
                    <a href="{{ route('register.form') }}" class="btn btn-primary btn-sm" target="_blank">
                        <i class="ph ph-plus"></i> Tambah Admin
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $index => $admin)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $admin->email }}</td>
                                <td><span class="badge bg-primary">Admin</span></td>
                                <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Data Guru</h5>
                <div class="card-header-right">
                    <a href="{{ route('admin.guru.create') }}" class="btn btn-success btn-sm">
                        <i class="ph ph-plus"></i> Tambah Guru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Mata Pelajaran</th>
                                <th>Jenis Kelamin</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gurus as $index => $guru)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $guru->guru->nip ?? '-' }}</td>
                                <td>{{ $guru->guru->nama ?? '-' }}</td>
                                <td>{{ $guru->guru->mata_pelajaran ?? '-' }}</td>
                                <td>{{ $guru->guru->jenis_kelamin ?? '-' }}</td>
                                <td>{{ $guru->email }}</td>
                                <td>
                                    <a href="{{ route('admin.guru.edit', $guru->guru->id) }}" class="btn btn-warning btn-sm">
                                        <i class="ph ph-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.guru.destroy', $guru->guru->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                            <i class="ph ph-trash"></i> Hapus
                                        </button>
                                    </form>
                                 </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Data Siswa</h5>
                <div class="card-header-right">
                    <a href="{{ route('admin.siswa.create') }}" class="btn btn-info btn-sm">
                        <i class="ph ph-plus"></i> Tambah Siswa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Jenis Kelamin</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswas as $index => $siswa)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $siswa->siswa->nis ?? '-' }}</td>
                                <td>{{ $siswa->siswa->nama ?? '-' }}</td>
                                <td>{{ $siswa->siswa->kelas ?? '-' }}</td>
                                <td>{{ $siswa->siswa->jurusan ?? '-' }}</td>
                                <td>{{ $siswa->siswa->jenis_kelamin ?? '-' }}</td>
                                <td>{{ $siswa->email }}</td>
                                <td>
                                    <a href="{{ route('admin.siswa.edit', $siswa->siswa->id) }}" class="btn btn-warning btn-sm">
                                        <i class="ph ph-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.siswa.destroy', $siswa->siswa->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                            <i class="ph ph-trash"></i> Hapus
                                        </button>
                                    </form>
                                 </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection