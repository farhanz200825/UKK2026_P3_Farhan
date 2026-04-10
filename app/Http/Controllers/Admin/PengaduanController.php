<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Progres;
use App\Models\HistoryStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function index()
    {
        $aspirasi = Aspirasi::with(['siswa', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.pengaduan.index', compact('aspirasi'));
    }
    
    public function detail($id)
    {
        $aspirasi = Aspirasi::with([
            'siswa', 
            'kategori', 
            'historyStatus.pengubah', 
            'progres.user'
        ])->findOrFail($id);
        
        return view('admin.pengaduan.detail', compact('aspirasi'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'keterangan_progres' => 'nullable|string'
        ]);
        
        $aspirasi = Aspirasi::findOrFail($id);
        $status_lama = $aspirasi->status;
        $status_baru = $request->status;
        
        // Update status
        $aspirasi->status = $status_baru;
        $aspirasi->save();
        
        // Save to history_status
        HistoryStatus::create([
            'id_aspirasi' => $id,
            'status_lama' => $status_lama,
            'status_baru' => $status_baru,
            'diubah_oleh' => Auth::id()
        ]);
        
        // Save progres if there's keterangan
        if ($request->filled('keterangan_progres')) {
            Progres::create([
                'id_aspirasi' => $id,
                'user_id' => Auth::id(),
                'keterangan_progres' => $request->keterangan_progres
            ]);
        }
        
        return redirect()->route('admin.pengaduan.detail', $id)
            ->with('success', 'Status berhasil diupdate');
    }
    
    public function destroy($id)
    {
        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->delete();
        
        return redirect()->route('admin.pengaduan.index')
            ->with('success', 'Data aspirasi berhasil dihapus');
    }
}