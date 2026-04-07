<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\Progres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    // Dashboard dengan statistik
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalAspirasi = Aspirasi::count();
        $totalAdmin = User::where('role', 'admin')->count();
        
        // Statistik berdasarkan status
        $aspirasiMenunggu = Aspirasi::where('status', 'Menunggu')->count();
        $aspirasiProses = Aspirasi::where('status', 'Proses')->count();
        $aspirasiSelesai = Aspirasi::where('status', 'Selesai')->count();
        
        // Aspirasi per bulan (6 bulan terakhir)
        $bulanLabels = [];
        $bulanData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $bulanLabels[] = $bulan->format('M Y');
            $bulanData[] = Aspirasi::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }
        
        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalAspirasi', 'totalAdmin',
            'aspirasiMenunggu', 'aspirasiProses', 'aspirasiSelesai',
            'bulanLabels', 'bulanData'
        ));
    }

    // Manajemen User
    public function users()
    {
        $admins = User::where('role', 'admin')->get();
        $gurus = User::where('role', 'guru')->with('guru')->get();
        $siswas = User::where('role', 'siswa')->with('siswa')->get();
        
        return view('admin.users.index', compact('admins', 'gurus', 'siswas'));
    }

    // CRUD Siswa
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

    public function destroySiswa($id)
    {
        $siswa = Siswa::findOrFail($id);
        $user = $siswa->user;
        $siswa->delete();
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'Data siswa berhasil dihapus');
    }

    // CRUD Guru
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

    public function destroyGuru($id)
    {
        $guru = Guru::findOrFail($id);
        $user = $guru->user;
        $guru->delete();
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'Data guru berhasil dihapus');
    }

    // Manajemen Kategori
    public function kategori()
    {
        $kategoris = Kategori::all();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function updateKategori(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori,' . $id . ',id_kategori'
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroyKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        
        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil dihapus');
    }

    // Manajemen Aspirasi/Pengaduan
    public function pengaduan(Request $request)
    {
        $query = Aspirasi::with(['user.siswa', 'user.guru', 'kategori']);
        
        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }
        
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('isi', 'like', '%' . $request->search . '%');
            });
        }
        
        $aspirasi = $query->orderBy('created_at', 'desc')->paginate(10);
        $kategoris = Kategori::all();
        
        return view('admin.pengaduan.index', compact('aspirasi', 'kategoris'));
    }

    public function pengaduanDetail($id)
    {
        $aspirasi = Aspirasi::with(['user.siswa', 'user.guru', 'kategori', 'progres.user'])->findOrFail($id);
        $kategoris = Kategori::all();
        
        return view('admin.pengaduan.detail', compact('aspirasi', 'kategoris'));
    }

    public function updateStatus(Request $request, $id)
    {
        $aspirasi = Aspirasi::findOrFail($id);
        $statusLama = $aspirasi->status;
        $statusBaru = $request->status;
        
        // Simpan history status
        \App\Models\HistoryStatus::create([
            'id_aspirasi' => $id,
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'diubah_oleh' => auth()->id(),
        ]);
        
        $aspirasi->update(['status' => $statusBaru]);
        
        return redirect()->back()->with('success', 'Status aspirasi berhasil diupdate');
    }

    public function storeFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required'
        ]);
        
        // Simpan feedback sebagai progres
        Progres::create([
            'id_aspirasi' => $id,
            'user_id' => auth()->id(),
            'keterangan_progres' => 'Feedback: ' . $request->feedback,
        ]);
        
        return redirect()->back()->with('success', 'Feedback berhasil ditambahkan');
    }

    public function storeProgres(Request $request, $id)
    {
        $request->validate([
            'keterangan_progres' => 'required'
        ]);
        
        Progres::create([
            'id_aspirasi' => $id,
            'user_id' => auth()->id(),
            'keterangan_progres' => $request->keterangan_progres,
        ]);
        
        return redirect()->back()->with('success', 'Progres berhasil ditambahkan');
    }

    // CRUD Admin
public function storeAdmin(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    User::create([
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'admin',
    ]);

    return redirect()->route('admin.users')->with('success', 'Admin berhasil ditambahkan');
}

public function updateAdmin(Request $request, $id)
{
    $admin = User::findOrFail($id);
    
    $request->validate([
        'email' => 'required|email|unique:users,email,' . $id,
    ]);

    $data = ['email' => $request->email];
    
    if ($request->filled('password')) {
        $request->validate(['password' => 'min:6']);
        $data['password'] = Hash::make($request->password);
    }
    
    $admin->update($data);

    return redirect()->route('admin.users')->with('success', 'Admin berhasil diupdate');
}

public function destroyAdmin($id)
{
    $admin = User::findOrFail($id);
    
    // Cegah menghapus diri sendiri
    if ($admin->id === auth()->id()) {
        return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun sendiri');
    }
    
    $admin->delete();
    
    return redirect()->route('admin.users')->with('success', 'Admin berhasil dihapus');
}
    
}