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
</head>
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
</style>

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
                        <div class="user-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; font-size: 18px;">
                            {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <div class="text-white small">{{ Auth::user()->email }}</div>
                            <small class="text-muted">
                                @if(Auth::user()->role == 'admin')
                                <span class="badge bg-primary">Administrator</span>
                                @elseif(Auth::user()->role == 'guru')
                                <span class="badge bg-success">Guru</span>
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
                            <span class="pc-micon">
                                <i class="ph ph-house-line"></i>
                            </span>
                            <span class="pc-mtext">Dashboard Admin</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Manajemen Data</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-users"></i>
                            </span>
                            <span class="pc-mtext">Manajemen Pengguna</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('admin.pengaduan*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pengaduan') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-warning-octagon"></i>
                            </span>
                            <span class="pc-mtext">Data Pengaduan</span>
                        </a>
                    </li>
                    @endif

                    <!-- ==================== MENU UNTUK GURU ==================== -->
                    @if(Auth::user()->role == 'guru')
                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('guru.dashboard') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-house-line"></i>
                            </span>
                            <span class="pc-mtext">Dashboard Guru</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Kelola Aspirasi</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('guru.aspirasi.index') ? 'active' : '' }}">
                        <a href="" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-chat-circle-text"></i>
                            </span>
                            <span class="pc-mtext">Data Aspirasi</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('guru.history') ? 'active' : '' }}">
                        <a href="" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-clock-counter-clockwise"></i>
                            </span>
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
                            <span class="pc-micon">
                                <i class="ph ph-house-line"></i>
                            </span>
                            <span class="pc-mtext">Dashboard Siswa</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Menu Aspirasi</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('siswa.aspirasi.create') ? 'active' : '' }}">
                        <a href="{{ route('siswa.aspirasi.create') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-pencil-line"></i>
                            </span>
                            <span class="pc-mtext">Buat Aspirasi</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('siswa.aspirasi.status') ? 'active' : '' }}">
                        <a href="{{ route('siswa.aspirasi.status') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-chart-line"></i>
                            </span>
                            <span class="pc-mtext">Status Aspirasi</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('siswa.aspirasi.history') ? 'active' : '' }}">
                        <a href="{{ route('siswa.aspirasi.history') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-clock-counter-clockwise"></i>
                            </span>
                            <span class="pc-mtext">History Aspirasi</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('siswa.aspirasi.feedback') ? 'active' : '' }}">
                        <a href="{{ route('siswa.aspirasi.feedback') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-chat-dots"></i>
                            </span>
                            <span class="pc-mtext">Feedback</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
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
                        <a
                            class="pc-head-link dropdown-toggle arrow-none m-0 trig-drp-search"
                            data-bs-toggle="dropdown"
                            href="#"
                            role="button"
                            aria-haspopup="false"
                            aria-expanded="false">
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
            <!-- [Mobile Media Block end] -->
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a
                            class="pc-head-link dropdown-toggle arrow-none me-0"
                            data-bs-toggle="dropdown"
                            href="#"
                            role="button"
                            aria-haspopup="false"
                            aria-expanded="false">
                            <i class="ph ph-sun-dim"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
                                <i class="ph ph-moon"></i>
                                <span>Dark</span>
                            </a>
                            <a href="#!" class="dropdown-item" onclick="layout_change('light')">
                                <i class="ph ph-sun"></i>
                                <span>Light</span>
                            </a>
                            <a href="#!" class="dropdown-item" onclick="layout_change_default()">
                                <i class="ph ph-cpu"></i>
                                <span>Default</span>
                            </a>
                        </div>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a
                            class="pc-head-link dropdown-toggle arrow-none me-0"
                            data-bs-toggle="dropdown"
                            href="#"
                            role="button"
                            aria-haspopup="false"
                            aria-expanded="false">
                            <i class="ph ph-diamonds-four"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a class="dropdown-item" href="">
                                <i class="ph ph-user-circle"></i> My Account
                            </a>
                            <a class="dropdown-item" href="">
                                <i class="ph ph-gear"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <!-- Logout dengan form -->
                            <form action="{{ route('logout') }}" method="POST" id="logout-form-header">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                    <i class="ph ph-sign-out"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a
                            class="pc-head-link dropdown-toggle arrow-none me-0"
                            data-bs-toggle="dropdown"
                            href="#"
                            role="button"
                            aria-haspopup="false"
                            aria-expanded="false">
                            <i class="ph ph-bell"></i>
                            <span class="badge bg-success pc-h-badge">5</span>
                        </a>
                        <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header d-flex align-items-center justify-content-between">
                                <h5 class="m-0">Notifications</h5>
                                <a href="#!" class="btn btn-link btn-sm">Mark all read</a>
                            </div>
                            <div class="dropdown-body text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
                                <p class="text-span">Today</p>
                                <div class="card bg-transparent mb-2 border-0">
                                    <div class="card-body p-3 rounded" style="background: rgba(var(--bs-light-rgb), 0.3); transition: all 0.2s ease;" onmouseover="this.style.background='rgba(var(--bs-primary-rgb), 0.05)'" onmouseout="this.style.background='rgba(var(--bs-light-rgb), 0.3)'">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="ph ph-credit-card text-success" style="font-size: 16px;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <span class="float-end text-sm text-muted">2 min ago</span>
                                                <h5 class="text-body mb-2">Payment Received</h5>
                                                <p class="mb-0">$2,499.00 payment received for Pro Plan subscription from Acme Corp</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card bg-transparent mb-2 border-0">
                                    <div class="card-body p-3 rounded" style="background: rgba(var(--bs-light-rgb), 0.3); transition: all 0.2s ease;" onmouseover="this.style.background='rgba(var(--bs-primary-rgb), 0.05)'" onmouseout="this.style.background='rgba(var(--bs-light-rgb), 0.3)'">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="ph ph-users text-primary" style="font-size: 16px;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <span class="float-end text-sm text-muted">1 hour ago</span>
                                                <h5 class="text-body mb-2">New Team Member</h5>
                                                <p class="mb-0">Sarah Johnson joined your workspace and was assigned to the Marketing team</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card bg-transparent mb-2 border-0">
                                    <div class="card-body p-3 rounded" style="background: rgba(var(--bs-light-rgb), 0.3); transition: all 0.2s ease;" onmouseover="this.style.background='rgba(var(--bs-primary-rgb), 0.05)'" onmouseout="this.style.background='rgba(var(--bs-light-rgb), 0.3)'">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="ph ph-chart-line text-warning" style="font-size: 16px;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <span class="float-end text-sm text-muted">3 hours ago</span>
                                                <h5 class="text-body mb-2">Monthly Report Ready</h5>
                                                <p class="mb-0">Your January 2025 analytics report is ready. Revenue up 24% vs last month</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-span">Yesterday</p>
                                <div class="card bg-transparent mb-2 border-0">
                                    <div class="card-body p-3 rounded" style="background: rgba(var(--bs-light-rgb), 0.3); transition: all 0.2s ease;" onmouseover="this.style.background='rgba(var(--bs-primary-rgb), 0.05)'" onmouseout="this.style.background='rgba(var(--bs-light-rgb), 0.3)'">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="ph ph-shield-check text-danger" style="font-size: 16px;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <span class="float-end text-sm text-muted">18 hours ago</span>
                                                <h5 class="text-body mb-2">Security Alert</h5>
                                                <p class="mb-2">New login detected from San Francisco, CA. If this wasn't you, please secure your account</p>
                                                <button class="btn btn-sm btn-outline-secondary me-2">Ignore</button>
                                                <button class="btn btn-sm btn-danger">Secure Account</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card bg-transparent mb-2 border-0">
                                    <div class="card-body p-3 rounded" style="background: rgba(var(--bs-light-rgb), 0.3); transition: all 0.2s ease;" onmouseover="this.style.background='rgba(var(--bs-primary-rgb), 0.05)'" onmouseout="this.style.background='rgba(var(--bs-light-rgb), 0.3)'">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img class="img-radius avatar rounded-0" src="{{ asset('assets/images/user/avatar-5.svg') }}" alt="Generic placeholder image" />
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <span class="float-end text-sm text-muted">5 hour ago</span>
                                                <h5 class="text-body mb-2">Security</h5>
                                                <p class="mb-0">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of
                                                    type and scrambled it to make a type</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center py-2">
                                <a href="#!" class="link-danger">Clear all Notifications</a>
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