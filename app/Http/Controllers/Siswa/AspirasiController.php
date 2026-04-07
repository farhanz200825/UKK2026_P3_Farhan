<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AspirasiController extends Controller
{
    public function create()
    {
        $kategoris = Kategori::all();
        return view('siswa.aspirasi.create', compact('kategoris'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'judul' => 'required|max:200',
            'isi' => 'required',
        ]);
        
        Aspirasi::create([
            'user_id' => auth()->id(),
            'id_kategori' => $request->id_kategori,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'status' => 'Menunggu',
        ]);
        
        return redirect()->route('siswa.aspirasi.status')->with('success', 'Aspirasi berhasil dikirim');
    }
    
    public function status()
    {
        $aspirasi = Aspirasi::with('kategori')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('siswa.aspirasi.status', compact('aspirasi'));
    }
    
    public function detail($id)
    {
        $aspirasi = Aspirasi::with(['kategori', 'progres.user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        return view('siswa.aspirasi.detail', compact('aspirasi'));
    }
    
    public function history()
    {
        $aspirasi = Aspirasi::with('kategori')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('siswa.aspirasi.history', compact('aspirasi'));
    }
    
    public function feedback()
    {
        $aspirasi = Aspirasi::with(['kategori', 'progres' => function($q) {
            $q->where('keterangan_progres', 'like', 'Feedback:%');
        }])
        ->where('user_id', auth()->id())
        ->has('progres')
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        return view('siswa.aspirasi.feedback', compact('aspirasi'));
    }
}