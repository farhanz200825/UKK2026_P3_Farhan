<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;
        $password = $request->password;
        
        // Cari user berdasarkan email (admin)
        $user = User::where('email', $login)->first();
        
        // Jika tidak ditemukan, cari berdasarkan NIP (guru)
        if (!$user) {
            $guru = Guru::where('nip', $login)->first();
            if ($guru) {
                $user = $guru->user;
            }
        }
        
        // Jika masih tidak ditemukan, cari berdasarkan NIS (siswa)
        if (!$user) {
            $siswa = Siswa::where('nis', $login)->first();
            if ($siswa) {
                $user = $siswa->user;
            }
        }
        
        // Jika user ditemukan dan password cocok
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            
            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role === 'guru') {
                return redirect()->intended(route('guru.dashboard'));
            } elseif ($user->role === 'siswa') {
                return redirect()->intended(route('siswa.dashboard'));
            }
        }
        
        // Jika login gagal
        return back()->withErrors([
            'login' => 'Email/NIP/NIS atau password salah.',
        ])->withInput($request->only('login'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard')->with('success', 'Registrasi berhasil! Selamat datang admin.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}