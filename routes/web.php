<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Guru\AspirasiController as GuruAspirasiController;
use App\Http\Controllers\Siswa\AspirasiController as SiswaAspirasiController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard redirect
Route::get('/dashboard', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'guru') return redirect()->route('guru.dashboard');
        if ($role === 'siswa') return redirect()->route('siswa.dashboard');
        if ($role === 'petugas') return redirect()->route('petugas.dashboard');
    }
    return redirect()->route('login');
})->name('dashboard');

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [DashboardController::class, 'users'])->name('users');

    // CRUD Admin
    Route::post('/admin/store', [DashboardController::class, 'storeAdmin'])->name('admin.store');
    Route::put('/admin/{id}', [DashboardController::class, 'updateAdmin'])->name('admin.update');
    Route::delete('/admin/{id}', [DashboardController::class, 'destroyAdmin'])->name('admin.destroy');

    // CRUD Guru
    Route::post('/guru/store', [DashboardController::class, 'storeGuru'])->name('guru.store');
    Route::put('/guru/{id}', [DashboardController::class, 'updateGuru'])->name('guru.update');
    Route::delete('/guru/{id}', [DashboardController::class, 'destroyGuru'])->name('guru.destroy');

    // CRUD Siswa
    Route::post('/siswa/store', [DashboardController::class, 'storeSiswa'])->name('siswa.store');
    Route::put('/siswa/{id}', [DashboardController::class, 'updateSiswa'])->name('siswa.update');
    Route::delete('/siswa/{id}', [DashboardController::class, 'destroySiswa'])->name('siswa.destroy');

    // IMPORT SISWA
    Route::post('/siswa/import', [DashboardController::class, 'importSiswa'])->name('siswa.import');
    Route::get('/siswa/template', [DashboardController::class, 'downloadTemplateSiswa'])->name('siswa.template');

    // CRUD Petugas
    Route::post('/petugas/store', [DashboardController::class, 'storePetugas'])->name('petugas.store');
    Route::put('/petugas/{id}', [DashboardController::class, 'updatePetugas'])->name('petugas.update');
    Route::delete('/petugas/{id}', [DashboardController::class, 'destroyPetugas'])->name('petugas.destroy');

    // Master Data
    Route::get('/kategori', [DashboardController::class, 'kategori'])->name('kategori');
    Route::post('/kategori', [DashboardController::class, 'storeKategori'])->name('kategori.store');
    Route::put('/kategori/{id}', [DashboardController::class, 'updateKategori'])->name('kategori.update');
    Route::delete('/kategori/{id}', [DashboardController::class, 'destroyKategori'])->name('kategori.destroy');

    // CRUD Jurusan
    Route::post('/jurusan', [DashboardController::class, 'storeJurusan'])->name('jurusan.store');
    Route::put('/jurusan/{id}', [DashboardController::class, 'updateJurusan'])->name('jurusan.update');
    Route::delete('/jurusan/{id}', [DashboardController::class, 'destroyJurusan'])->name('jurusan.destroy');

    // CRUD Kelas
    Route::post('/kelas', [DashboardController::class, 'storeKelas'])->name('kelas.store');
    Route::put('/kelas/{id}', [DashboardController::class, 'updateKelas'])->name('kelas.update');
    Route::delete('/kelas/{id}', [DashboardController::class, 'destroyKelas'])->name('kelas.destroy');

    // CRUD Ruangan
    Route::post('/ruangan', [DashboardController::class, 'storeRuangan'])->name('ruangan.store');
    Route::put('/ruangan/{id}', [DashboardController::class, 'updateRuangan'])->name('ruangan.update');
    Route::delete('/ruangan/{id}', [DashboardController::class, 'destroyRuangan'])->name('ruangan.destroy');

    // Pengaduan/Aspirasi Management
    Route::get('/pengaduan', [DashboardController::class, 'pengaduan'])->name('pengaduan');
    Route::get('/pengaduan/{id}', [DashboardController::class, 'pengaduanDetail'])->name('pengaduan.detail');
    Route::post('/pengaduan/{id}/status', [DashboardController::class, 'updateStatus'])->name('pengaduan.status');
    Route::post('/pengaduan/{id}/feedback', [DashboardController::class, 'storeFeedback'])->name('pengaduan.feedback');
    Route::post('/pengaduan/{id}/progres', [DashboardController::class, 'storeProgres'])->name('pengaduan.progres');
    Route::delete('/pengaduan/{id}', [DashboardController::class, 'destroyAspirasi'])->name('pengaduan.destroy');

    // History
    Route::get('/history', [DashboardController::class, 'history'])->name('history');

    // Sarana
    Route::get('/sarana', function () {
        return view('admin.sarana.index');
    })->name('sarana');

    // Logs Activity
    Route::get('/logs', [DashboardController::class, 'logs'])->name('logs');
});

