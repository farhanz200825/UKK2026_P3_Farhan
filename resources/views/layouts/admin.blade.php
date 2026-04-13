<!doctype html>
<html lang="en">

<head>
    <title>@yield('title') | Aplikasi Pengaduan Sarana Sekolah</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta name="description" content="Aplikasi Pengaduan Sarana Sekolah" />
    <meta name="author" content="DashboardPack.com" />
    <meta name="theme-color" content="#1e293b" />

    <!-- [Favicon] icons -->
    <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/svg+xml" />

    <!-- [Font] Family -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/phosphor-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/tabler-icons.min.css') }}" />

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" />

    <style>
        .user-info {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #4680ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .pc-sidebar .user-info {
            transition: all 0.3s;
        }

        .pc-sidebar .user-info:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .badge-petugas {
            background: #7b1fa2;
            color: white;
        }
    </style>
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header d-flex align-items-center gap-2">
                <a href="{{ route('dashboard') }}" class="b-brand d-flex align-items-center gap-2">
                    <div class="brand-logo">
                        <i class="ph ph-buildings text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div class="brand-title">
                        <span class="fw-bold">Sekolah<span class="text-primary">Ku</span></span>
                        <small class="text-white d-block">Pengaduan Sarana</small>
                    </div>
                </a>
            </div>

            <div class="navbar-content">
                <!-- User Info -->
                <div class="user-info p-3 border-bottom border-secondary mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <!-- Foto Profil -->
                        <div class="user-avatar-wrapper">
                            @php
                            $user = Auth::user();
                            $fotoUrl = null;

                            if($user->role == 'siswa' && $user->siswa && $user->siswa->foto) {
                            $fotoUrl = asset('storage/' . $user->siswa->foto);
                            } elseif($user->role == 'guru' && $user->guru && $user->guru->foto) {
                            $fotoUrl = asset('storage/' . $user->guru->foto);
                            } elseif($user->role == 'petugas' && $user->petugas && $user->petugas->foto) {
                            $fotoUrl = asset('storage/' . $user->petugas->foto);
                            }
                            @endphp

                            @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="Profile" class="rounded-circle"
                                style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #4680ff;">
                            @else
                            <div class="user-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px; font-size: 18px;">
                                {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                            </div>
                            @endif
                        </div>

                        <div class="user-details">
                            <div class="text-white small">
                                @if($user->role == 'siswa' && $user->siswa)
                                {{ $user->siswa->nama ?? $user->email }}
                                @elseif($user->role == 'guru' && $user->guru)
                                {{ $user->guru->nama ?? $user->email }}
                                @elseif($user->role == 'petugas' && $user->petugas)
                                {{ $user->petugas->nama ?? $user->email }}
                                @else
                                {{ $user->email }}
                                @endif
                            </div>
                            <small class="text-muted">
                                @if($user->role == 'admin')
                                <span class="badge bg-primary">Administrator</span>
                                @elseif($user->role == 'guru')
                                <span class="badge bg-success">Guru</span>
                                @elseif($user->role == 'petugas')
                                <span class="badge bg-purple">Petugas</span>
                                @else
                                <span class="badge bg-info">Siswa</span>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

                <ul class="pc-navbar">

                    <!-- ==================== MENU UNTUK ADMIN ==================== -->
                    @if(Auth::user()->role == 'admin')
                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-house-line"></i></span>
                            <span class="pc-mtext">Dashboard Admin</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Manajemen Data</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-users"></i></span>
                            <span class="pc-mtext">Manajemen Pengguna</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('admin.kategori*') ? 'active' : '' }}">
                        <a href="{{ route('admin.kategori') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-tag"></i></span>
                            <span class="pc-mtext">Master Data</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('admin.pengaduan*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pengaduan') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-warning-octagon"></i></span>
                            <span class="pc-mtext">Data Aspirasi</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Lainnya</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('admin.history') ? 'active' : '' }}">
                        <a href="{{ route('admin.history') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-clock-counter-clockwise"></i></span>
                            <span class="pc-mtext">History</span>
                        </a>
                    </li>
                    @endif

                    <!-- ==================== MENU UNTUK GURU ==================== -->
                    @if(Auth::user()->role == 'guru')
                    @php $guru = Auth::user()->guru; @endphp

                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('guru.dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-house-line"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <!-- Menu untuk GURU (bisa membuat aspirasi) -->
                    @if($guru->canCreateAspirasi())
                    <li class="pc-item {{ request()->routeIs('guru.aspirasi.create') ? 'active' : '' }}">
                        <a href="{{ route('guru.aspirasi.create') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-pencil-line"></i></span>
                            <span class="pc-mtext">Buat Aspirasi</span>
                        </a>
                    </li>
                    @endif

                    <!-- Menu Data Aspirasi untuk semua yang bisa melihat -->
                    @if($guru->canViewAllAspirasi() || $guru->canCreateAspirasi())
                    <li class="pc-item {{ request()->routeIs('guru.aspirasi.index') ? 'active' : '' }}">
                        <a href="{{ route('guru.aspirasi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-list"></i></span>
                            <span class="pc-mtext">
                                @if($guru->canCreateAspirasi())
                                Daftar Aspirasi Saya
                                @else
                                Data Aspirasi
                                @endif
                            </span>
                        </a>
                    </li>
                    @endif

                    <!-- Menu untuk KEPALA SEKOLAH, WAKIL, KEPALA JURUSAN (statistik) -->
                    @if($guru->canViewStatistik())
                    <li class="pc-item {{ request()->routeIs('guru.statistik') ? 'active' : '' }}">
                        <a href="{{ route('guru.statistik') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-chart-line"></i></span>
                            <span class="pc-mtext">Statistik</span>
                        </a>
                    </li>
                    @endif

                    <!-- Menu HISTORY -->
                    <li class="pc-item {{ request()->routeIs('guru.history') ? 'active' : '' }}">
                        <a href="{{ route('guru.history') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-clock-counter-clockwise"></i></span>
                            <span class="pc-mtext">
                                @if($guru->canCreateAspirasi())
                                History Saya
                                @else
                                History Aspirasi
                                @endif
                            </span>
                        </a>
                    </li>

                    @endif

                    <!-- ==================== MENU UNTUK PETUGAS ==================== -->
                    @if(Auth::user()->role == 'petugas')
                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('petugas.dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-house-line"></i></span>
                            <span class="pc-mtext">Dashboard Petugas</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Kelola Aspirasi</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('petugas.aspirasi.index') ? 'active' : '' }}">
                        <a href="{{ route('petugas.aspirasi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-chat-circle-text"></i></span>
                            <span class="pc-mtext">Data Aspirasi</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('petugas.history') ? 'active' : '' }}">
                        <a href="{{ route('petugas.history') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-clock-counter-clockwise"></i></span>
                            <span class="pc-mtext">History Aspirasi</span>
                        </a>
                    </li>
                    @endif

                    <!-- ==================== MENU UNTUK SISWA ==================== -->
                    @if(Auth::user()->role == 'siswa')
                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('siswa.dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-house-line"></i></span>
                            <span class="pc-mtext">Dashboard Siswa</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Menu Aspirasi</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('siswa.aspirasi.create') ? 'active' : '' }}">
                        <a href="{{ route('siswa.aspirasi.create') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-pencil-line"></i></span>
                            <span class="pc-mtext">Buat Aspirasi</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('siswa.aspirasi.index') ? 'active' : '' }}">
                        <a href="{{ route('siswa.aspirasi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-list"></i></span>
                            <span class="pc-mtext">Daftar Aspirasi</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('siswa.aspirasi.status') ? 'active' : '' }}">
                        <a href="{{ route('siswa.aspirasi.status') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-chart-line"></i></span>
                            <span class="pc-mtext">Status Aspirasi</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('siswa.aspirasi.history') ? 'active' : '' }}">
                        <a href="{{ route('siswa.aspirasi.history') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-clock-counter-clockwise"></i></span>
                            <span class="pc-mtext">History Aspirasi</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ph ph-list"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ph ph-list"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none m-0 trig-drp-search"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph ph-magnifying-glass"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3 py-2">
                                <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . ." />
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph ph-sun-dim"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
                                <i class="ph ph-moon"></i> <span>Dark</span>
                            </a>
                            <a href="#!" class="dropdown-item" onclick="layout_change('light')">
                                <i class="ph ph-sun"></i> <span>Light</span>
                            </a>
                            <a href="#!" class="dropdown-item" onclick="layout_change_default()">
                                <i class="ph ph-cpu"></i> <span>Default</span>
                            </a>
                        </div>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph ph-diamonds-four"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a class="dropdown-item" href="{{ route('profile.my-account') }}">
                                <i class="ph ph-user-circle"></i> My Account
                            </a>
                            <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                <i class="ph ph-gear"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form-header">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                    <i class="ph ph-sign-out"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph ph-bell"></i>
                            @php
                            use App\Models\Aspirasi;
                            use App\Models\Progres;

                            $user = Auth::user();
                            $notifikasiCount = 0;
                            $notifikasiList = [];

                            if($user->role == 'admin') {
                            $aspirasiBaru = Aspirasi::where('status', 'Menunggu')->count();
                            $notifikasiCount += $aspirasiBaru;
                            foreach(Aspirasi::with('user.siswa')->where('status', 'Menunggu')->latest()->take(5)->get() as $asp) {
                            $notifikasiList[] = [
                            'type' => 'new',
                            'title' => 'Aspirasi Baru',
                            'message' => 'Dari: ' . ($asp->user->siswa->nama ?? $asp->user->email),
                            'time' => $asp->created_at->diffForHumans(),
                            'link' => route('admin.pengaduan.detail', $asp->id_aspirasi)
                            ];
                            }
                            } elseif($user->role == 'guru') {
                            $aspirasiBaru = Aspirasi::where('status', 'Menunggu')->count();
                            $notifikasiCount += $aspirasiBaru;
                            foreach(Aspirasi::with('user.siswa')->where('status', 'Menunggu')->latest()->take(5)->get() as $asp) {
                            $notifikasiList[] = [
                            'type' => 'new',
                            'title' => 'Aspirasi Baru',
                            'message' => 'Dari: ' . ($asp->user->siswa->nama ?? $asp->user->email),
                            'time' => $asp->created_at->diffForHumans(),
                            'link' => route('guru.aspirasi.detail', $asp->id_aspirasi)
                            ];
                            }
                            } elseif($user->role == 'petugas') {
                            $aspirasiBaru = Aspirasi::where('status', 'Menunggu')->count();
                            $notifikasiCount += $aspirasiBaru;
                            foreach(Aspirasi::with('user.siswa')->where('status', 'Menunggu')->latest()->take(5)->get() as $asp) {
                            $notifikasiList[] = [
                            'type' => 'new',
                            'title' => 'Aspirasi Baru',
                            'message' => 'Dari: ' . ($asp->user->siswa->nama ?? $asp->user->email),
                            'time' => $asp->created_at->diffForHumans(),
                            'link' => route('petugas.aspirasi.detail', $asp->id_aspirasi)
                            ];
                            }
                            } elseif($user->role == 'siswa') {
                            $progresBaru = Progres::whereHas('aspirasi', function($q) use ($user) {
                            $q->where('user_id', $user->id);
                            })->where('created_at', '>=', now()->subDays(7))->count();
                            $notifikasiCount += $progresBaru;
                            $aspirasiIds = Aspirasi::where('user_id', $user->id)->pluck('id_aspirasi');
                            foreach(Progres::with('user')->whereIn('id_aspirasi', $aspirasiIds)->latest()->take(5)->get() as $prog) {
                            $isFeedback = str_contains($prog->keterangan_progres, 'Feedback:');
                            $notifikasiList[] = [
                            'type' => $isFeedback ? 'feedback' : 'progress',
                            'title' => $isFeedback ? 'Feedback Baru' : 'Update Progres',
                            'message' => $isFeedback ? str_replace('Feedback: ', '', substr($prog->keterangan_progres, 0, 50)) : substr($prog->keterangan_progres, 0, 50),
                            'time' => $prog->created_at->diffForHumans(),
                            'link' => route('siswa.aspirasi.detail', $prog->id_aspirasi)
                            ];
                            }
                            }
                            @endphp
                            <span class="badge bg-success pc-h-badge">{{ $notifikasiCount > 0 ? $notifikasiCount : '' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown" style="min-width: 320px;">
                            <div class="dropdown-header d-flex align-items-center justify-content-between">
                                <h5 class="m-0">Notifikasi</h5>
                                @if($notifikasiCount > 0)
                                <a href="#" class="btn btn-link btn-sm" id="markAllRead">Tandai dibaca</a>
                                @endif
                            </div>
                            <div class="dropdown-body text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
                                @if(count($notifikasiList) > 0)
                                @foreach($notifikasiList as $notif)
                                <a href="{{ $notif['link'] }}" class="text-decoration-none">
                                    <div class="card bg-transparent mb-2 border-0 notification-item">
                                        <div class="card-body p-3 rounded" style="background: rgba(var(--bs-light-rgb), 0.3); transition: all 0.2s ease;" onmouseover="this.style.background='rgba(var(--bs-primary-rgb), 0.05)'" onmouseout="this.style.background='rgba(var(--bs-light-rgb), 0.3)'">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(13, 110, 253, 0.1);">
                                                        @if($notif['type'] == 'new')
                                                        <i class="ph ph-warning-octagon text-warning" style="font-size: 20px;"></i>
                                                        @elseif($notif['type'] == 'feedback')
                                                        <i class="ph ph-chat-dots text-primary" style="font-size: 20px;"></i>
                                                        @else
                                                        <i class="ph ph-progress text-success" style="font-size: 20px;"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="text-body mb-1">{{ $notif['title'] }}</h6>
                                                        <small class="text-muted">{{ $notif['time'] }}</small>
                                                    </div>
                                                    <p class="mb-0 text-muted small">{{ Str::limit($notif['message'], 60) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                                @else
                                <div class="text-center py-4">
                                    <i class="ph ph-bell-slash" style="font-size: 48px; color: #ccc;"></i>
                                    <p class="mt-2 text-muted mb-0">Tidak ada notifikasi baru</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph ph-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph ph-x-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <script>
        // Notifikasi real-time (refresh setiap 30 detik)
        function checkNewNotifications() {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newBadge = doc.querySelector('.pc-h-badge');
                    const currentBadge = document.querySelector('.pc-h-badge');

                    if (newBadge && currentBadge) {
                        const newCount = newBadge.innerText;
                        const currentCount = currentBadge.innerText;
                        if (newCount !== currentCount) {
                            location.reload();
                        }
                    }
                })
                .catch(error => console.log('Error checking notifications:', error));
        }

        setInterval(checkNewNotifications, 30000);

        document.getElementById('markAllRead')?.addEventListener('click', function(e) {
            e.preventDefault();
            localStorage.setItem('notifications_read', Date.now());
            const badge = document.querySelector('.pc-h-badge');
            if (badge) badge.innerText = '';
            location.reload();
        });
    </script>

    <!-- Required JS -->
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>

    <script>
        layout_change('light');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
        layout_theme_sidebar_change('false');
    </script>
</body>

</html>