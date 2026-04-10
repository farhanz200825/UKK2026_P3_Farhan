<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\Progres;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AspirasiController extends Controller
{
    public function index()
    {
        $aspirasi = Aspirasi::with(['kategori'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('siswa.aspirasi.index', compact('aspirasi'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();
        return view('siswa.aspirasi.create', compact('kategoris', 'ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'lokasi' => 'required|string|max:100',
            'keterangan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('aspirasi_foto', 'public');
        }

        Aspirasi::create([
            'user_id' => Auth::id(),
            'id_kategori' => $request->id_kategori,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
            'foto' => $fotoPath,
            'status' => 'Menunggu'
        ]);

        return redirect()->route('siswa.aspirasi.index')
            ->with('success', 'Aspirasi berhasil dikirim');
    }

    public function detail($id)
    {
        $aspirasi = Aspirasi::with(['kategori', 'progres.user', 'historyStatus.pengubah'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('siswa.aspirasi.detail', compact('aspirasi'));
    }

    public function status()
    {
        $aspirasi = Aspirasi::with(['kategori'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('siswa.aspirasi.status', compact('aspirasi'));
    }

    public function history()
    {
        $history = Aspirasi::with(['kategori', 'historyStatus'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('siswa.aspirasi.history', compact('history'));
    }

    // METHOD STORE FEEDBACK - PERBAIKAN
    public function storeFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string|min:3'
        ]);
        
        // Pastikan aspirasi milik siswa yang login
        $aspirasi = Aspirasi::where('user_id', Auth::id())->find($id);
        
        if (!$aspirasi) {
            return redirect()->route('siswa.aspirasi.index')
                ->with('error', 'Aspirasi tidak ditemukan');
        }
        
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