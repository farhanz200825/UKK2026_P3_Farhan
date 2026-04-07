@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="mb-0">Dashboard Siswa</h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Welcome Card ] start -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="text-white mb-2">Selamat Datang, {{ Auth::user()->name ?? 'Siswa' }}!</h4>
                            <p class="text-white mb-0">Silakan pilih menu untuk menyampaikan aspirasi atau melihat status pengaduan Anda.</p>
                        </div>
                        <i class="ph ph-student d-none d-sm-block" style="font-size: 4rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ Menu Cards ] start -->
    <div class="row g-3">
        <div class="col-6 col-md-6 col-xl-3 mb-3">
            <div class="card h-100 text-center" style="cursor: pointer;" onclick="location.href='{{ route('siswa.aspirasi.create') }}'">
                <div class="card-body p-3 p-md-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 mb-md-3" style="width: 50px; height: 50px;">
                        <i class="ph ph-pencil-simple text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="mb-1">Input Aspirasi</h6>
                    <p class="text-muted small mb-0 d-none d-md-block">Sampaikan aspirasi atau laporan kerusakan sarana sekolah</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-6 col-xl-3 mb-3">
            <div class="card h-100 text-center" style="cursor: pointer;" onclick="location.href='{{ route('siswa.aspirasi.status') }}'">
                <div class="card-body p-3 p-md-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 mb-md-3" style="width: 50px; height: 50px;">
                        <i class="ph ph-clock text-success" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="mb-1">Lihat Status</h6>
                    <p class="text-muted small mb-0 d-none d-md-block">Pantau status pengaduan (Menunggu/Diproses/Selesai)</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-6 col-xl-3 mb-3">
            <div class="card h-100 text-center" style="cursor: pointer;" onclick="location.href='{{ route('siswa.aspirasi.history') }}'">
                <div class="card-body p-3 p-md-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 mb-md-3" style="width: 50px; height: 50px;">
                        <i class="ph ph-history text-warning" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="mb-1">Lihat History</h6>
                    <p class="text-muted small mb-0 d-none d-md-block">Lihat daftar riwayat pengajuan aspirasi Anda</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-6 col-xl-3 mb-3">
            <div class="card h-100 text-center" style="cursor: pointer;" onclick="location.href='{{ route('siswa.aspirasi.feedback') }}'">
                <div class="card-body p-3 p-md-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 mb-md-3" style="width: 50px; height: 50px;">
                        <i class="ph ph-chat-circle-text text-info" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="mb-1">Lihat Feedback</h6>
                    <p class="text-muted small mb-0 d-none d-md-block">Lihat tanggapan dari guru/admin</p>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Menu Cards ] end -->

    <!-- [ Statistik Singkat ] start -->
    <div class="row g-3 mt-2">
        <div class="col-12 col-md-4 mb-3">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 p-2 p-md-3 rounded">
                            <i class="ph ph-clock text-warning" style="font-size: 1.2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 small">Menunggu</h6>
                            <h3 class="mb-0" id="countMenunggu">2</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-2 p-md-3 rounded">
                            <i class="ph ph-spinner text-primary" style="font-size: 1.2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 small">Diproses</h6>
                            <h3 class="mb-0" id="countDiproses">1</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-2 p-md-3 rounded">
                            <i class="ph ph-check-circle text-success" style="font-size: 1.2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 small">Selesai</h6>
                            <h3 class="mb-0" id="countSelesai">3</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Statistik Singkat ] end -->

    <!-- [ Pengaduan Terbaru ] start -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pengaduan Terbaru Anda</h5>
                </div>
                <div class="card-body p-0">
                    <!-- Desktop Table View -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="recentAspirasiTable">
                                <!-- Data akan diisi JavaScript untuk desktop -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Mobile Card View -->
                    <div class="d-block d-md-none p-3" id="recentAspirasiMobile">
                        <!-- Data akan diisi JavaScript untuk mobile -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Pengaduan Terbaru ] end -->
</div>

