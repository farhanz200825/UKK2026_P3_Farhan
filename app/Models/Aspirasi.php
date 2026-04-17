<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    use HasFactory;
    
    protected $table = 'aspirasi';
    protected $primaryKey = 'id_aspirasi';
    
    protected $fillable = [
        'user_id',
        'id_kategori',
        'lokasi',
        'keterangan',
        'foto',
        'status',
        'id_ruangan',
        'saksi_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function siswa()
    {
        return $this->hasOneThrough(Siswa::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }
    
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
    
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
    
    // Relasi ke saksi (siswa)
    public function saksi()
    {
        return $this->belongsTo(Siswa::class, 'saksi_id');
    }
    
    public function progres()
    {
        return $this->hasMany(Progres::class, 'id_aspirasi');
    }
    
    public function historyStatus()
    {
        return $this->hasMany(HistoryStatus::class, 'id_aspirasi');
    }
    
    public function getNamaPengirimAttribute()
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
        return 'Pengguna tidak diketahui';
    }
    
    public function getNamaSaksiAttribute()
    {
        return $this->saksi ? $this->saksi->nama : '-';
    }
}