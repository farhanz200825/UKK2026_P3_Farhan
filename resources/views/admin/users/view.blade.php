@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="mb-0">Detail Pengguna</h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users') }}">Manajemen Pengguna</a></li>
                        <li class="breadcrumb-item active">Detail Pengguna</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar-circle-large bg-primary text-white mx-auto mb-3">
                        AD
                    </div>
                    <h4 class="mb-1">Admin Sekolah</h4>
                    <p class="text-muted mb-3">ID: USR001</p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-primary">Admin</span>
                        <span class="badge bg-success">Aktif</span>
                    </div>
                    
                    <button class="btn btn-warning text-white me-2" onclick="location.href='{{ route('users') }}/edit/1'">
                        <i class="ph ph-pencil me-1"></i>Edit
                    </button>
                    <button class="btn btn-danger" onclick="confirmDelete(1)">
                        <i class="ph ph-trash me-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Informasi Lengkap</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>ID Pengguna</strong></td>
                            <td>: USR001</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Lengkap</strong></td>
                            <td>: Admin Sekolah</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>: admin@sekolah.com</td>
                        </tr>
                        <tr>
                            <td><strong>Role</strong></td>
                            <td>: <span class="badge bg-primary">Admin</span></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: <span class="badge bg-success">Aktif</span></td>
                        </tr>
                        <tr>
                            <td><strong>Terdaftar</strong></td>
                            <td>: 10 Januari 2026</td>
                        </tr>
                        <tr>
                            <td><strong>Terakhir Login</strong></td>
                            <td>: 10 Maret 2026, 08:30 WIB</td>
                        </tr>
                        <tr>
                            <td><strong>Total Pengaduan</strong></td>
                            <td>: 12 pengaduan</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('users') }}" class="btn btn-secondary">
                        <i class="ph ph-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
            alert('Data user berhasil dihapus! (Demo)');
            window.location.href = "{{ route('users') }}";
        }
    }
</script>

<style>
    .avatar-circle-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 40px;
    }
    
    .badge {
        padding: 6px 12px;
        border-radius: 30px;
    }
</style>
@endsection