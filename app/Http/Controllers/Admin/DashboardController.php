<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = \App\Models\Siswa::count();
        $totalGuru = \App\Models\Guru::count();
        $totalAspirasi = \App\Models\Aspirasi::count();
        $totalAdmin = User::where('role', 'admin')->count();

        return view('admin.dashboard', compact('totalSiswa', 'totalGuru', 'totalAspirasi', 'totalAdmin'));
    }

    public function users()
    {
        $admins = User::where('role', 'admin')->get();
        $gurus = User::where('role', 'guru')->with('guru')->get();
        $siswas = User::where('role', 'siswa')->with('siswa')->get();

        return view('admin.users.index', compact('admins', 'gurus', 'siswas'));
    }

    public function createSiswa()
    {
        return view('admin.users.create-siswa');
    }

    public function storeSiswa(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        Siswa::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.users')->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function createGuru()
    {
        return view('admin.users.create-guru');
    }

    public function storeGuru(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:guru,nip',
            'nama' => 'required',
            'mata_pelajaran' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
        ]);

        Guru::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'mata_pelajaran' => $request->mata_pelajaran,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.users')->with('success', 'Data guru berhasil ditambahkan');
    }

    public function editSiswa($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        return view('admin.users.edit-siswa', compact('siswa'));
    }

    public function updateSiswa(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nis' => 'required|unique:siswa,nis,' . $id,
            'nama' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
        ]);

        $siswa->update([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        if ($request->password) {
            $request->validate(['password' => 'min:6']);
            $siswa->user->update(['password' => Hash::make($request->password)]);
        }

        $siswa->user->update(['email' => $request->email]);

        return redirect()->route('admin.users')->with('success', 'Data siswa berhasil diupdate');
    }

    public function editGuru($id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        return view('admin.users.edit-guru', compact('guru'));
    }

    public function updateGuru(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nip' => 'required|unique:guru,nip,' . $id,
            'nama' => 'required',
            'mata_pelajaran' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email,' . $guru->user_id,
        ]);

        $guru->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'mata_pelajaran' => $request->mata_pelajaran,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        if ($request->password) {
            $request->validate(['password' => 'min:6']);
            $guru->user->update(['password' => Hash::make($request->password)]);
        }

        $guru->user->update(['email' => $request->email]);

        return redirect()->route('admin.users')->with('success', 'Data guru berhasil diupdate');
    }

    public function destroySiswa($id)
    {
        $siswa = Siswa::findOrFail($id);
        $user = $siswa->user;
        $siswa->delete();
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Data siswa berhasil dihapus');
    }

    public function destroyGuru($id)
    {
        $guru = Guru::findOrFail($id);
        $user = $guru->user;
        $guru->delete();
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Data guru berhasil dihapus');
    }
}
