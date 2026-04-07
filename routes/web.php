<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard redirect based on role
Route::get('/dashboard', function() {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'guru') {
            return redirect()->route('guru.dashboard');
        } elseif ($role === 'siswa') {
            return redirect()->route('siswa.dashboard');
        }
    }
    return redirect()->route('login');
})->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [DashboardController::class, 'users'])->name('users');
    
    // Siswa CRUD
    Route::get('/siswa/create', [DashboardController::class, 'createSiswa'])->name('siswa.create');
    Route::post('/siswa/store', [DashboardController::class, 'storeSiswa'])->name('siswa.store');
    Route::get('/siswa/{id}/edit', [DashboardController::class, 'editSiswa'])->name('siswa.edit');
    Route::put('/siswa/{id}', [DashboardController::class, 'updateSiswa'])->name('siswa.update');
    Route::delete('/siswa/{id}', [DashboardController::class, 'destroySiswa'])->name('siswa.destroy');
    
    // Guru CRUD
    Route::get('/guru/create', [DashboardController::class, 'createGuru'])->name('guru.create');
    Route::post('/guru/store', [DashboardController::class, 'storeGuru'])->name('guru.store');
    Route::get('/guru/{id}/edit', [DashboardController::class, 'editGuru'])->name('guru.edit');
    Route::put('/guru/{id}', [DashboardController::class, 'updateGuru'])->name('guru.update');
    Route::delete('/guru/{id}', [DashboardController::class, 'destroyGuru'])->name('guru.destroy');
    
    Route::get('/sarana', function () { return view('admin.sarana.index'); })->name('sarana');
    Route::get('/pengaduan', function () { return view('admin.pengaduan.index'); })->name('pengaduan');
});

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', function () {
        return view('guru.dashboard');
    })->name('dashboard');
});

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('siswa.dashboard');
    })->name('dashboard');
    
    Route::get('/aspirasi/create', function () {
        return view('siswa.aspirasi.create');
    })->name('aspirasi.create');
    
    Route::get('/aspirasi/status', function () {
        return view('siswa.aspirasi.status');
    })->name('aspirasi.status');
    
    Route::get('/aspirasi/history', function () {
        return view('siswa.aspirasi.history');
    })->name('aspirasi.history');
    
    Route::get('/aspirasi/feedback', function () {
        return view('siswa.aspirasi.feedback');
    })->name('aspirasi.feedback');
    
    Route::get('/aspirasi/detail/{id}', function ($id) {
        return view('siswa.aspirasi.detail', ['id' => $id]);
    })->name('aspirasi.detail');
});