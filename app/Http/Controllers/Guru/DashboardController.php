<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAspirasi = Aspirasi::count();
        $aspirasiMenunggu = Aspirasi::where('status', 'Menunggu')->count();
        $aspirasiProses = Aspirasi::where('status', 'Proses')->count();
        
        return view('guru.dashboard', compact('totalAspirasi', 'aspirasiMenunggu', 'aspirasiProses'));
    }
}