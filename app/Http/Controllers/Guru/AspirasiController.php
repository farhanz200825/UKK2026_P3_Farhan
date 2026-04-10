<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Progres;
use App\Models\HistoryStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AspirasiController extends Controller
{
    // Menampilkan semua aspirasi yang belum selesai
    public function index(Request $request)
    {
        $query = Aspirasi::with(['user.siswa', 'kategori'])
            ->where('status', '!=', 'Selesai');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('lokasi', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }
        
        $aspirasi = $query->orderBy('created_at', 'desc')->paginate(10);
        $kategoris = \App\Models\Kategori::all();
        
        return view('guru.aspirasi.index', compact('aspirasi', 'kategoris'));
    }
    
    // History aspirasi (status selesai)
    public function history(Request $request)
    {
        $query = Aspirasi::with(['user.siswa', 'kategori'])
            ->where('status', 'Selesai');
        
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('lokasi', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }
        
        $aspirasi = $query->orderBy('updated_at', 'desc')->paginate(10);
        $kategoris = \App\Models\Kategori::all();
        
        return view('guru.aspirasi.history', compact('aspirasi', 'kategoris'));
    }
    
    // Detail aspirasi
    public function detail($id)
    {
        $aspirasi = Aspirasi::with(['user.siswa', 'kategori', 'progres.user', 'historyStatus.user'])
            ->findOrFail($id);
            
        return view('guru.aspirasi.detail', compact('aspirasi'));
    }
    
    // Update status menjadi Proses (hanya dari update progres)
    public function updateStatusToProses($id)
    {
        $aspirasi = Aspirasi::findOrFail($id);
        
        if ($aspirasi->status == 'Menunggu') {
            $statusLama = $aspirasi->status;
            $statusBaru = 'Proses';
            
            HistoryStatus::create([
                'id_aspirasi' => $id,
                'status_lama' => $statusLama,
                'status_baru' => $statusBaru,
                'diubah_oleh' => auth()->id(),
            ]);
            
            $aspirasi->update(['status' => $statusBaru]);
            
            return true;
        }
        
        return false;
    }
    
    // Update status menjadi Selesai
    public function updateStatusToSelesai($id)
    {
        $aspirasi = Aspirasi::findOrFail($id);
        
        if ($aspirasi->status != 'Selesai') {
            $statusLama = $aspirasi->status;
            $statusBaru = 'Selesai';
            
            HistoryStatus::create([
                'id_aspirasi' => $id,
                'status_lama' => $statusLama,
                'status_baru' => $statusBaru,
                'diubah_oleh' => auth()->id(),
            ]);
            
            $aspirasi->update(['status' => $statusBaru]);
            
            return redirect()->route('guru.aspirasi.index')
                ->with('success', 'Aspirasi telah selesai dan dipindahkan ke history');
        }
        
        return redirect()->back()->with('error', 'Gagal mengupdate status');
    }
    
    // Tambah progres (akan mengubah status ke Proses jika masih Menunggu)
    public function storeProgres(Request $request, $id)
    {
        $request->validate([
            'keterangan_progres' => 'required|string'
        ]);
        
        // Update status ke Proses jika masih Menunggu
        $this->updateStatusToProses($id);
        
        // Simpan progres
        Progres::create([
            'id_aspirasi' => $id,
            'user_id' => auth()->id(),
            'keterangan_progres' => $request->keterangan_progres,
        ]);
        
        return redirect()->back()->with('success', 'Progres berhasil ditambahkan dan status berubah menjadi Proses');
    }
    
    // Tambah feedback (TIDAK mengubah status)
    public function storeFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string'
        ]);
        
        // Simpan feedback tanpa mengubah status
        Progres::create([
            'id_aspirasi' => $id,
            'user_id' => auth()->id(),
            'keterangan_progres' => 'Feedback: ' . $request->feedback,
        ]);
        
        return redirect()->back()->with('success', 'Feedback berhasil ditambahkan');
    }
    
    // Hapus aspirasi
    public function destroy($id)
    {
        try {
            $aspirasi = Aspirasi::findOrFail($id);
            
            // Hapus foto jika ada
            if ($aspirasi->foto && Storage::disk('public')->exists($aspirasi->foto)) {
                Storage::disk('public')->delete($aspirasi->foto);
            }
            
            // Hapus progres terkait
            Progres::where('id_aspirasi', $id)->delete();
            
            // Hapus history status terkait
            HistoryStatus::where('id_aspirasi', $id)->delete();
            
            // Hapus aspirasi
            $aspirasi->delete();
            
            return redirect()->back()->with('success', 'Aspirasi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus aspirasi: ' . $e->getMessage());
        }
    }
}