<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\Siswa;
use App\Models\Progres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AspirasiController extends Controller
{
    // Dashboard siswa
    public function dashboard()
    {
        $siswa = Auth::user()->siswa;
        $totalAspirasi = Aspirasi::where('user_id', Auth::id())->count();
        $aspirasiMenunggu = Aspirasi::where('user_id', Auth::id())->where('status', 'Menunggu')->count();
        $aspirasiProses = Aspirasi::where('user_id', Auth::id())->where('status', 'Proses')->count();
        $aspirasiSelesai = Aspirasi::where('user_id', Auth::id())->where('status', 'Selesai')->count();
        
        $aspirasiTerbaru = Aspirasi::where('user_id', Auth::id())
            ->with('kategori')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('siswa.dashboard', compact('siswa', 'totalAspirasi', 'aspirasiMenunggu', 
            'aspirasiProses', 'aspirasiSelesai', 'aspirasiTerbaru'));
    }

    // Menampilkan form buat aspirasi
    public function create()
    {
        $kategoris = Kategori::all();
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        
        // Ambil data siswa yang login
        $siswaSekarang = Auth::user()->siswa;
        
        // Ambil siswa lain dengan kelas yang sama (untuk jadi saksi)
        $calonSaksi = collect();
        if ($siswaSekarang && $siswaSekarang->id_kelas) {
            $calonSaksi = Siswa::where('id_kelas', $siswaSekarang->id_kelas)
                ->where('id', '!=', $siswaSekarang->id)
                ->get();
        }
        
        return view('siswa.aspirasi.create', compact('kategoris', 'ruangans', 'calonSaksi', 'siswaSekarang'));
    }

    // Menyimpan aspirasi
    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_ruangan' => 'required|exists:ruangan,id_ruangan',
            'keterangan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'saksi_id' => 'required|exists:siswa,id',
            'pin' => 'required|string|size:6'
        ]);

        // Verifikasi PIN
        $siswa = Auth::user()->siswa;
        if (!$siswa->verifyPin($request->pin)) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput();
        }

        // Cek jumlah aspirasi hari ini (maksimal 3)
        $today = now()->startOfDay();
        $countToday = Aspirasi::where('user_id', Auth::id())
            ->whereDate('created_at', '>=', $today)
            ->count();

        if ($countToday >= 3) {
            return redirect()->back()
                ->with('error', 'Anda telah mencapai batas maksimal 3 aspirasi dalam 1 hari. Silakan coba lagi besok.')
                ->withInput();
        }

        // Cek apakah saksi memiliki kelas yang sama
        if ($request->filled('saksi_id')) {
            $siswaSekarang = Auth::user()->siswa;
            $saksi = Siswa::find($request->saksi_id);
            
            if (!$saksi || $siswaSekarang->id_kelas != $saksi->id_kelas) {
                return redirect()->back()
                    ->with('error', 'Saksi harus berasal dari kelas yang sama!')
                    ->withInput();
            }
        }

        $ruangan = Ruangan::find($request->id_ruangan);
        $lokasi = $ruangan->nama_ruangan . ' (' . $ruangan->kode_ruangan . ')';

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('aspirasi_foto', 'public');
        }

        Aspirasi::create([
            'user_id' => Auth::id(),
            'id_kategori' => $request->id_kategori,
            'id_ruangan' => $request->id_ruangan,
            'lokasi' => $lokasi,
            'keterangan' => $request->keterangan,
            'foto' => $fotoPath,
            'status' => 'Menunggu',
            'saksi_id' => $request->saksi_id
        ]);

        $sisaKuota = 3 - ($countToday + 1);

        return redirect()->route('siswa.aspirasi.index')
            ->with('success', "Aspirasi berhasil dikirim! Sisa kuota hari ini: {$sisaKuota} aspirasi lagi.");
    }

    // Menampilkan daftar aspirasi siswa
    public function index()
    {
        $aspirasi = Aspirasi::with(['kategori', 'ruangan', 'saksi'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('siswa.aspirasi.index', compact('aspirasi'));
    }

    // Menampilkan detail aspirasi
    public function detail($id)
    {
        $aspirasi = Aspirasi::with(['kategori', 'ruangan', 'saksi', 'progres.user', 'historyStatus.pengubah'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('siswa.aspirasi.detail', compact('aspirasi'));
    }

    // STATUS ASPIRASI - Menampilkan aspirasi yang aktif (belum selesai)
    public function status()
    {
        $aspirasi = Aspirasi::with(['kategori', 'ruangan'])
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'Selesai')
            ->orderByRaw("FIELD(status, 'Proses', 'Menunggu')")
            ->orderBy('created_at', 'desc')
            ->get();
        
        $statistik = [
            'total' => $aspirasi->count(),
            'menunggu' => $aspirasi->where('status', 'Menunggu')->count(),
            'proses' => $aspirasi->where('status', 'Proses')->count(),
        ];
        
        return view('siswa.aspirasi.status', compact('aspirasi', 'statistik'));
    }

    // HISTORY ASPIRASI - Menampilkan aspirasi yang sudah selesai
    public function history()
    {
        $aspirasiSelesai = Aspirasi::with(['kategori', 'ruangan', 'historyStatus.pengubah'])
            ->where('user_id', Auth::id())
            ->where('status', 'Selesai')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        return view('siswa.aspirasi.history', compact('aspirasiSelesai'));
    }

    // Menyimpan feedback dari siswa
    public function storeFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string|min:3'
        ]);
        
        $aspirasi = Aspirasi::where('user_id', Auth::id())
            ->where('status', '!=', 'Selesai')
            ->findOrFail($id);
        
        Progres::create([
            'id_aspirasi' => $id,
            'user_id' => Auth::id(),
            'keterangan_progres' => 'Feedback dari siswa: ' . $request->feedback,
        ]);
        
        return redirect()->route('siswa.aspirasi.detail', $id)
            ->with('success', 'Feedback berhasil dikirim');
    }

    // Profile siswa
    public function profile()
    {
        $siswa = Auth::user()->siswa;
        return view('siswa.profile', compact('siswa'));
    }

    // Halaman setup PIN
    public function setupPin()
    {
        $siswa = Auth::user()->siswa;
        
        // Jika sudah punya PIN, redirect ke dashboard
        if ($siswa->hasPin()) {
            return redirect()->route('siswa.dashboard')->with('info', 'Anda sudah memiliki PIN. PIN tidak dapat diubah atau direset.');
        }
        
        return view('siswa.setup-pin');
    }

    // Proses setup PIN (hanya sekali seumur hidup)
    public function storePin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6|confirmed',
            'pin_confirmation' => 'required'
        ]);
        
        $siswa = Auth::user()->siswa;
        
        // Cek apakah sudah punya PIN
        if ($siswa->hasPin()) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Anda sudah memiliki PIN. PIN tidak dapat diubah atau direset!');
        }
        
        // Simpan PIN
        $siswa->createPin($request->pin);
        
        return redirect()->route('siswa.dashboard')
            ->with('success', 'PIN berhasil dibuat! PIN ini akan digunakan setiap kali Anda membuat aspirasi. Simpan PIN Anda dengan baik karena tidak dapat diubah.');
    }
}