// ==================== GURU ROUTES ====================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruAspirasiController::class, 'dashboard'])->name('dashboard');
    Route::get('/aspirasi', [GuruAspirasiController::class, 'index'])->name('aspirasi.index');
    Route::get('/aspirasi/create', [GuruAspirasiController::class, 'create'])->name('aspirasi.create');
    Route::post('/aspirasi', [GuruAspirasiController::class, 'store'])->name('aspirasi.store');
    Route::get('/aspirasi/{id}', [GuruAspirasiController::class, 'detail'])->name('aspirasi.detail');
    Route::post('/aspirasi/{id}/feedback', [GuruAspirasiController::class, 'storeFeedback'])->name('aspirasi.feedback');
    Route::post('/aspirasi/{id}/progres', [GuruAspirasiController::class, 'storeProgres'])->name('aspirasi.progres');
    Route::put('/aspirasi/{id}/status', [GuruAspirasiController::class, 'updateStatus'])->name('aspirasi.status');
    Route::get('/history', [GuruAspirasiController::class, 'history'])->name('history');
    Route::get('/statistik', [GuruAspirasiController::class, 'statistik'])->name('statistik');
});

// ==================== SISWA ROUTES ====================
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/aspirasi/create', [SiswaAspirasiController::class, 'create'])->name('aspirasi.create');
    Route::post('/aspirasi', [SiswaAspirasiController::class, 'store'])->name('aspirasi.store');
    Route::get('/aspirasi', [SiswaAspirasiController::class, 'index'])->name('aspirasi.index');
    Route::get('/aspirasi/{id}', [SiswaAspirasiController::class, 'detail'])->name('aspirasi.detail');
    Route::get('/status', [SiswaAspirasiController::class, 'status'])->name('aspirasi.status');
    Route::get('/history', [SiswaAspirasiController::class, 'history'])->name('aspirasi.history');
    Route::post('/aspirasi/{id}/feedback', [SiswaAspirasiController::class, 'storeFeedback'])->name('aspirasi.feedback');
    Route::get('/profile', [SiswaAspirasiController::class, 'profile'])->name('profile');
});

// ==================== PROFILE ROUTES ====================
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/my-account', [ProfileController::class, 'myAccount'])->name('my-account');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::post('/update-photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');
});

// ==================== PETUGAS ROUTES ====================
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    Route::get('/aspirasi', [PetugasDashboardController::class, 'aspirasiIndex'])->name('aspirasi.index');
    Route::get('/aspirasi/{id}', [PetugasDashboardController::class, 'aspirasiDetail'])->name('aspirasi.detail');
    Route::post('/aspirasi/{id}/status', [PetugasDashboardController::class, 'updateStatus'])->name('aspirasi.status');
    Route::post('/aspirasi/{id}/feedback', [PetugasDashboardController::class, 'storeFeedback'])->name('aspirasi.feedback');
    Route::post('/aspirasi/{id}/progres', [PetugasDashboardController::class, 'storeProgres'])->name('aspirasi.progres');
    Route::get('/history', [PetugasDashboardController::class, 'history'])->name('history');
    Route::get('/profile', [PetugasDashboardController::class, 'profile'])->name('profile');
});
