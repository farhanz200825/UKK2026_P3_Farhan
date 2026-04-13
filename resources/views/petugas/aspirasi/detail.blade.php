@extends('layouts.admin')

@section('title', 'Detail Aspirasi')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">Detail Aspirasi #{{ $aspirasi->id_aspirasi }}</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="30%">Kategori</th><td>{{ $aspirasi->kategori->nama_kategori ?? '-' }}</td></tr>
                    <tr><th>Ruangan</th><td>{{ $aspirasi->ruangan->nama_ruangan ?? $aspirasi->lokasi }}</td></tr>
                    <tr>
                        <th>Pengirim</th>
                        <td>
                            @php
                                $pengirim = $aspirasi->user->siswa ?? $aspirasi->user->guru;
                            @endphp
                            {{ $pengirim->nama ?? $aspirasi->user->email }}
                            <br><small class="text-muted">{{ $pengirim->kelas ?? $pengirim->jabatan ?? '-' }}</small>
                        

                    
                    <tr><th>Keterangan</th><td>{{ $aspirasi->keterangan }}</td></tr>
                    @if($aspirasi->foto)
                    <tr><th>Foto</th><td><img src="{{ asset('storage/' . $aspirasi->foto) }}" width="200" class="img-thumbnail"></td></tr>
                    @endif
                    <tr><th>Status</th>
                        <td>
                            <span class="badge bg-{{ $aspirasi->status == 'Selesai' ? 'success' : ($aspirasi->status == 'Proses' ? 'info' : 'warning') }}">
                                {{ $aspirasi->status }}
                            </span>
                        

                    
                    <tr><th>Dibuat Pada</th><td>{{ $aspirasi->created_at->format('d/m/Y H:i:s') }}</td></tr>
                </table>
            </div>
        </div>

        <!-- Form Feedback & Progres untuk Petugas -->
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="ph ph-chat"></i> Kelola Aspirasi (Petugas)</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('petugas.aspirasi.feedback', $aspirasi->id_aspirasi) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Feedback untuk Pengirim</label>
                        <textarea name="feedback" class="form-control" rows="2" placeholder="Tulis feedback untuk pengirim..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="ph ph-paper-plane"></i> Kirim Feedback
                    </button>
                </form>
                
                <hr>
                
                <form action="{{ route('petugas.aspirasi.progres', $aspirasi->id_aspirasi) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Update Progres Penanganan</label>
                        <textarea name="keterangan_progres" class="form-control" rows="2" placeholder="Update progres penanganan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-info btn-sm">
                        <i class="ph ph-progress"></i> Update Progres
                    </button>
                </form>
                
                <hr>
                
                <form action="{{ route('petugas.aspirasi.status', $aspirasi->id_aspirasi) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">Ubah Status</label>
                            <select name="status" class="form-select">
                                <option value="Menunggu" {{ $aspirasi->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Proses" {{ $aspirasi->status == 'Proses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Selesai" {{ $aspirasi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="ph ph-arrow-circle-right"></i> Update Status
                            </button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Keterangan perubahan status (opsional):</small>
                        <textarea name="keterangan_progres" class="form-control mt-1" rows="1" placeholder="Tambahkan keterangan..."></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Riwayat Progres -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ph ph-list"></i> Riwayat Progres</h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($aspirasi->progres as $progres)
                <div class="border-start border-primary ps-3 mb-3">
                    <small class="text-muted">{{ $progres->created_at->format('d/m/Y H:i') }}</small>
                    <p class="mb-0 small">{{ $progres->keterangan_progres }}</p>
                    <small class="text-muted">- {{ $progres->user->petugas->nama ?? $progres->user->guru->nama ?? $progres->user->email }}</small>
                </div>
                @empty
                <p class="text-muted text-center">Belum ada progres</p>
                @endforelse
            </div>
        </div>
        
        <!-- Riwayat Status -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ph ph-clock-counter-clockwise"></i> Riwayat Status</h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($aspirasi->historyStatus as $history)
                <div class="border-start border-info ps-3 mb-3">
                    <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                    <p class="mb-0 small">{{ $history->status_lama }} → {{ $history->status_baru }}</p>
                    <small class="text-muted">- {{ $history->pengubah->petugas->nama ?? $history->pengubah->guru->nama ?? $history->pengubah->email }}</small>
                </div>
                @empty
                <p class="text-muted text-center">Belum ada riwayat</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection