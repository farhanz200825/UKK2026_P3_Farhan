@extends('layouts.admin')

@section('title', 'Status Aspirasi Aktif')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="ph ph-chart-line"></i> Status Aspirasi Aktif</h5>
                <small>Aspirasi yang sedang dalam proses penanganan</small>
            </div>
            <div class="card-body">
                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>{{ $statistik['total'] }}</h3>
                                <small>Aspirasi Aktif</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3>{{ $statistik['menunggu'] }}</h3>
                                <small>Menunggu</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3>{{ $statistik['proses'] }}</h3>
                                <small>Diproses</small>
                            </div>
                        </div>
                    </div>
                </div>

                @if($aspirasi->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aspirasi as $index => $a)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $a->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $a->ruangan->nama_ruangan ?? $a->lokasi }}</td>
                                <td>{{ Str::limit($a->keterangan, 50) }}</td>
                                <td>
                                    @php
                                        $statusClass = $a->status == 'Menunggu' ? 'warning' : 'info';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $a->status }}</span>
                                </td>
                                <td>{{ $a->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('siswa.aspirasi.detail', $a->id_aspirasi) }}" class="btn btn-info btn-sm">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-success text-center">
                    <i class="ph ph-check-circle ph-2x"></i>
                    <h5 class="mt-2">Tidak Ada Aspirasi Aktif</h5>
                    <p class="mb-0">Semua aspirasi Anda sudah selesai ditangani.</p>
                    <a href="{{ route('siswa.aspirasi.history') }}" class="btn btn-primary mt-3">
                        <i class="ph ph-clock-counter-clockwise"></i> Lihat History
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection