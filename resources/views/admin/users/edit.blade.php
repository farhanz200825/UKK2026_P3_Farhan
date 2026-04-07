@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="mb-0">Edit Pengguna</h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users') }}">Manajemen Pengguna</a></li>
                        <li class="breadcrumb-item active">Edit Pengguna</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Form Edit Pengguna</h5>
                </div>
                <div class="card-body">
                    <form id="editUserForm">
                        <input type="hidden" id="userId" value="1">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" value="Admin Sekolah">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" value="admin@sekolah.com">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation" placeholder="Konfirmasi password baru">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" id="role">
                                    <option value="Admin" selected>Admin</option>
                                    <option value="Guru">Guru</option>
                                    <option value="Siswa">Siswa</option>
                                    <option value="Staff">Staff</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="status">
                                    <option value="Aktif" selected>Aktif</option>
                                    <option value="Nonaktif">Nonaktif</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Inisial</label>
                                <input type="text" class="form-control" id="inisial" value="AD" maxlength="2">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-warning text-white" onclick="updateUser()">
                        <i class="ph ph-check me-2"></i>Update
                    </button>
                    <a href="{{ route('users') }}" class="btn btn-secondary">
                        <i class="ph ph-x me-2"></i>Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateUser() {
        const nama = document.getElementById('nama').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        
        if (!nama || !email) {
            alert('Nama dan email wajib diisi!');
            return;
        }
        
        if (password && password.length < 8) {
            alert('Password minimal 8 karakter!');
            return;
        }
        
        if (password && password !== passwordConfirmation) {
            alert('Password dan konfirmasi password tidak cocok!');
            return;
        }
        
        alert('Data user berhasil diupdate! (Demo)');
        window.location.href = "{{ route('users') }}";
    }
</script>
@endsection