<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\Progres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AspirasiController extends Controller
{
    public function create()
    {
        $kategoris = Kategori::all();
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        return view('siswa.aspirasi.create', compact('kategoris', 'ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_ruangan' => 'required|exists:ruangan,id_ruangan',
            'keterangan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Cek jumlah aspirasi hari ini
        $today = now()->startOfDay();
        $countToday = Aspirasi::where('user_id', Auth::id())
            ->whereDate('created_at', '>=', $today)
            ->count();

        // Batasan maksimal 3 kali dalam 1 hari
        if ($countToday >= 3) {
            return redirect()->back()
                ->with('error', 'Anda telah mencapai batas maksimal 3 aspirasi dalam 1 hari. Silakan coba lagi besok.')
                ->withInput();
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
            'status' => 'Menunggu'
        ]);

        // Hitung sisa kuota
        $sisaKuota = 3 - ($countToday + 1);

        return redirect()->route('siswa.aspirasi.index')
            ->with('success', "Aspirasi berhasil dikirim! Sisa kuota hari ini: {$sisaKuota} aspirasi lagi.");
    }

    public function index()
    {
        $aspirasi = Aspirasi::with(['kategori', 'ruangan'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('siswa.aspirasi.index', compact('aspirasi'));
    }

    public function detail($id)
    {
        $aspirasi = Aspirasi::with(['kategori', 'ruangan', 'progres.user', 'historyStatus.pengubah'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('siswa.aspirasi.detail', compact('aspirasi'));
    }

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

    public function history()
    {
        $aspirasiSelesai = Aspirasi::with(['kategori', 'ruangan', 'historyStatus.pengubah'])
            ->where('user_id', Auth::id())
            ->where('status', 'Selesai')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('siswa.aspirasi.history', compact('aspirasiSelesai'));
    }

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

    public function profile()
    {
        $siswa = Auth::user()->siswa;
        return view('siswa.profile', compact('siswa'));
    }
}
