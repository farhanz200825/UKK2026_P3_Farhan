@extends('layouts.admin')

@section('title', 'My Account')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    @php
                    $fotoPath = null;
                    if($user->role == 'siswa' && $user->siswa && $user->siswa->foto) {
                        $fotoPath = asset('storage/' . $user->siswa->foto);
                    } elseif($user->role == 'guru' && $user->guru && $user->guru->foto) {
                        $fotoPath = asset('storage/' . $user->guru->foto);
                    } elseif($user->role == 'petugas' && $user->petugas && $user->petugas->foto) {
                        $fotoPath = asset('storage/' . $user->petugas->foto);
                    }
                    @endphp

                    @if($fotoPath)
                    <img src="{{ $fotoPath }}" alt="Profile" class="rounded-circle"
                        style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #4680ff;">
                    @else
                    <div class="mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 150px; height: 150px; font-size: 64px;">
                        {{ strtoupper(substr($user->email, 0, 1)) }}
                    </div>
                    @endif
                </div>
                
                @php
                    $displayName = '-';
                    if($user->role == 'siswa' && $user->siswa) {
                        $displayName = $user->siswa->nama;
                    } elseif($user->role == 'guru' && $user->guru) {
                        $displayName = $user->guru->nama;
                    } elseif($user->role == 'petugas' && $user->petugas) {
                        $displayName = $user->petugas->nama;
                    } else {
                        $displayName = $user->email;
                    }
                @endphp
                <h5>{{ $displayName }}</h5>
                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                <hr>
                <div class="text-start">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Terdaftar:</strong> {{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : '-' }}</p>
                    <p><strong>Terakhir Update:</strong> {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Informasi Lengkap</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    @if($user->role == 'siswa' && $user->siswa)
                    <tr>
                        <th width="30%">NIS</th>
                        <td>{{ $user->siswa->nis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $user->siswa->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $user->siswa->kelas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <td>{{ $user->siswa->jurusan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ ($user->siswa->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : (($user->siswa->jenis_kelamin ?? '') == 'P' ? 'Perempuan' : '-') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $user->siswa->tanggal_lahir ? date('d/m/Y', strtotime($user->siswa->tanggal_lahir)) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>{{ $user->siswa->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $user->siswa->alamat ?? '-' }}</td>
                    </tr>

                    @elseif($user->role == 'guru' && $user->guru)
                    <tr>
                        <th>NIP</th>
                        <td>{{ $user->guru->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $user->guru->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <td>{{ $user->guru->mata_pelajaran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>{{ $user->guru->jabatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ ($user->guru->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : (($user->guru->jenis_kelamin ?? '') == 'P' ? 'Perempuan' : '-') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $user->guru->tanggal_lahir ? date('d/m/Y', strtotime($user->guru->tanggal_lahir)) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>{{ $user->guru->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $user->guru->alamat ?? '-' }}</td>
                    </tr>

                    @elseif($user->role == 'petugas' && $user->petugas)
                    <tr>
                        <th>NIP</th>
                        <td>{{ $user->petugas->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $user->petugas->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ ($user->petugas->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : (($user->petugas->jenis_kelamin ?? '') == 'P' ? 'Perempuan' : '-') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $user->petugas->tanggal_lahir ? date('d/m/Y', strtotime($user->petugas->tanggal_lahir)) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>{{ $user->petugas->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $user->petugas->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ ($user->petugas->status ?? '') == 'Aktif' ? 'success' : 'danger' }}">
                                {{ $user->petugas->status ?? '-' }}
                            </span>
                        </td>
                    </tr>

                    @else
                    <tr>
                        <td colspan="2" class="text-center">Data profil tidak tersedia</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('profile.settings') }}" class="btn btn-warning btn-sm">
                    <i class="ph ph-pencil"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection