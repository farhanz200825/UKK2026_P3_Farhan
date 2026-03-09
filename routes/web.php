<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auts.login');
})->name('login');

Route::get('/register', function () {
    return view('auts.register');
})->name('register'); // Tambahkan nama route

Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');