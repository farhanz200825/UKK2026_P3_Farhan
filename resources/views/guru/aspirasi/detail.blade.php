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
                    
                    <!-- Baris SAKSI -->
                    <tr>
                        <th>Saksi</th>
                        <td>
                            @if($aspirasi->saksi)
                                <span class="fw-bold">{{ $aspirasi->saksi->nama }}</span>
                                <br><small class="text-muted">NIS: {{ $aspirasi->saksi->nis }} | Kelas: {{ $aspirasi->saksi->kelasRelasi->nama_kelas ?? $aspirasi->saksi->kelas }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Baris PENGIRIM -->
                    <tr>
                        <th>Pengirim</th>
                        <td>
                            @php
                                $pengirim = $aspirasi->user->siswa ?? $aspirasi->user->guru;
                                $rolePengirim = $aspirasi->user->role;
                                $detailPengirim = '';
                                
                                if($rolePengirim == 'siswa') {
                                    $detailPengirim = 'Siswa - Kelas: ' . ($pengirim->kelas ?? '-');
                                } elseif($rolePengirim == 'guru') {
                                    $detailPengirim = 'Guru - ' . ($pengirim->jabatan ?? '-');
                                } else {
                                    $detailPengirim = ucfirst($rolePengirim);
                                }
                            @endphp
                            <span class="fw-bold">{{ $pengirim->nama ?? $aspirasi->user->email }}</span>
                            <br><small class="text-muted">{{ $detailPengirim }}</small>
                        

                    
                    <tr><th>Keterangan</th><td>{{ $aspirasi->keterangan }}</td></tr>
                    
                    @if($aspirasi->foto)
                    <tr>
                        <th>Foto Awal</th>
                        <td>
                            <img src="{{ asset('storage/' . $aspirasi->foto) }}" alt="Foto Awal" width="200" class="img-thumbnail">
                        

                     
                    @endif
                    
                    <!-- TAMPILKAN FOTO BUKTI SELESAI -->
                    @php
                        $fotoBukti = null;
                        $fotoBuktiKeterangan = null;
                        foreach($aspirasi->progres as $progres) {
                            if(str_contains($progres->keterangan_progres, '📎 Foto bukti:')) {
                                preg_match('/📎 Foto bukti: (.*)/', $progres->keterangan_progres, $matches);
                                if(isset($matches[1])) {
                                    $fotoBukti = $matches[1];
                                    $fotoBuktiKeterangan = $progres->keterangan_progres;
                                    break;
                                }
                            }
                        }
                    @endphp
                    
                    @if($fotoBukti)
                    <tr>
                        <th>Foto Bukti Selesai</th>
                        <td>
                            <img src="{{ $fotoBukti }}" alt="Foto Bukti" width="300" class="img-thumbnail">
                            <br>
                            <small class="text-muted">Foto bukti penanganan setelah selesai</small>
                            @if($fotoBuktiKeterangan)
                                <br>
                                <small class="text-muted">Keterangan: {{ Str::limit(str_replace('📎 Foto bukti: ' . $fotoBukti, '', $fotoBuktiKeterangan), 100) }}</small>
                            @endif
                        

                     
                    @endif
                    
                    <!-- STATUS -->
                    <tr>
                        <th>Status</th>
                        <td>
                            @php
                                $statusClass = 'warning';
                                $statusIcon = 'ph-clock';
                                if($aspirasi->status == 'Proses') {
                                    $statusClass = 'info';
                                    $statusIcon = 'ph-spinner';
                                } elseif($aspirasi->status == 'Selesai') {
                                    $statusClass = 'success';
                                    $statusIcon = 'ph-check-circle';
                                }
                            @endphp
                            <span class="badge bg-{{ $statusClass }} p-2">
                                <i class="{{ $statusIcon }} me-1"></i> {{ $aspirasi->status }}
                            </span>
                        

                    
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $aspirasi->created_at ? $aspirasi->created_at->format('d/m/Y H:i:s') : '-' }}–
                    </tr>
                    
                    @if($aspirasi->status == 'Selesai')
                    <tr>
                        <th>Selesai Pada</th>
                        <td>{{ $aspirasi->updated_at ? $aspirasi->updated_at->format('d/m/Y H:i:s') : '-' }}–
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Form Feedback & Progres (Hanya untuk Wali Kelas) -->
        @if($guru->canManageAspirasi())
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="ph ph-chat"></i> Kelola Aspirasi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.aspirasi.feedback', $aspirasi->id_aspirasi) }}" method="POST" class="mb-3">
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
            </div>
        </div>
        @endif
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
                    <small class="text-muted">{{ $progres->created_at ? $progres->created_at->format('d/m/Y H:i') : '-' }}</small>
                    <p class="mb-0 small">{!! nl2br(e($progres->keterangan_progres)) !!}</p>
                    <small class="text-muted">- {{ $progres->user->guru->nama ?? $progres->user->petugas->nama ?? $progres->user->email }}</small>
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
                    <small class="text-muted">{{ $history->created_at ? $history->created_at->format('d/m/Y H:i') : '-' }}</small>
                    <p class="mb-0 small">
                        <span class="badge bg-secondary">{{ $history->status_lama }}</span> 
                        <i class="ph ph-arrow-right"></i> 
                        <span class="badge bg-{{ $history->status_baru == 'Selesai' ? 'success' : ($history->status_baru == 'Proses' ? 'info' : 'warning') }}">
                            {{ $history->status_baru }}
                        </span>
                    </p>
                    <small class="text-muted">- {{ $history->pengubah->guru->nama ?? $history->pengubah->petugas->nama ?? $history->pengubah->email }}</small>
                </div>
                @empty
                <p class="text-muted text-center">Belum ada riwayat</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection