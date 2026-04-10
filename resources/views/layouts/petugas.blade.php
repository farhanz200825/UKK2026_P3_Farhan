<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Panel Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/css/icons.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { width: 260px; position: fixed; left: 0; top: 0; bottom: 0; background: #1e293b; z-index: 100; }
        .sidebar .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 8px; margin: 4px 12px; transition: all 0.3s; }
        .sidebar .nav-link:hover { background: #334155; color: white; }
        .sidebar .nav-link.active { background: #4f46e5; color: white; }
        .sidebar .nav-link i { width: 24px; margin-right: 12px; }
        .main-content { margin-left: 260px; padding: 20px; background: #f1f5f9; min-height: 100vh; }
        .navbar-top { background: white; padding: 12px 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .card { border-radius: 12px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .table { vertical-align: middle; }
        .btn-sm { padding: 5px 10px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="text-center py-4">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" height="50">
            <h6 class="text-white mt-2">Panel Petugas</h6>
        </div>
        <hr class="text-secondary mx-3">
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}" href="{{ route('petugas.dashboard') }}">
                <i class="ph ph-house"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('petugas.aspirasi.*') ? 'active' : '' }}" href="{{ route('petugas.aspirasi.index') }}">
                <i class="ph ph-chat-circle"></i> Aspirasi
            </a>
            <a class="nav-link {{ request()->routeIs('petugas.history') ? 'active' : '' }}" href="{{ route('petugas.history') }}">
                <i class="ph ph-clock-counter-clockwise"></i> History
            </a>
            <a class="nav-link {{ request()->routeIs('petugas.profile') ? 'active' : '' }}" href="{{ route('petugas.profile') }}">
                <i class="ph ph-user"></i> Profile
            </a>
        </nav>
        <hr class="text-secondary mx-3">
        <div class="position-absolute bottom-0 w-100 p-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="ph ph-sign-out"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="navbar-top d-flex justify-content-between align-items-center">
            <h5 class="mb-0">@yield('title')</h5>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->petugas->foto_url }}" width="30" height="30" class="rounded-circle me-2">
                    {{ Auth::user()->petugas->nama }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('petugas.profile') }}"><i class="ph ph-user"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="ph ph-sign-out"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>