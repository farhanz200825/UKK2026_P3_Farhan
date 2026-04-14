@extends('layouts.admin')

@section('title', 'History Aspirasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="ph ph-check-circle"></i> 
                    @if($guru->jabatan == 'Wali Kelas')
                        @if($currentType == 'saya')
                            History Aspirasi Saya
                        @else
                            History Aspirasi Kelas
                        @endif
                    @elseif($guru->canCreateAspirasi())
                        History Aspirasi Saya
                    @else
                        History Aspirasi
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <!-- TABS KHUSUS UNTUK WALI KELAS -->
                @if($guru->jabatan == 'Wali Kelas')
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link {{ $currentType == 'kelas' ? 'active' : '' }}" 
                           href="{{ route('guru.history', ['type' => 'kelas']) }}">
                            <i class="ph ph-users"></i> History Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentType == 'saya' ? 'active' : '' }}" 
                           href="{{ route('guru.history', ['type' => 'saya']) }}">
                            <i class="ph ph-user"></i> History Saya
                        </a>
                    </li>
                </ul>
                @endif
                
                <div class="alert alert-success mb-3">
                    <i class="ph ph-check-circle"></i> 
                    <strong>Informasi:</strong> Halaman ini menampilkan aspirasi yang sudah <strong>Selesai</strong>.
                </div>
                
                <!-- Tabel History -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal Selesai</th>
                                <th>ID Aspirasi</th>
                                @if($guru->jabatan == 'Wali Kelas' && $currentType == 'kelas')
                                <th>Siswa</th>
                                @endif
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Ditangani Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $index => $h)
                            <tr>
                                <td>{{ $history->firstItem() + $index }}</td>
                                <td>{{ $h->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $h->id_aspirasi }}</td>
                                
                                @if($guru->jabatan == 'Wali Kelas' && $currentType == 'kelas')
                                <td>
                                    {{ $h->aspirasi->user->siswa->nama ?? '-' }}
                                    <br><small class="text-muted">{{ $h->aspirasi->user->siswa->kelas ?? '-' }}</small>
                                
                                
                                @endif
                                
                                <td>{{ $h->aspirasi->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $h->aspirasi->ruangan->nama_ruangan ?? $h->aspirasi->lokasi }}</td>
                                <td>
                                    @php
                                        $pengubah = $h->pengubah;
                                        $namaPengubah = '-';
                                        if($pengubah) {
                                            if($pengubah->role == 'admin') {
                                                $namaPengubah = 'Admin';
                                            } elseif($pengubah->role == 'petugas' && $pengubah->petugas) {
                                                $namaPengubah = $pengubah->petugas->nama . ' (Petugas)';
                                            } elseif($pengubah->role == 'guru' && $pengubah->guru) {
                                                $namaPengubah = $pengubah->guru->nama . ' (' . $pengubah->guru->jabatan . ')';
                                            } else {
                                                $namaPengubah = $pengubah->email;
                                            }
                                        }
                                    @endphp
                                    {{ $namaPengubah }}
                                
                                
                                <td>
                                    <a href="{{ route('guru.aspirasi.detail', $h->id_aspirasi) }}" class="btn btn-sm btn-info">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                
                              
                            @empty
                                32
                                    <td colspan="{{ ($guru->jabatan == 'Wali Kelas' && $currentType == 'kelas') ? '8' : '7' }}" class="text-center">
                                        Belum ada history aspirasi
                                    </td>
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $history->links() }}
            </div>
        </div>
    </div>
</div>
@endsection