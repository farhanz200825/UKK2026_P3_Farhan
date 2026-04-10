@extends('layouts.admin')

@section('title', 'Detail Aspirasi')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5><i class="ph ph-file-text"></i> Detail Aspirasi</h5>
                <div class="card-header-right">
                    @if($aspirasi->status != 'Selesai')
                        @if($aspirasi->status == 'Proses')
                            <a href="{{ route('guru.aspirasi.selesai', $aspirasi->id_aspirasi) }}" 
                               class="btn btn-success btn-sm" 
                               onclick="return confirm('Yakin ingin menyelesaikan aspirasi ini?')">
                                <i class="ph ph-check-circle"></i> Tandai Selesai
                            </a>
                        @else
                            <span class="badge bg-warning text-dark p-2">Status: Menunggu</span>
                        @endif
                    @else
                        <span class="badge bg-success p-2">Status: Selesai</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Informasi Siswa -->
                <div class="alert alert-light border-start border-primary" style="border-left-width: 4px !important;">
                    <h6><i class="ph ph-user"></i> Informasi Pengaju</h6>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Siswa:</strong> {{ $aspirasi->user->siswa->nama ?? '-' }}</p>
                            <p><strong>NIS:</strong> {{ $aspirasi->user->siswa->nis ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Kelas:</strong> {{ $aspirasi->user->siswa->kelas ?? '-' }}</p>
                            <p><strong>Jurusan:</strong> {{ $aspirasi->user->siswa->jurusan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Detail Aspirasi -->
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Tanggal Pengajuan</th>
                        <td>{{ $aspirasi->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $aspirasi->kategori->nama_kategori ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $aspirasi->lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ nl2br($aspirasi->keterangan) }}</td>
                    </tr>
                    @if($aspirasi->foto)
                    <tr>
                        <th>Foto</th>
                        <td>
                            <a href="{{ asset('storage/' . $aspirasi->foto) }}" target="_blank">
                                <img src="{{ asset('storage/' . $aspirasi->foto) }}" 
                                     alt="Foto Aspirasi" style="max-width: 200px; border-radius: 8px;">
                            </a>
                        </td>
                    </tr>
                    @endif
                </table>
                
                @if($aspirasi->status != 'Selesai')
                <!-- Form Feedback (TIDAK mengubah status) -->
                <div class="mt-4">
                    <h6><i class="ph ph-chat-dots"></i> Berikan Feedback</h6>
                    <hr>
                    <form action="{{ route('guru.aspirasi.feedback', $aspirasi->id_aspirasi) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <textarea name="feedback" class="form-control" rows="2" 
                                      placeholder="Tulis feedback untuk siswa..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="ph ph-paper-plane"></i> Kirim Feedback
                        </button>
                    </form>
                    <small class="text-muted">*Feedback tidak mengubah status aspirasi</small>
                </div>
                
                <!-- Form Progres (MENGUBAH status ke Proses) -->
                <div class="mt-4">
                    <h6><i class="ph ph-progress"></i> Update Progres</h6>
                    <hr>
                    <form action="{{ route('guru.aspirasi.progres', $aspirasi->id_aspirasi) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <textarea name="keterangan_progres" class="form-control" rows="2" 
                                      placeholder="Update progres penanganan..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="ph ph-pencil"></i> Update Progres
                        </button>
                    </form>
                    <small class="text-muted">*Update progres akan mengubah status menjadi "Diproses"</small>
                </div>
                @endif
                
                <!-- Riwayat Progres -->
                <div class="mt-4">
                    <h6><i class="ph ph-clock-counter-clockwise"></i> Riwayat Progres & Feedback</h6>
                    <hr>
                    @forelse($aspirasi->progres->sortByDesc('created_at') as $progres)
                        <div class="alert alert-light border-start 
                            @if(str_contains($progres->keterangan_progres, 'Feedback:')) border-primary
                            @else border-success
                            @endif" 
                            style="border-left-width: 4px !important;">
                            <div class="d-flex justify-content-between">
                                <strong>
                                    @if(str_contains($progres->keterangan_progres, 'Feedback:'))
                                        <i class="ph ph-chat-dots text-primary"></i> Feedback
                                    @else
                                        <i class="ph ph-progress text-success"></i> Progres
                                    @endif
                                    @if($progres->user)
                                        - {{ $progres->user->role == 'guru' ? 'Guru' : 'Admin' }}
                                    @endif
                                </strong>
                                <small class="text-muted">{{ $progres->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="mb-0 mt-2">
                                {{ str_replace('Feedback: ', '', $progres->keterangan_progres) }}
                            </p>
                        </div>
                    @empty
                        <div class="alert alert-secondary text-center">
                            <i class="ph ph-info"></i> Belum ada progres atau feedback
                        </div>
                    @endforelse
                </div>
                
                <!-- Riwayat Status -->
                @if($aspirasi->historyStatus->count() > 0)
                <div class="mt-4">
                    <h6><i class="ph ph-clock-counter-clockwise"></i> Riwayat Perubahan Status</h6>
                    <hr>
                    <div class="timeline">
                        @foreach($aspirasi->historyStatus->sortByDesc('created_at') as $history)
                        <div class="timeline-item">
                            <div class="timeline-marker 
                                @if($history->status_baru == 'Selesai') bg-success
                                @elseif($history->status_baru == 'Proses') bg-info
                                @else bg-warning
                                @endif">
                            </div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">
                                    Status berubah dari 
                                    <span class="badge bg-secondary">{{ $history->status_lama }}</span>
                                    menjadi 
                                    <span class="badge 
                                        @if($history->status_baru == 'Selesai') bg-success
                                        @elseif($history->status_baru == 'Proses') bg-info
                                        @else bg-warning
                                        @endif">
                                        {{ $history->status_baru }}
                                    </span>
                                    @if($history->user)
                                        <br><small>Oleh: {{ $history->user->role }}</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('guru.aspirasi.index') }}" class="btn btn-secondary">
                    <i class="ph ph-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-marker {
        position: absolute;
        left: -25px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #e0e0e0;
    }
    .timeline-content {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
    }
</style>
@endpush