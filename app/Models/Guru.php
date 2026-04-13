<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    
    protected $table = 'guru';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'mata_pelajaran',
        'jabatan',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Bisa membuat aspirasi (seperti siswa)
    public function canCreateAspirasi()
    {
        return $this->jabatan == 'Guru';
    }
    
    // Bisa memberi feedback dan update progres
    public function canManageAspirasi()
    {
        return $this->jabatan == 'Wali Kelas';
    }
    
    // Bisa mengubah status
    public function canChangeStatus()
    {
        return $this->jabatan == 'Wali Kelas';
    }
    
    // Bisa melihat semua data aspirasi (read only)
    public function canViewAllAspirasi()
    {
        return in_array($this->jabatan, ['Kepala Sekolah', 'Wakil Kepala Sekolah', 'Kepala Jurusan', 'Wali Kelas']);
    }
    
    // Bisa melihat statistik
    public function canViewStatistik()
    {
        return in_array($this->jabatan, ['Kepala Sekolah', 'Wakil Kepala Sekolah', 'Kepala Jurusan']);
    }
    
    // Hanya bisa melihat statistik (tanpa lihat data)
    public function canOnlyView()
    {
        return false; // Tidak digunakan lagi
    }
    
    public function getJabatanBadgeAttribute()
    {
        $badges = [
            'Guru' => 'primary',
            'Kepala Sekolah' => 'danger',
            'Wakil Kepala Sekolah' => 'warning',
            'Wali Kelas' => 'success',
            'Kepala Jurusan' => 'info'
        ];
        
        return $badges[$this->jabatan] ?? 'secondary';
    }
    
    // Relasi ke aspirasi (untuk guru yang membuat aspirasi)
    public function aspirasi()
    {
        return $this->hasMany(Aspirasi::class, 'user_id', 'user_id');
    }
}