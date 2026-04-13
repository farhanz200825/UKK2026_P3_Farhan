@extends('layouts.admin')

@section('title', 'History Aspirasi Selesai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="ph ph-check-circle"></i> History Aspirasi Selesai</h5>
                <small>Daftar aspirasi yang sudah selesai ditangani</small>
            </div>
            <div class="card-body">
                @if($aspirasiSelesai->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>ID</th>
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Keterangan</th>
                                <th>Tanggal Selesai</th>
                                <th>Riwayat Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aspirasiSelesai as $index => $aspirasi)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $aspirasi->id_aspirasi }}</td>
                                <td>{{ $aspirasi->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $aspirasi->ruangan->nama_ruangan ?? $aspirasi->lokasi }}</td>
                                <td>{{ Str::limit($aspirasi->keterangan, 50) }}</td>
                                <td>{{ $aspirasi->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#historyModal{{ $aspirasi->id_aspirasi }}">
                                        <i class="ph ph-clock-counter-clockwise"></i> Lihat
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('siswa.aspirasi.detail', $aspirasi->id_aspirasi) }}" class="btn btn-info btn-sm">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Modal Riwayat Status -->
                            <div class="modal fade" id="historyModal{{ $aspirasi->id_aspirasi }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Riwayat Status Aspirasi #{{ $aspirasi->id_aspirasi }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="timeline">
                                                @foreach($aspirasi->historyStatus as $history)
                                                <div class="d-flex mb-3">
                                                    <div class="me-3 text-center">
                                                        <div class="bg-{{ $history->status_baru == 'Selesai' ? 'success' : ($history->status_baru == 'Proses' ? 'info' : 'warning') }} rounded-circle p-2" style="width: 40px; height: 40px;">
                                                            <i class="ph ph-{{ $history->status_baru == 'Selesai' ? 'check' : ($history->status_baru == 'Proses' ? 'spinner' : 'clock') }} text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $history->status_lama }} → {{ $history->status_baru }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $history->created_at->format('d/m/Y H:i:s') }}<br>
                                                            Oleh: 
                                                            @php
                                                                $pengubah = $history->pengubah;
                                                                if($pengubah) {
                                                                    if($pengubah->role == 'guru' && $pengubah->guru) echo $pengubah->guru->nama;
                                                                    elseif($pengubah->role == 'petugas' && $pengubah->petugas) echo $pengubah->petugas->nama;
                                                                    else echo $pengubah->email;
                                                                } else echo '-';
                                                            @endphp
                                                        </small>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="ph ph-info ph-2x"></i>
                    <h5 class="mt-2">Belum Ada History</h5>
                    <p class="mb-0">Belum ada aspirasi yang selesai ditangani.</p>
                    <a href="{{ route('siswa.aspirasi.status') }}" class="btn btn-primary mt-3">
                        <i class="ph ph-chart-line"></i> Lihat Status Aktif
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection