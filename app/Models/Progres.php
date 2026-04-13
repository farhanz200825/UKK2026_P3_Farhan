<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    use HasFactory;
    
    protected $table = 'progres';
    protected $primaryKey = 'id_progres';
    
    protected $fillable = [
        'id_aspirasi',
        'user_id',
        'keterangan_progres'
    ];
    
    public $timestamps = true;
    
    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class, 'id_aspirasi');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Helper untuk mendapatkan nama pemberi progres
    public function getNamaUserAttribute()
    {
        if ($this->user) {
            if ($this->user->role == 'siswa' && $this->user->siswa) {
                return $this->user->siswa->nama;
            } elseif ($this->user->role == 'guru' && $this->user->guru) {
                return $this->user->guru->nama;
            } elseif ($this->user->role == 'petugas' && $this->user->petugas) {
                return $this->user->petugas->nama;
            }
            return $this->user->email;
        }
        return 'Sistem';
    }
}