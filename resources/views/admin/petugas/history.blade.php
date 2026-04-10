@extends('layouts.petugas')

@section('title', 'History Perubahan Status')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0"><i class="ph ph-clock-counter-clockwise"></i> Riwayat Perubahan Status Aspirasi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>ID Aspirasi</th>
                        <th>Siswa</th>
                        <th>Status Lama</th>
                        <th>Status Baru</th>
                        <th>Diubah Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                    <tr>
                        <td>{{ $h->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $h->id_aspirasi }}</td>
                        <td>{{ $h->aspirasi->user->siswa->nama ?? '-' }}</td>
                        <td><span class="badge bg-secondary">{{ $h->status_lama }}</span></td>
                        <td><span class="badge bg-{{ $h->status_baru == 'Selesai' ? 'success' : ($h->status_baru == 'Proses' ? 'warning' : 'secondary') }}">{{ $h->status_baru }}</span></td>
                        <td>{{ $h->pengubah->petugas->nama ?? $h->pengubah->email ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">Tidak ada riwayat</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $history->links() }}
    </div>
</div>
@endsection