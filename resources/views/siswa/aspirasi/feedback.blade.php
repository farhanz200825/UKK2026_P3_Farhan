@extends('layouts.admin')

@section('title', 'Feedback Aspirasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="ph ph-chat-dots"></i> Feedback dari Guru/Admin</h5>
            </div>
            <div class="card-body">
                @forelse($aspirasi as $item)
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <strong><i class="ph ph-map-pin"></i> {{ Str::limit($item->lokasi, 30) }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <span class="badge 
                                    @if($item->status == 'Menunggu') bg-warning text-dark
                                    @elseif($item->status == 'Proses') bg-info
                                    @else bg-success
                                    @endif">
                                    {{ $item->status }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $feedbacks = $item->progres->filter(function($progres) {
                                    return str_contains($progres->keterangan_progres, 'Feedback:');
                                });
                            @endphp
                            
                            @if($feedbacks->count() > 0)
                                @foreach($feedbacks as $feedback)
                                    <div class="alert alert-light border-start border-primary mb-2" style="border-left-width: 4px !important;">
                                        <div class="d-flex justify-content-between flex-wrap gap-2">
                                            <strong>
                                                <i class="ph ph-chat-dots text-primary"></i> 
                                                @if($feedback->user)
                                                    @if($feedback->user->role == 'guru')
                                                        👨‍🏫 Guru
                                                    @elseif($feedback->user->role == 'admin')
                                                        👨‍💼 Admin
                                                    @endif
                                                @endif
                                            </strong>
                                            <small class="text-muted">{{ $feedback->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <p class="mb-0 mt-2">
                                            {{ str_replace('Feedback: ', '', $feedback->keterangan_progres) }}
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-secondary text-center">
                                    <i class="ph ph-info"></i> Belum ada feedback
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('siswa.aspirasi.detail', $item->id_aspirasi) }}" 
                               class="btn btn-primary btn-sm w-100">
                                <i class="ph ph-eye"></i> Lihat Detail Lengkap
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="ph ph-chat-dots" style="font-size: 48px; color: #ccc;"></i>
                        <p class="mt-2">Belum ada feedback dari guru/admin</p>
                        <a href="{{ route('siswa.aspirasi.create') }}" class="btn btn-primary">
                            <i class="ph ph-pencil-line"></i> Buat Aspirasi
                        </a>
                    </div>
                @endforelse
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $aspirasi->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection