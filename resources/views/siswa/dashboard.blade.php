@extends('layouts.admin')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $totalAspirasi }}</h3>
                <small>Total Aspirasi</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3>{{ $totalMenunggu }}</h3>
                <small>Menunggu</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>{{ $totalProses }}</h3>
                <small>Diproses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $totalSelesai }}</h3>
                <small>Selesai</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h6>Aspirasi Aktif</h6>
            </div>
            <div class="card-body">
                @if($aspirasiAktif->count() > 0)
                <div class="list-group">
                    @foreach($aspirasiAktif as $a)
                    <a href="{{ route('siswa.aspirasi.detail', $a->id_aspirasi) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $a->kategori->nama_kategori ?? '-' }}</strong>
                                <br>
                                <small>{{ $a->lokasi }}</small>
                            </div>
                            <span class="badge bg-{{ $a->status == 'Menunggu' ? 'warning' : 'info' }}">{{ $a->status }}</span>
                        </div>
                        <small class="text-muted">{{ $a->created_at->diffForHumans() }}</small>
                    </a>
                    @endforeach
                </div>
                <a href="{{ route('siswa.aspirasi.status') }}" class="btn btn-sm btn-primary mt-3">Lihat Semua</a>
                @else
                <div class="text-center py-4">
                    <i class="ph ph-check-circle ph-2x text-success"></i>
                    <p class="mt-2">Tidak ada aspirasi aktif</p>
                    <a href="{{ route('siswa.aspirasi.create') }}" class="btn btn-primary btn-sm">Buat Aspirasi</a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h6>Aspirasi Selesai</h6>
            </div>
            <div class="card-body">
                @if($aspirasiSelesai->count() > 0)
                <div class="list-group">
                    @foreach($aspirasiSelesai as $a)
                    <a href="{{ route('siswa.aspirasi.detail', $a->id_aspirasi) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $a->kategori->nama_kategori ?? '-' }}</strong>
                                <br>
                                <small>{{ $a->lokasi }}</small>
                            </div>
                            <span class="badge bg-success">Selesai</span>
                        </div>
                        <small class="text-muted">Selesai: {{ $a->updated_at->diffForHumans() }}</small>
                    </a>
                    @endforeach
                </div>
                <a href="{{ route('siswa.aspirasi.history') }}" class="btn btn-sm btn-success mt-3">Lihat History</a>
                @else
                <div class="text-center py-4">
                    <i class="ph ph-clock-counter-clockwise ph-2x text-muted"></i>
                    <p class="mt-2">Belum ada aspirasi selesai</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection