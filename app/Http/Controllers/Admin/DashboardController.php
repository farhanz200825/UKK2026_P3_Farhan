<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Petugas;
use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\Progres;
use App\Models\HistoryStatus;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    // Dashboard dengan statistik
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalAspirasi = Aspirasi::count();
        $totalAdmin = User::where('role', 'admin')->count();

        $aspirasiMenunggu = Aspirasi::where('status', 'Menunggu')->count();
        $aspirasiProses = Aspirasi::where('status', 'Proses')->count();
        $aspirasiSelesai = Aspirasi::where('status', 'Selesai')->count();

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
            'totalSiswa',
            'totalGuru',
            'totalAspirasi',
            'totalAdmin',
            'aspirasiMenunggu',
            'aspirasiProses',
            'aspirasiSelesai',
            'bulanLabels',
            'bulanData'
        ));
    }

    public function users()
    {
        $admins = User::where('role', 'admin')->get();
        $gurus = Guru::with('user', 'kelas')->get();
        $siswas = Siswa::with('user', 'kelasRelasi', 'jurusanRelasi')->get();
        $petugas = Petugas::with('user')->get();

        // Ambil semua kelas
        $allKelas = Kelas::with('jurusan')->get();

        // Ambil ID kelas yang sudah memiliki Wali Kelas
        $kelasTerpakai = Guru::where('jabatan', 'Wali Kelas')
            ->whereNotNull('id_kelas')
            ->pluck('id_kelas')
            ->toArray();

        // Kelas yang tersedia (belum ada wali kelas)
        $kelasTersedia = Kelas::whereNotIn('id_kelas', $kelasTerpakai)->get();

        $allJurusan = Jurusan::all();

        return view('admin.users.index', compact('admins', 'gurus', 'siswas', 'petugas', 'allKelas', 'allJurusan', 'kelasTersedia', 'kelasTerpakai'));
    }

    // ==================== CRUD SISWA ====================
    public function storeSiswa(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $kelas = Kelas::find($request->id_kelas);
        $jurusan = Jurusan::find($request->id_jurusan);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_siswa', 'public');
        }

        Siswa::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $kelas->nama_kelas ?? $request->kelas,
            'jurusan' => $jurusan->nama_jurusan ?? $request->jurusan,
            'id_kelas' => $request->id_kelas,
            'id_jurusan' => $request->id_jurusan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('admin.users')->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function updateSiswa(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nis' => 'required|unique:siswa,nis,' . $id,
            'nama' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $kelas = Kelas::find($request->id_kelas);
        $jurusan = Jurusan::find($request->id_jurusan);

        $userData = ['email' => $request->email];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $siswa->user->update($userData);

        if ($request->hasFile('foto')) {
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $fotoPath = $request->file('foto')->store('foto_siswa', 'public');
            $siswa->foto = $fotoPath;
        }

        $siswa->update([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $kelas->nama_kelas ?? $request->kelas,
            'jurusan' => $jurusan->nama_jurusan ?? $request->jurusan,
            'id_kelas' => $request->id_kelas,
            'id_jurusan' => $request->id_jurusan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

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

    public function storeGuru(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'nullable|unique:guru,nip',
            'mata_pelajaran' => 'nullable|string|max:100',
            'jabatan' => 'required|in:Guru,Kepala Sekolah,Wakil Kepala Sekolah,Wali Kelas,Kepala Jurusan',
            'id_kelas' => 'required_if:jabatan,Wali Kelas|nullable|exists:kelas,id_kelas',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Cek apakah kelas sudah memiliki Wali Kelas
        if ($request->jabatan == 'Wali Kelas' && $request->id_kelas) {
            $existingWaliKelas = Guru::where('jabatan', 'Wali Kelas')
                ->where('id_kelas', $request->id_kelas)
                ->exists();

            if ($existingWaliKelas) {
                return redirect()->back()
                    ->with('error', 'Kelas ini sudah memiliki Wali Kelas!')
                    ->withInput();
            }
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_guru', 'public');
        }

        Guru::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'mata_pelajaran' => $request->mata_pelajaran,
            'jabatan' => $request->jabatan,
            'id_kelas' => $request->jabatan == 'Wali Kelas' ? $request->id_kelas : null,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'foto' => $fotoPath
        ]);

        return redirect()->route('admin.users')->with('success', 'Guru berhasil ditambahkan');
    }

    public function updateGuru(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'nullable|unique:guru,nip,' . $id,
            'mata_pelajaran' => 'nullable|string|max:100',
            'jabatan' => 'required|in:Guru,Kepala Sekolah,Wakil Kepala Sekolah,Wali Kelas,Kepala Jurusan',
            'id_kelas' => 'required_if:jabatan,Wali Kelas|nullable|exists:kelas,id_kelas',
            'email' => 'required|email|unique:users,email,' . $guru->user_id,
            'password' => 'nullable|min:6',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Cek apakah kelas sudah memiliki Wali Kelas lain
        if ($request->jabatan == 'Wali Kelas' && $request->id_kelas) {
            $existingWaliKelas = Guru::where('jabatan', 'Wali Kelas')
                ->where('id_kelas', $request->id_kelas)
                ->where('id', '!=', $id)
                ->exists();

            if ($existingWaliKelas) {
                return redirect()->back()
                    ->with('error', 'Kelas ini sudah memiliki Wali Kelas lain!')
                    ->withInput();
            }
        }

        $userData = ['email' => $request->email];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $guru->user->update($userData);

        if ($request->hasFile('foto')) {
            if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                Storage::disk('public')->delete($guru->foto);
            }
            $fotoPath = $request->file('foto')->store('foto_guru', 'public');
            $guru->foto = $fotoPath;
        }

        $guru->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'mata_pelajaran' => $request->mata_pelajaran,
            'jabatan' => $request->jabatan,
            'id_kelas' => $request->jabatan == 'Wali Kelas' ? $request->id_kelas : null,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.users')->with('success', 'Guru berhasil diupdate');
    }

    public function destroyGuru($id)
    {
        $guru = Guru::findOrFail($id);
        $user = $guru->user;

        if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->delete();
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Guru berhasil dihapus');
    }

    // ==================== CRUD ADMIN ====================
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

        if ($admin->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        $admin->delete();

        return redirect()->route('admin.users')->with('success', 'Admin berhasil dihapus');
    }

    // ==================== CRUD PETUGAS ====================
    public function storePetugas(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'nullable|unique:petugas,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Aktif,Tidak Aktif'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_petugas', 'public');
        }

        Petugas::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'foto' => $fotoPath,
            'status' => $request->status
        ]);

        return redirect()->route('admin.users')->with('success', 'Petugas berhasil ditambahkan');
    }

    public function updatePetugas(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'nullable|unique:petugas,nip,' . $id,
            'email' => 'required|email|unique:users,email,' . $petugas->user_id,
            'password' => 'nullable|min:6',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Aktif,Tidak Aktif'
        ]);

        $userData = ['email' => $request->email];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $petugas->user->update($userData);

        if ($request->hasFile('foto')) {
            if ($petugas->foto && Storage::disk('public')->exists($petugas->foto)) {
                Storage::disk('public')->delete($petugas->foto);
            }
            $fotoPath = $request->file('foto')->store('foto_petugas', 'public');
            $petugas->foto = $fotoPath;
        }

        $petugas->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'status' => $request->status
        ]);

        return redirect()->route('admin.users')->with('success', 'Petugas berhasil diupdate');
    }

    public function destroyPetugas($id)
    {
        $petugas = Petugas::findOrFail($id);

        if ($petugas->foto && Storage::disk('public')->exists($petugas->foto)) {
            Storage::disk('public')->delete($petugas->foto);
        }

        $user = $petugas->user;
        $petugas->delete();
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Petugas berhasil dihapus');
    }

    // ==================== MANAJEMEN KATEGORI & MASTER DATA ====================
    public function kategori()
    {
        $kategoris = Kategori::withCount('aspirasi')->get();
        $jurusans = Jurusan::withCount('kelas')->get();
        $kelas = Kelas::with('jurusan')->withCount('siswa')->get();
        $ruangans = Ruangan::withCount('aspirasi')->get();
        $allJurusans = Jurusan::all();

        return view('admin.kategori.index', compact('kategoris', 'jurusans', 'kelas', 'ruangans', 'allJurusans'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori',
            'deskripsi' => 'nullable|string'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function updateKategori(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori,' . $id . ',id_kategori',
            'deskripsi' => 'nullable|string'
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroyKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil dihapus');
    }

    // ==================== CRUD JURUSAN ====================
    public function storeJurusan(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'required|unique:jurusan',
            'nama_jurusan' => 'required',
            'deskripsi' => 'nullable'
        ]);

        Jurusan::create($request->all());
        return redirect()->route('admin.kategori')->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function updateJurusan(Request $request, $id)
    {
        $request->validate([
            'kode_jurusan' => 'required|unique:jurusan,kode_jurusan,' . $id . ',id_jurusan',
            'nama_jurusan' => 'required',
            'deskripsi' => 'nullable'
        ]);

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update($request->all());
        return redirect()->route('admin.kategori')->with('success', 'Jurusan berhasil diupdate');
    }

    public function destroyJurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();
        return redirect()->route('admin.kategori')->with('success', 'Jurusan berhasil dihapus');
    }

    // ==================== CRUD KELAS ====================
    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas',
            'tingkat' => 'required',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'kapasitas' => 'nullable|integer'
        ]);

        Kelas::create($request->all());
        return redirect()->route('admin.kategori')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function updateKelas(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas,' . $id . ',id_kelas',
            'tingkat' => 'required',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'kapasitas' => 'nullable|integer'
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());
        return redirect()->route('admin.kategori')->with('success', 'Kelas berhasil diupdate');
    }

    public function destroyKelas($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return redirect()->route('admin.kategori')->with('success', 'Kelas berhasil dihapus');
    }

    // ==================== CRUD RUANGAN ====================
    public function storeRuangan(Request $request)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:ruangan',
            'nama_ruangan' => 'required',
            'jenis_ruangan' => 'required',
            'lokasi' => 'nullable',
            'kapasitas' => 'nullable|integer',
            'kondisi' => 'required',
            'deskripsi' => 'nullable'
        ]);

        Ruangan::create($request->all());
        return redirect()->route('admin.kategori')->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function updateRuangan(Request $request, $id)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:ruangan,kode_ruangan,' . $id . ',id_ruangan',
            'nama_ruangan' => 'required',
            'jenis_ruangan' => 'required',
            'lokasi' => 'nullable',
            'kapasitas' => 'nullable|integer',
            'kondisi' => 'required',
            'deskripsi' => 'nullable'
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update($request->all());
        return redirect()->route('admin.kategori')->with('success', 'Ruangan berhasil diupdate');
    }

    public function destroyRuangan($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();
        return redirect()->route('admin.kategori')->with('success', 'Ruangan berhasil dihapus');
    }

    // ==================== ASPIRASI MANAGEMENT ====================
    // Data Aspirasi - Hanya menampilkan yang belum selesai (Menunggu dan Proses)
    public function pengaduan(Request $request)
    {
        $query = Aspirasi::with(['user.siswa', 'user.guru', 'kategori', 'ruangan', 'progres'])
            ->where('status', '!=', 'Selesai'); // HANYA yang belum selesai

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        if ($request->filled('ruangan')) {
            $query->where('id_ruangan', $request->ruangan);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('keterangan', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        $aspirasi = $query->orderBy('created_at', 'desc')->paginate(10);
        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();

        // Statistik (semua status)
        $statistik = [
            'total' => Aspirasi::count(),
            'menunggu' => Aspirasi::where('status', 'Menunggu')->count(),
            'proses' => Aspirasi::where('status', 'Proses')->count(),
            'selesai' => Aspirasi::where('status', 'Selesai')->count(),
        ];

        return view('admin.pengaduan.index', compact('aspirasi', 'kategoris', 'ruangans', 'statistik'));
    }

    public function pengaduanDetail($id)
    {
        $aspirasi = Aspirasi::with(['user.siswa', 'user.guru', 'kategori', 'ruangan', 'progres.user', 'historyStatus.pengubah'])
            ->findOrFail($id);

        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();

        return view('admin.pengaduan.detail', compact('aspirasi', 'kategoris', 'ruangans'));
    }

    public function storeFeedback(Request $request, $id)
    {
        $request->validate(['feedback' => 'required|string']);

        Progres::create([
            'id_aspirasi' => $id,
            'user_id' => auth()->id(),
            'keterangan_progres' => 'Feedback dari Admin: ' . $request->feedback,
        ]);

        return redirect()->back()->with('success', 'Feedback berhasil ditambahkan');
    }

    public function storeProgres(Request $request, $id)
    {
        $request->validate(['keterangan_progres' => 'required|string']);

        Progres::create([
            'id_aspirasi' => $id,
            'user_id' => auth()->id(),
            'keterangan_progres' => $request->keterangan_progres,
        ]);

        return redirect()->back()->with('success', 'Progres berhasil ditambahkan');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'keterangan_progres' => 'required_if:status,Selesai|nullable|string',
            'foto_bukti' => 'required_if:status,Selesai|nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $aspirasi = Aspirasi::findOrFail($id);
        $statusLama = $aspirasi->status;
        $statusBaru = $request->status;

        // Simpan history status
        HistoryStatus::create([
            'id_aspirasi' => $id,
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'diubah_oleh' => auth()->id(),
        ]);

        // Update status
        $aspirasi->update(['status' => $statusBaru]);

        // Handle upload foto bukti jika status Selesai
        $fotoBuktiPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoBuktiPath = $request->file('foto_bukti')->store('aspirasi_bukti', 'public');
        }

        // Simpan progres jika ada keterangan
        if ($request->filled('keterangan_progres')) {
            $progresText = $request->keterangan_progres;
            if ($fotoBuktiPath) {
                $progresText .= "\n\n📎 Foto bukti: " . asset('storage/' . $fotoBuktiPath);
            }
            Progres::create([
                'id_aspirasi' => $id,
                'user_id' => auth()->id(),
                'keterangan_progres' => $progresText,
            ]);
        }

        // Jika status menjadi Selesai, tambahkan progres otomatis
        if ($statusBaru == 'Selesai') {
            $selesaiText = 'Aspirasi telah selesai ditangani oleh Admin';
            if ($fotoBuktiPath) {
                $selesaiText .= "\n\n📎 Foto bukti: " . asset('storage/' . $fotoBuktiPath);
            }
            Progres::create([
                'id_aspirasi' => $id,
                'user_id' => auth()->id(),
                'keterangan_progres' => $selesaiText,
            ]);
        }

        $message = $statusBaru == 'Selesai'
            ? 'Aspirasi telah selesai dan masuk ke history'
            : 'Status berhasil diupdate';

        return redirect()->back()->with('success', $message);
    }

    public function destroyAspirasi($id)
    {
        $aspirasi = Aspirasi::findOrFail($id);

        if ($aspirasi->foto && Storage::disk('public')->exists($aspirasi->foto)) {
            Storage::disk('public')->delete($aspirasi->foto);
        }

        Progres::where('id_aspirasi', $id)->delete();
        HistoryStatus::where('id_aspirasi', $id)->delete();
        $aspirasi->delete();

        return redirect()->route('admin.pengaduan')->with('success', 'Aspirasi berhasil dihapus');
    }

    public function history(Request $request)
    {
        // Hanya ambil history dengan status_baru = Selesai
        $query = HistoryStatus::with(['aspirasi.user.siswa', 'aspirasi.user.guru', 'aspirasi.kategori', 'aspirasi.ruangan', 'pengubah'])
            ->where('status_baru', 'Selesai'); // HANYA yang statusnya menjadi Selesai

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $history = $query->orderBy('created_at', 'desc')->paginate(20);

        $statistik = [
            'total' => HistoryStatus::where('status_baru', 'Selesai')->count(),
            'menunggu' => 0, // Tidak ada karena hanya Selesai
            'proses' => 0,    // Tidak ada karena hanya Selesai
            'selesai' => HistoryStatus::where('status_baru', 'Selesai')->count(),
        ];

        return view('admin.history', compact('history', 'statistik'));
    }

    // ==================== IMPORT SISWA ====================
    public function importSiswa(Request $request)
    {
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls,csv|max:2048']);

        try {
            $import = new SiswaImport();
            Excel::import($import, $request->file('file_excel'));

            $successCount = $import->getSuccessCount();
            $failures = $import->getFailures();

            $successMessage = "Berhasil mengimport {$successCount} data siswa.";

            if (count($failures) > 0) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = "Data: " . json_encode($failure['row']) . " - Error: " . $failure['errors'];
                }
                return redirect()->back()->with('warning', $successMessage . ' Namun ada data yang gagal diimport.')->with('import_errors', $errorMessages);
            }

            return redirect()->route('admin.users')->with('success', $successMessage);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('warning', 'Validasi gagal:<br>' . implode('<br>', $errors))->with('import_errors', $errors);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    // Download template Excel
    public function downloadTemplateSiswa()
    {
        $headers = ['nis', 'nama', 'kelas', 'jurusan', 'jenis_kelamin', 'tanggal_lahir', 'no_hp', 'email', 'password', 'alamat'];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headers);
            fputcsv($file, ['232410001', 'Ahmad Fauzi', '10 RPL', 'RPL', 'L', '2008-05-15', '081234567890', 'ahmad@example.com', 'siswa123', 'Jl. Pendidikan No.1']);
            fputcsv($file, ['232410002', 'Siti Aminah', '10 TKJ', 'TKJ', 'P', '2008-08-20', '081234567891', 'siti@example.com', 'siswa123', 'Jl. Merdeka No.2']);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_siswa.csv"',
        ]);
    }
}
