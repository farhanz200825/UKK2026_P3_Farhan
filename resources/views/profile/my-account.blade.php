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
                <h5>{{ $profile->nama ?? $user->email }}</h5>
                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                <hr>
                <div class="text-start">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Terdaftar:</strong> {{ $user->created_at->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Terakhir Update:</strong> {{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
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
                    @if($user->role == 'siswa')
                    <tr>
                        <th width="30%">NIS</th>
                        <td>{{ $profile->nis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $profile->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $profile->kelas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <td>{{ $profile->jurusan ?? '-' }}</td>
                    </tr>
                    @elseif($user->role == 'guru')
                    <tr>
                        <th>NIP</th>
                        <td>{{ $profile->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $profile->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <td>{{ $profile->mata_pelajaran ?? '-' }}</td>
                    </tr>
                    @endif

                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $profile->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $profile->tanggal_lahir ? date('d/m/Y', strtotime($profile->tanggal_lahir)) : '-' }}</td>
                    </tr>

                    <tr>
                        <th>No HP</th>
                        <td>{{ $profile->no_hp ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Alamat</th>
                        <td>{{ $profile->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>
                            @php
                            $tglLahir = $profile->tanggal_lahir ?? '';
                            if($tglLahir) {
                            echo date('d/m/Y', strtotime($tglLahir));
                            } else {
                            echo '-';
                            }
                            @endphp
                        </td>
                    </tr>
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