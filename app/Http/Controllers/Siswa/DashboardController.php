<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        
        $aspirasiAktif = Aspirasi::where('user_id', Auth::id())
            ->where('status', '!=', 'Selesai')
            ->with('kategori')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $aspirasiSelesai = Aspirasi::where('user_id', Auth::id())
            ->where('status', 'Selesai')
            ->with('kategori')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        $totalAspirasi = Aspirasi::where('user_id', Auth::id())->count();
        $totalMenunggu = Aspirasi::where('user_id', Auth::id())->where('status', 'Menunggu')->count();
        $totalProses = Aspirasi::where('user_id', Auth::id())->where('status', 'Proses')->count();
        $totalSelesai = Aspirasi::where('user_id', Auth::id())->where('status', 'Selesai')->count();
        
        return view('siswa.dashboard', compact(
            'siswa', 
            'aspirasiAktif', 
            'aspirasiSelesai',
            'totalAspirasi',
            'totalMenunggu',
            'totalProses',
            'totalSelesai'
        ));
    }
}