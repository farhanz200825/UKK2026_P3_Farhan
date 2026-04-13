@extends('layouts.admin')

@section('title', 'Profile Petugas')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <img src="{{ $petugas->foto_url }}" alt="Foto" width="150" height="150" class="rounded-circle img-thumbnail mb-3">
                <h5>{{ $petugas->nama }}</h5>
                <p class="text-muted">{{ $petugas->nip ?? '-' }}</p>
                <span class="badge bg-{{ $petugas->status_badge }}">{{ $petugas->status }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Informasi Petugas</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><th width="30%">Nama</th><td>{{ $petugas->nama }}</td></tr>
                    <tr><th>NIP</th><td>{{ $petugas->nip ?? '-' }}</td></tr>
                    <tr><th>Jenis Kelamin</th><td>{{ $petugas->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><th>Tanggal Lahir</th><td>{{ $petugas->tanggal_lahir ? date('d/m/Y', strtotime($petugas->tanggal_lahir)) : '-' }}</td></tr>
                    <tr><th>No HP</th><td>{{ $petugas->no_hp ?? '-' }}</td></tr>
                    <tr><th>Email</th><td>{{ $petugas->user->email }}</td></tr>
                    <tr><th>Alamat</th><td>{{ $petugas->alamat ?? '-' }}</td></tr>
                    <tr><th>Status</th><td><span class="badge bg-{{ $petugas->status_badge }}">{{ $petugas->status }}</span></td></tr>
                    <tr><th>Bergabung</th><td>{{ $petugas->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection