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
                        <small class="text-muted d-block">Pengaduan Sarana</small>
                    </div>
                </a>
            </div>

            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                    </li>

                    <!-- Dashboard -->
                    <li class="pc-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-house-line"></i>
                            </span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Panel Admin</label>
                    </li>

                    <!-- Data Users -->
                    <li class="pc-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-users"></i>
                            </span>
                            <span class="pc-mtext">Manajemen Pengguna</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Menu Pengaduan</label>
                    </li>

                    <!-- Data Pengaduan -->
                    <li class="pc-item {{ request()->routeIs('admin.pengaduan*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pengaduan') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-warning-octagon"></i>
                            </span>
                            <span class="pc-mtext">Data Pengaduan</span>
                        </a>
                    </li>

                    <!-- Data Sarana -->
                    <li class="pc-item {{ request()->routeIs('admin.sarana*') ? 'active' : '' }}">
                        <a href="{{ route('admin.sarana') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ph ph-chair"></i>
                            </span>
                            <span class="pc-mtext">Data Sarana</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Logout</label>
                    </li>

                    <!-- Logout -->
                    <li class="pc-item">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <a href="#" class="pc-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="pc-micon">
                                    <i class="ph ph-sign-out"></i>
                                </span>
                                <span class="pc-mtext">Logout</span>
                            </a>
                        </form>
                    </li>
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
                </ul>
            </div>
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button">
                            <i class="ph ph-user-circle"></i>
                            <span class="ms-2">{{ Auth::user()->email }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a href="#" class="dropdown-item">
                                <i class="ph ph-user"></i>
                                <span>Profile</span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="ph ph-gear"></i>
                                <span>Settings</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ph ph-sign-out"></i>
                                <span>Logout</span>
                            </a>
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