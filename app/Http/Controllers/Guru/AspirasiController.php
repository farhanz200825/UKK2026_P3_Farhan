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

        // Aspirasi terbaru (5 terakhir) berdasarkan jabatan
        if ($guru->canCreateAspirasi()) {
            // GURU: hanya lihat aspirasi yang dia buat sendiri
            $aspirasiTerbaru = Aspirasi::with(['kategori', 'ruangan'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } elseif ($guru->jabatan == 'Wali Kelas') {
            // WALI KELAS: hanya lihat aspirasi dari kelas yang dia wali
            $kelasId = $guru->getKelasId();

            if ($kelasId) {
                $aspirasiTerbaru = Aspirasi::with(['user.siswa', 'kategori', 'ruangan'])
                    ->whereHas('user.siswa', function ($q) use ($kelasId) {
                        $q->where('id_kelas', $kelasId);
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            } else {
                $aspirasiTerbaru = collect();
            }
        } elseif ($guru->canViewAllAspirasi()) {
            // KEPALA SEKOLAH, WAKIL, KEPALA JURUSAN: lihat semua aspirasi
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

    // DATA ASPIRASI - Berdasarkan jabatan
    public function index(Request $request)
    {
        $guru = $this->getGuru();

        if ($guru->canCreateAspirasi() && !$guru->canManageAspirasi()) {
            // GURU BIASA: hanya lihat aspirasi yang dia buat sendiri
            $query = Aspirasi::with(['kategori', 'ruangan'])
                ->where('user_id', Auth::id())
                ->where('status', '!=', 'Selesai');
        } elseif ($guru->jabatan == 'Wali Kelas') {
            // WALI KELAS: bisa lihat aspirasi sendiri dan aspirasi kelas
            // Tentukan jenis tampilan berdasarkan parameter 'type'
            $type = $request->get('type', 'kelas');

            if ($type == 'saya') {
                // Aspirasi yang dibuat Wali Kelas sendiri
                $query = Aspirasi::with(['kategori', 'ruangan'])
                    ->where('user_id', Auth::id())
                    ->where('status', '!=', 'Selesai');
            } else {
                // Aspirasi dari kelas yang diampu
                $kelasId = $guru->getKelasId();
                if ($kelasId) {
                    $query = Aspirasi::with(['user.siswa', 'kategori', 'ruangan'])
                        ->where('status', '!=', 'Selesai')
                        ->whereHas('user.siswa', function ($q) use ($kelasId) {
                            $q->where('id_kelas', $kelasId);
                        });
                } else {
                    $query = Aspirasi::whereRaw('1 = 0');
                }
            }
        } elseif ($guru->canViewAllAspirasi()) {
            // KEPALA SEKOLAH, WAKIL, KEPALA JURUSAN: lihat semua aspirasi
            $query = Aspirasi::with(['user.siswa', 'user.guru', 'kategori', 'ruangan'])
                ->where('status', '!=', 'Selesai');
        } else {
            $query = Aspirasi::whereRaw('1 = 0');
        }

        // Filter (sama seperti sebelumnya)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->filled('ruangan')) {
            $query->where('id_ruangan', $request->ruangan);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
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
        $currentType = $request->get('type', 'kelas');

        return view('guru.aspirasi.index', compact('guru', 'aspirasi', 'statistik', 'kategoris', 'ruangans', 'currentType'));
    }

    // Form buat aspirasi (khusus Guru dan Wali Kelas)
    public function create()
    {
        $guru = $this->getGuru();

        if (!$guru->canCreateAspirasi()) {
            abort(403, 'Hanya Guru dan Wali Kelas yang dapat membuat aspirasi');
        }

        $kategoris = Kategori::all();
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        return view('guru.aspirasi.create', compact('guru', 'kategoris', 'ruangans'));
    }

    // Store aspirasi (khusus Guru dan Wali Kelas)
    public function store(Request $request)
    {
        $guru = $this->getGuru();

        if (!$guru->canCreateAspirasi()) {
            return redirect()->back()->with('error', 'Hanya Guru dan Wali Kelas yang dapat membuat aspirasi');
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
    // History - Menampilkan riwayat perubahan status
    public function history(Request $request)
    {
        $guru = $this->getGuru();

        if ($guru->canCreateAspirasi() && !$guru->canManageAspirasi()) {
            // GURU BIASA: hanya melihat history aspirasi yang dia buat sendiri
            $query = HistoryStatus::with(['aspirasi.user.siswa', 'aspirasi.kategori', 'aspirasi.ruangan', 'pengubah'])
                ->where('status_baru', 'Selesai')
                ->whereHas('aspirasi', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            $currentType = 'saya';
        } elseif ($guru->jabatan == 'Wali Kelas') {
            // WALI KELAS: bisa lihat history sendiri dan history kelas
            $type = $request->get('type', 'kelas');
            $currentType = $type;

            if ($type == 'saya') {
                // History aspirasi yang dibuat Wali Kelas sendiri
                $query = HistoryStatus::with(['aspirasi.user.siswa', 'aspirasi.kategori', 'aspirasi.ruangan', 'pengubah'])
                    ->where('status_baru', 'Selesai')
                    ->whereHas('aspirasi', function ($q) {
                        $q->where('user_id', Auth::id());
                    });
            } else {
                // History aspirasi dari kelas yang diampu
                $kelasId = $guru->getKelasId();
                if ($kelasId) {
                    $query = HistoryStatus::with(['aspirasi.user.siswa', 'aspirasi.kategori', 'aspirasi.ruangan', 'pengubah'])
                        ->where('status_baru', 'Selesai')
                        ->whereHas('aspirasi.user.siswa', function ($q) use ($kelasId) {
                            $q->where('id_kelas', $kelasId);
                        });
                } else {
                    $query = HistoryStatus::whereRaw('1 = 0');
                }
            }
        } elseif ($guru->canViewAllAspirasi()) {
            // KEPALA SEKOLAH, WAKIL, KEPALA JURUSAN: melihat semua history
            $query = HistoryStatus::with(['aspirasi.user.siswa', 'aspirasi.user.guru', 'aspirasi.kategori', 'aspirasi.ruangan', 'pengubah'])
                ->where('status_baru', 'Selesai');
            $currentType = 'semua';
        } else {
            $query = HistoryStatus::whereRaw('1 = 0');
            $currentType = 'semua';
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $history = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('guru.history', compact('guru', 'history', 'currentType'));
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
