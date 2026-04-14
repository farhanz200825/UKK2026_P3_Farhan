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
    // DASHBOARD - Menampilkan ringkasan statistik
    public function dashboard()
    {
        $guru = $this->getGuru();

        // Statistik
        $statistik = [
            'total' => Aspirasi::count(),
            'menunggu' => Aspirasi::where('status', 'Menunggu')->count(),
            'proses' => Aspirasi::where('status', 'Proses')->count(),
            'selesai' => Aspirasi::where('status', 'Selesai')->count(),
        ];

        // Aspirasi terbaru (5 terakhir)
        if ($guru->canCreateAspirasi()) {
            $aspirasiTerbaru = Aspirasi::with(['kategori', 'ruangan'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } elseif ($guru->canViewAllAspirasi()) {
            $aspirasiTerbaru = Aspirasi::with(['user.siswa', 'user.guru', 'kategori', 'ruangan'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } else {
            $aspirasiTerbaru = collect();
        }

        // Data untuk grafik - ambil 6 bulan terakhir
        $bulanLabels = [];
        $bulanData = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $bulanLabels[] = $bulan->format('M Y');

            $count = Aspirasi::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
            $bulanData[] = $count;
        }

        // Debug: cek data ke log
        \Log::info('Grafik Data:', ['labels' => $bulanLabels, 'data' => $bulanData]);

        return view('guru.dashboard', compact('guru', 'statistik', 'aspirasiTerbaru', 'bulanLabels', 'bulanData'));
    }
}
