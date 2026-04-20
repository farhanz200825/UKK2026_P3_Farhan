@extends('layouts.admin')

@section('title', 'Setup PIN')

@section('content')
<div class="row">
    <div class="col-12 col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ph ph-key"></i> Setup PIN Verifikasi</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ph ph-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ph ph-warning-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <div class="alert alert-warning mb-4">
                    <i class="ph ph-warning"></i>
                    <strong>Perhatian!</strong> 
                    <ul class="mb-0 mt-2">
                        <li>PIN hanya bisa dibuat <strong>SATU KALI</strong></li>
                        <li>PIN tidak dapat diubah atau direset</li>
                        <li>PIN akan digunakan setiap kali Anda membuat aspirasi</li>
                        <li>Simpan PIN Anda dengan baik</li>
                    </ul>
                </div>
                
                <form method="POST" action="{{ route('siswa.setup-pin.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Buat PIN (6 digit) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph ph-key"></i></span>
                            <input type="password" name="pin" class="form-control" 
                                   placeholder="Masukkan PIN 6 digit" 
                                   maxlength="6" 
                                   pattern="[0-9]{6}"
                                   title="Harus 6 digit angka"
                                   required>
                            <button type="button" class="btn btn-outline-secondary toggle-pin">
                                <i class="ph ph-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Buat PIN 6 digit yang mudah diingat (hanya angka)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi PIN <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph ph-key"></i></span>
                            <input type="password" name="pin_confirmation" class="form-control" 
                                   placeholder="Ketik ulang PIN" 
                                   maxlength="6" 
                                   pattern="[0-9]{6}"
                                   required>
                            <button type="button" class="btn btn-outline-secondary toggle-pin">
                                <i class="ph ph-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Ketik ulang PIN yang sama</small>
                    </div>
                    
                    <div class="alert alert-danger" id="pinMismatch" style="display: none;">
                        <i class="ph ph-x-circle"></i> PIN tidak cocok!
                    </div>
                    
                    <div class="d-flex justify-content-between gap-2">
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">
                            <i class="ph ph-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ph ph-floppy-disk"></i> Buat PIN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle visibility PIN
    document.querySelectorAll('.toggle-pin').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('ph-eye');
            this.querySelector('i').classList.toggle('ph-eye-slash');
        });
    });
    
    // Validasi PIN match
    const pinInput = document.querySelector('input[name="pin"]');
    const pinConfirmInput = document.querySelector('input[name="pin_confirmation"]');
    const pinMismatch = document.getElementById('pinMismatch');
    const submitBtn = document.getElementById('submitBtn');
    
    function validatePin() {
        const pin = pinInput.value;
        const confirm = pinConfirmInput.value;
        
        if (confirm.length > 0 && pin !== confirm) {
            pinMismatch.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            pinMismatch.style.display = 'none';
            submitBtn.disabled = false;
        }
    }
    
    pinInput.addEventListener('input', validatePin);
    pinConfirmInput.addEventListener('input', validatePin);
</script>
@endsection