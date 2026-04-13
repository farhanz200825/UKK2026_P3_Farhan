<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryStatus extends Model
{
    use HasFactory;
    
    protected $table = 'history_status';
    protected $primaryKey = 'id_history';
    
    protected $fillable = [
        'id_aspirasi',
        'status_lama',
        'status_baru',
        'diubah_oleh'
    ];
    
    public $timestamps = true;
    
    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class, 'id_aspirasi');
    }
    
    public function pengubah()
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
    
    // Helper untuk mendapatkan nama pengubah
    public function getNamaPengubahAttribute()
    {
        if ($this->pengubah) {
            if ($this->pengubah->role == 'siswa' && $this->pengubah->siswa) {
                return $this->pengubah->siswa->nama;
            } elseif ($this->pengubah->role == 'guru' && $this->pengubah->guru) {
                return $this->pengubah->guru->nama;
            } elseif ($this->pengubah->role == 'petugas' && $this->pengubah->petugas) {
                return $this->pengubah->petugas->nama;
            }
            return $this->pengubah->email;
        }
        return 'Sistem';
    }
}