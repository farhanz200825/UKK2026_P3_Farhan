<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAspirasi = Aspirasi::where('user_id', auth()->id())->count();
        $aspirasiMenunggu = Aspirasi::where('user_id', auth()->id())->where('status', 'Menunggu')->count();
        $aspirasiSelesai = Aspirasi::where('user_id', auth()->id())->where('status', 'Selesai')->count();
        
        return view('siswa.dashboard', compact('totalAspirasi', 'aspirasiMenunggu', 'aspirasiSelesai'));
    }
}