<style>
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .pc-content {
            padding: 0.5rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .breadcrumb {
            font-size: 0.8rem;
        }
        
        .page-header-title h5 {
            font-size: 1.1rem;
        }
        
        /* Mobile card list styling */
        .mobile-aspirasi-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #eef2f6;
            transition: all 0.3s ease;
        }
        
        .mobile-aspirasi-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .mobile-aspirasi-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .mobile-aspirasi-date {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .mobile-aspirasi-status {
            font-size: 0.7rem;
        }
        
        .mobile-aspirasi-body {
            margin-bottom: 10px;
        }
        
        .mobile-aspirasi-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 0.85rem;
        }
        
        .mobile-aspirasi-label {
            font-weight: 600;
            color: #495057;
            width: 70px;
        }
        
        .mobile-aspirasi-value {
            flex: 1;
            color: #333;
            word-break: break-word;
        }
        
        .mobile-aspirasi-footer {
            text-align: right;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #e0e0e0;
        }
        
        .btn-mobile-detail {
            padding: 4px 12px;
            font-size: 0.75rem;
        }
        
        /* Badge styling untuk mobile */
        .badge {
            padding: 4px 8px;
            font-size: 0.7rem;
        }
    }
    
    /* Tablet Styles */
    @media (min-width: 768px) and (max-width: 992px) {
        .pc-content {
            padding: 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
    }
</style>

<script>
    // Data dummy pengaduan siswa
    const aspirasiData = [
        { id: 1, tanggal: '2026-04-05', kategori: 'Furniture', lokasi: 'Kelas X IPA 1', keterangan: 'Kursi patah 3 buah', status: 'Menunggu' },
        { id: 2, tanggal: '2026-04-03', kategori: 'Elektronik', lokasi: 'Lab Komputer', keterangan: 'AC tidak dingin', status: 'Diproses' },
        { id: 3, tanggal: '2026-03-28', kategori: 'Fasilitas', lokasi: 'Perpustakaan', keterangan: 'Lampu mati', status: 'Selesai' },
        { id: 4, tanggal: '2026-03-25', kategori: 'Furniture', lokasi: 'Kelas X IPA 2', keterangan: 'Meja goyang', status: 'Selesai' },
        { id: 5, tanggal: '2026-03-20', kategori: 'Elektronik', lokasi: 'Lab Bahasa', keterangan: 'Speaker rusak', status: 'Diproses' }
    ];

    function getStatusBadge(status) {
        switch(status) {
            case 'Menunggu':
                return '<span class="badge bg-secondary">Menunggu</span>';
            case 'Diproses':
                return '<span class="badge bg-warning text-dark">Diproses</span>';
            case 'Selesai':
                return '<span class="badge bg-success">Selesai</span>';
            default:
                return '<span class="badge bg-secondary">' + status + '</span>';
        }
    }

    function formatDate(dateString) {
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    function formatDateShort(dateString) {
        const options = { day: 'numeric', month: 'short', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    // Load Desktop Table
    function loadDesktopTable() {
        const tbody = document.getElementById('recentAspirasiTable');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        const recentData = aspirasiData.slice(0, 5);
        
        if (recentData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">Tidak ada data pengaduan</td></tr>';
            return;
        }
        
        recentData.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${formatDate(item.tanggal)}</td>
                <td>${item.kategori}</td>
                <td>${item.lokasi}</td>
                <td>${item.keterangan}</td>
                <td>${getStatusBadge(item.status)}</td>
                <td>
                    <a href="{{ route('siswa.aspirasi.detail', '') }}/${item.id}" class="btn btn-sm btn-info text-white">
                        <i class="ph ph-eye"></i>
                    </a>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Load Mobile Cards
    function loadMobileCards() {
        const container = document.getElementById('recentAspirasiMobile');
        if (!container) return;
        
        container.innerHTML = '';
        const recentData = aspirasiData.slice(0, 5);
        
        if (recentData.length === 0) {
            container.innerHTML = '<div class="text-center py-4 text-muted">Tidak ada data pengaduan</div>';
            return;
        }
        
        recentData.forEach(item => {
            const card = document.createElement('div');
            card.className = 'mobile-aspirasi-card';
            card.innerHTML = `
                <div class="mobile-aspirasi-header">
                    <span class="mobile-aspirasi-date">📅 ${formatDateShort(item.tanggal)}</span>
                    <span class="mobile-aspirasi-status">${getStatusBadge(item.status)}</span>
                </div>
                <div class="mobile-aspirasi-body">
                    <div class="mobile-aspirasi-item">
                        <span class="mobile-aspirasi-label">Kategori</span>
                        <span class="mobile-aspirasi-value">${item.kategori}</span>
                    </div>
                    <div class="mobile-aspirasi-item">
                        <span class="mobile-aspirasi-label">Lokasi</span>
                        <span class="mobile-aspirasi-value">${item.lokasi}</span>
                    </div>
                    <div class="mobile-aspirasi-item">
                        <span class="mobile-aspirasi-label">Keterangan</span>
                        <span class="mobile-aspirasi-value">${item.keterangan}</span>
                    </div>
                </div>
                <div class="mobile-aspirasi-footer">
                    <a href="{{ route('siswa.aspirasi.detail', '') }}/${item.id}" class="btn btn-info btn-mobile-detail text-white">
                        <i class="ph ph-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            `;
            container.appendChild(card);
        });
    }

    // Hitung statistik
    function loadStats() {
        const menunggu = aspirasiData.filter(a => a.status === 'Menunggu').length;
        const diproses = aspirasiData.filter(a => a.status === 'Diproses').length;
        const selesai = aspirasiData.filter(a => a.status === 'Selesai').length;
        
        const countMenunggu = document.getElementById('countMenunggu');
        const countDiproses = document.getElementById('countDiproses');
        const countSelesai = document.getElementById('countSelesai');
        
        if (countMenunggu) countMenunggu.textContent = menunggu;
        if (countDiproses) countDiproses.textContent = diproses;
        if (countSelesai) countSelesai.textContent = selesai;
    }

    // Handle responsive loading
    function handleResponsive() {
        const isMobile = window.innerWidth < 768;
        
        if (isMobile) {
            loadMobileCards();
        } else {
            loadDesktopTable();
        }
    }

    // Listen for resize events
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            handleResponsive();
        }, 250);
    });

    document.addEventListener('DOMContentLoaded', function() {
        handleResponsive();
        loadStats();
    });
</script>
@endsection