<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Progres;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Aspirasi::with(['user.siswa', 'kategori']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $aspirasi = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('guru.aspirasi.index', compact('aspirasi'));
    }
    
    public function detail($id)
    {
        $aspirasi = Aspirasi::with(['user.siswa', 'kategori', 'progres.user'])->findOrFail($id);
        return view('guru.aspirasi.detail', compact('aspirasi'));
    }
    
    public function storeFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required'
        ]);
        
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
    
    public function history()
    {
        $aspirasi = Aspirasi::with(['user.siswa', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('guru.aspirasi.history', compact('aspirasi'));
    }
}