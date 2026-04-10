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

        // Ambil data tambahan berdasarkan role
        if ($user->role == 'siswa') {
            $profile = $user->siswa;
            // Format tanggal lahir jika ada
            if ($profile && $profile->tanggal_lahir) {
                $profile->tanggal_lahir_formatted = date('Y-m-d', strtotime($profile->tanggal_lahir));
            }
        } elseif ($user->role == 'guru') {
            $profile = $user->guru;
            // Format tanggal lahir jika ada
            if ($profile && $profile->tanggal_lahir) {
                $profile->tanggal_lahir_formatted = date('Y-m-d', strtotime($profile->tanggal_lahir));
            }
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
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update profile berdasarkan role
        if ($user->role == 'siswa' && $user->siswa) {
            $user->siswa->update([
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        } elseif ($user->role == 'guru' && $user->guru) {
            $user->guru->update([
                'nama' => $request->nama,
                'mata_pelajaran' => $request->mata_pelajaran,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
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
