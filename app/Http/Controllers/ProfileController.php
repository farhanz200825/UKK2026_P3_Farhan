<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function myAccount()
    {
        $user = Auth::user();

        // Ambil data tambahan berdasarkan role
        if ($user->role == 'siswa') {
            $profile = $user->siswa;
        } elseif ($user->role == 'guru') {
            $profile = $user->guru;
        } else {
            $profile = null;
        }

        return view('profile.my-account', compact('user', 'profile'));
    }

    public function settings()
    {
        $user = Auth::user();

        // Load relasi sesuai role
        if ($user->role == 'siswa') {
            $user->load('siswa');
            $profile = $user->siswa;
        } elseif ($user->role == 'guru') {
            $user->load('guru');
            $profile = $user->guru;
        } elseif ($user->role == 'petugas') {
            $user->load('petugas');
            $profile = $user->petugas;
        } else {
            $profile = null;
        }

        return view('profile.settings', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'password' => 'nullable|min:6|confirmed'
        ]);

        // Update user email
        $user->update(['email' => $request->email]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update data profile berdasarkan role
        if ($user->role == 'siswa' && $user->siswa) {
            $user->siswa->update([
                'nama' => $request->nama ?? $user->siswa->nama,
                'kelas' => $request->kelas ?? $user->siswa->kelas,
                'jurusan' => $request->jurusan ?? $user->siswa->jurusan,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin
            ]);
        } elseif ($user->role == 'guru' && $user->guru) {
            $user->guru->update([
                'nama' => $request->nama ?? $user->guru->nama,
                'mata_pelajaran' => $request->mata_pelajaran ?? $user->guru->mata_pelajaran,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin
            ]);
        } elseif ($user->role == 'petugas' && $user->petugas) {
            $user->petugas->update([
                'nama' => $request->nama ?? $user->petugas->nama,
                'status' => $request->status ?? $user->petugas->status,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin
            ]);
        }

        return redirect()->route('profile.settings')->with('success', 'Profil berhasil diupdate');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->role == 'siswa' && $user->siswa && $user->siswa->foto) {
            Storage::delete('public/' . $user->siswa->foto);
        } elseif ($user->role == 'guru' && $user->guru && $user->guru->foto) {
            Storage::delete('public/' . $user->guru->foto);
        }

        // Upload foto baru
        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('public/foto_profil', $filename);

        // Simpan path ke database
        if ($user->role == 'siswa' && $user->siswa) {
            $user->siswa->update(['foto' => 'foto_profil/' . $filename]);
        } elseif ($user->role == 'guru' && $user->guru) {
            $user->guru->update(['foto' => 'foto_profil/' . $filename]);
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
    }
}
