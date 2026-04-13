<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\Progres;
use App\Models\HistoryStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AspirasiController extends Controller
{
    private function getGuru()
    {
        return Auth::user()->guru;
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
            // GURU: hanya lihat aspirasi yang dia buat sendiri
            $aspirasiTerbaru = Aspirasi::with(['kategori', 'ruangan'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } elseif ($guru->canViewAllAspirasi()) {
            // KEPALA SEKOLAH, WAKIL, KEPALA JURUSAN, WALI KELAS: lihat semua aspirasi
            $aspirasiTerbaru = Aspirasi::with(['user.siswa', 'user.guru', 'kategori', 'ruangan'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } else {
            $aspirasiTerbaru = collect();
        }

        // Statistik per bulan (6 bulan terakhir)
        $bulanLabels = [];
        $bulanData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $bulanLabels[] = $bulan->format('M Y');
            $bulanData[] = Aspirasi::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }

        return view('guru.dashboard', compact('guru', 'statistik', 'aspirasiTerbaru', 'bulanLabels', 'bulanData'));
    }

    // DATA ASPIRASI - Menampilkan semua aspirasi (index)
    // DATA ASPIRASI - Hanya menampilkan yang belum selesai (Menunggu dan Proses)
    public function index(Request $request)
    {
        $guru = $this->getGuru();

        if ($guru->canCreateAspirasi()) {
            // GURU: hanya lihat aspirasi yang dia buat sendiri (belum selesai)
            $query = Aspirasi::with(['kategori', 'ruangan'])
                ->where('user_id', Auth::id())
                ->where('status', '!=', 'Selesai');
        } elseif ($guru->canViewAllAspirasi()) {
            // WALI KELAS, KEPALA SEKOLAH, WAKIL, KEPALA JURUSAN: lihat semua aspirasi (belum selesai)
            $query = Aspirasi::with(['user.siswa', 'user.guru', 'kategori', 'ruangan'])
                ->where('status', '!=', 'Selesai');
        } else {
            $query = Aspirasi::whereRaw('1 = 0'); // Query kosong
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan')) {
            $query->where('id_ruangan', $request->ruangan);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('keterangan', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        $aspirasi = $query->orderBy('created_at', 'desc')->paginate(10);

        $statistik = [
            'total' => Aspirasi::count(),
            'menunggu' => Aspirasi::where('status', 'Menunggu')->count(),
            'proses' => Aspirasi::where('status', 'Proses')->count(),
            'selesai' => Aspirasi::where('status', 'Selesai')->count(),
        ];

        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();

        return view('guru.aspirasi.index', compact('guru', 'aspirasi', 'statistik', 'kategoris', 'ruangans'));
    }

    // Form buat aspirasi (khusus Guru)
    public function create()
    {
        $guru = $this->getGuru();

        if (!$guru->canCreateAspirasi()) {
            abort(403, 'Hanya Guru yang dapat membuat aspirasi');
        }

        $kategoris = Kategori::all();
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        return view('guru.aspirasi.create', compact('guru', 'kategoris', 'ruangans'));
    }

    // Store aspirasi (khusus Guru)
    public function store(Request $request)
    {
        $guru = $this->getGuru();

        if (!$guru->canCreateAspirasi()) {
            return redirect()->back()->with('error', 'Hanya Guru yang dapat membuat aspirasi');
        }

        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_ruangan' => 'required|exists:ruangan,id_ruangan',
            'keterangan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $ruangan = Ruangan::find($request->id_ruangan);
        $lokasi = $ruangan->nama_ruangan . ' (' . $ruangan->kode_ruangan . ')';

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('aspirasi_foto', 'public');
        }

        Aspirasi::create([
            'user_id' => Auth::id(),
            'id_kategori' => $request->id_kategori,
            'id_ruangan' => $request->id_ruangan,
            'lokasi' => $lokasi,
            'keterangan' => $request->keterangan,
            'foto' => $fotoPath,
            'status' => 'Menunggu'
        ]);

        return redirect()->route('guru.aspirasi.index')
            ->with('success', 'Aspirasi berhasil dikirim');
    }

    // Detail aspirasi
    public function detail($id)
    {
        $guru = $this->getGuru();

        if ($guru->canCreateAspirasi()) {
            // GURU: hanya bisa lihat aspirasi miliknya sendiri
            $aspirasi = Aspirasi::with(['kategori', 'ruangan', 'progres.user', 'historyStatus.pengubah'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);
        } elseif ($guru->canViewAllAspirasi()) {
            // KEPALA SEKOLAH, WAKIL, KEPALA JURUSAN, WALI KELAS: bisa lihat semua
            $aspirasi = Aspirasi::with(['user.siswa', 'user.guru', 'kategori', 'ruangan', 'progres.user', 'historyStatus.pengubah'])
                ->findOrFail($id);
        } else {
            abort(403, 'Anda tidak memiliki akses');
        }

        $kategoris = Kategori::all();

        return view('guru.aspirasi.detail', compact('guru', 'aspirasi', 'kategoris'));
    }

    // Store Feedback (Hanya untuk Wali Kelas)
    public function storeFeedback(Request $request, $id)
    {
        $guru = $this->getGuru();

        if (!$guru->canManageAspirasi()) {
            return redirect()->back()->with('error', 'Hanya Wali Kelas yang dapat memberi feedback');
        }

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

    // Store Progres (Hanya untuk Wali Kelas)
    public function storeProgres(Request $request, $id)
    {
        $guru = $this->getGuru();

        if (!$guru->canManageAspirasi()) {
            return redirect()->back()->with('error', 'Hanya Wali Kelas yang dapat update progres');
        }

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

    // Update Status (Hanya untuk Wali Kelas)
    public function updateStatus(Request $request, $id)
    {
        $guru = $this->getGuru();

        if (!$guru->canChangeStatus()) {
            return redirect()->back()->with('error', 'Hanya Wali Kelas yang dapat mengubah status');
        }

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

        // Jika status menjadi Selesai, tambahkan progres otomatis
        if ($statusBaru == 'Selesai') {
            Progres::create([
                'id_aspirasi' => $id,
                'user_id' => Auth::id(),
                'keterangan_progres' => 'Aspirasi telah selesai ditangani oleh Wali Kelas ' . $guru->nama,
            ]);
        }

        $message = $statusBaru == 'Selesai'
            ? 'Aspirasi telah selesai dan masuk ke history'
            : 'Status berhasil diupdate';

        return redirect()->back()->with('success', $message);
    }
    // History
    // History - Hanya menampilkan riwayat yang status_baru = Selesai
    public function history(Request $request)
    {
        $guru = $this->getGuru();

        if ($guru->canCreateAspirasi()) {
            // GURU: hanya melihat history aspirasi yang dia buat sendiri (yang sudah selesai)
            $query = HistoryStatus::with(['aspirasi.user.siswa', 'aspirasi.kategori', 'aspirasi.ruangan', 'pengubah'])
                ->where('status_baru', 'Selesai') // HANYA yang statusnya menjadi Selesai
                ->whereHas('aspirasi', function ($q) {
                    $q->where('user_id', Auth::id());
                });
        } else {
            // WALI KELAS, KEPALA SEKOLAH, WAKIL, KEPALA JURUSAN: melihat semua history yang selesai
            $query = HistoryStatus::with(['aspirasi.user.siswa', 'aspirasi.user.guru', 'aspirasi.kategori', 'aspirasi.ruangan', 'pengubah'])
                ->where('status_baru', 'Selesai'); // HANYA yang statusnya menjadi Selesai
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $history = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('guru.history', compact('guru', 'history'));
    }
    // Statistik (Untuk Kepala Sekolah, Wakil, Kepala Jurusan)
    public function statistik()
    {
        $guru = $this->getGuru();

        if (!$guru->canViewStatistik()) {
            return redirect()->route('guru.aspirasi.index')->with('error', 'Anda tidak memiliki akses ke halaman statistik');
        }

        $bulanLabels = [];
        $bulanData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $bulanLabels[] = $bulan->format('M Y');
            $bulanData[] = Aspirasi::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }

        $kategoriStat = Kategori::withCount('aspirasi')->get();
        $ruanganStat = Ruangan::withCount('aspirasi')->get();
        $statusStat = [
            'Menunggu' => Aspirasi::where('status', 'Menunggu')->count(),
            'Proses' => Aspirasi::where('status', 'Proses')->count(),
            'Selesai' => Aspirasi::where('status', 'Selesai')->count(),
        ];

        return view('guru.statistik', compact('guru', 'bulanLabels', 'bulanData', 'kategoriStat', 'ruanganStat', 'statusStat'));
    }
}
