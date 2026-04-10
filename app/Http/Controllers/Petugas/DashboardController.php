<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\Progres;
use App\Models\HistoryStatus;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $petugas = Auth::user()->petugas;
        
        // Statistik
        $totalAspirasi = Aspirasi::count();
        $aspirasiMenunggu = Aspirasi::where('status', 'Menunggu')->count();
        $aspirasiProses = Aspirasi::where('status', 'Proses')->count();
        $aspirasiSelesai = Aspirasi::where('status', 'Selesai')->count();
        
        // Aspirasi yang perlu ditangani (Menunggu dan Proses)
        $aspirasiAktif = Aspirasi::with(['user.siswa', 'kategori', 'ruangan'])
            ->whereIn('status', ['Menunggu', 'Proses'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
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
        
        return view('petugas.dashboard', compact(
            'petugas',
            'totalAspirasi',
            'aspirasiMenunggu',
            'aspirasiProses',
            'aspirasiSelesai',
            'aspirasiAktif',
            'bulanLabels',
            'bulanData'
        ));
    }
    
    public function aspirasiIndex(Request $request)
    {
        $query = Aspirasi::with(['user.siswa', 'kategori', 'ruangan', 'progres']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('keterangan', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }
        
        $aspirasi = $query->orderBy('created_at', 'desc')->paginate(10);
        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();
        
        return view('petugas.aspirasi.index', compact('aspirasi', 'kategoris', 'ruangans'));
    }
    
    public function aspirasiDetail($id)
    {
        $aspirasi = Aspirasi::with([
            'user.siswa', 
            'kategori', 
            'ruangan',
            'progres.user',
            'historyStatus.pengubah'
        ])->findOrFail($id);
        
        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();
        
        return view('petugas.aspirasi.detail', compact('aspirasi', 'kategoris', 'ruangans'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'keterangan_progres' => 'nullable|string'
        ]);
        
        $aspirasi = Aspirasi::findOrFail($id);
        $statusLama = $aspirasi->status;
        $statusBaru = $request->status;
        
        // Simpan history status
        HistoryStatus::create([
            'id_aspirasi' => $id,
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'diubah_oleh' => Auth::id(),
        ]);
        
        // Update status
        $aspirasi->update(['status' => $statusBaru]);
        
        // Simpan progres jika ada keterangan
        if ($request->filled('keterangan_progres')) {
            Progres::create([
                'id_aspirasi' => $id,
                'user_id' => Auth::id(),
                'keterangan_progres' => $request->keterangan_progres,
            ]);
        }
        
        return redirect()->back()->with('success', 'Status aspirasi berhasil diupdate');
    }
    
    public function storeFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string'
        ]);
        
        Progres::create([
            'id_aspirasi' => $id,
            'user_id' => Auth::id(),
            'keterangan_progres' => 'Feedback: ' . $request->feedback,
        ]);
        
        return redirect()->back()->with('success', 'Feedback berhasil ditambahkan');
    }
    
    public function storeProgres(Request $request, $id)
    {
        $request->validate([
            'keterangan_progres' => 'required|string'
        ]);
        
        Progres::create([
            'id_aspirasi' => $id,
            'user_id' => Auth::id(),
            'keterangan_progres' => $request->keterangan_progres,
        ]);
        
        return redirect()->back()->with('success', 'Progres berhasil ditambahkan');
    }
    
    public function history()
    {
        $history = HistoryStatus::with(['aspirasi.user.siswa', 'pengubah'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('petugas.history', compact('history'));
    }
    
    public function profile()
    {
        $petugas = Auth::user()->petugas;
        return view('petugas.profile', compact('petugas'));
    }
}