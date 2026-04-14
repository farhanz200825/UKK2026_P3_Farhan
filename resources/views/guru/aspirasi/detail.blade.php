@extends('layouts.admin')

@section('title', 'Detail Aspirasi')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6>Detail Aspirasi #{{ $aspirasi->id_aspirasi }}</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Kategori</th><td>{{ $aspirasi->kategori->nama_kategori ?? '-' }}</td></tr>
                    <tr><th>Ruangan</th><td>{{ $aspirasi->ruangan->nama_ruangan ?? $aspirasi->lokasi }}</td></tr>
                    <tr><th>Keterangan</th><td>{{ $aspirasi->keterangan }}</td></tr>
                    <tr><th>Status</th>
                        <td>
                            <span class="badge bg-{{ $aspirasi->status == 'Selesai' ? 'success' : ($aspirasi->status == 'Proses' ? 'info' : 'warning') }}">
                                {{ $aspirasi->status }}
                            </span>
                         </td>
                    </tr>
                    <tr><th>Tanggal</th><td>{{ $aspirasi->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
            </div>
        </div>

        @if($guru->canManageAspirasi())
        <!-- Form Feedback & Progres (Hanya untuk Guru & Wali Kelas) -->
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h6>Kelola Aspirasi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.aspirasi.feedback', $aspirasi->id_aspirasi) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label>Feedback</label>
                        <textarea name="feedback" class="form-control" rows="2" placeholder="Tulis feedback untuk siswa..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Kirim Feedback</button>
                </form> 
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Riwayat Progres -->
        <div class="card">
            <div class="card-header">
                <h6>Riwayat Progres</h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($aspirasi->progres as $progres)
                <div class="border-start border-primary ps-3 mb-2">
                    <small class="text-muted">{{ $progres->created_at->format('d/m/Y H:i') }}</small>
                    <p class="mb-0 small">{{ $progres->keterangan_progres }}</p>
                    <small>- {{ $progres->user->guru->nama ?? $progres->user->email }}</small>
                </div>
                @empty
                <p class="text-muted">Belum ada progres</p>
                @endforelse
            </div>
        </div>
        
        <!-- Riwayat Status -->
        <div class="card mt-3">
            <div class="card-header">
                <h6>Riwayat Status</h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($aspirasi->historyStatus as $history)
                <div class="border-start border-info ps-3 mb-2">
                    <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                    <p class="mb-0 small">{{ $history->status_lama }} → {{ $history->status_baru }}</p>
                    <small>- {{ $history->pengubah->guru->nama ?? $history->pengubah->email }}</small>
                </div>
                @empty
                <p class="text-muted">Belum ada riwayat</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection