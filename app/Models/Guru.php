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
        'id_kelas',
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

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    // Bisa membuat aspirasi (Guru dan Wali Kelas)
    public function canCreateAspirasi()
    {
        return in_array($this->jabatan, ['Guru', 'Wali Kelas']);
    }

    // Bisa memberi feedback dan update progres (Wali Kelas)
    public function canManageAspirasi()
    {
        return $this->jabatan == 'Wali Kelas';
    }

    // Bisa mengubah status (Wali Kelas)
    public function canChangeStatus()
    {
        return $this->jabatan == 'Wali Kelas';
    }

    // Bisa melihat semua data aspirasi (read only) untuk Kepala Sekolah, Wakil, Kepala Jurusan
    public function canViewAllAspirasi()
    {
        return in_array($this->jabatan, ['Kepala Sekolah', 'Wakil Kepala Sekolah', 'Kepala Jurusan']);
    }

    // Bisa melihat statistik
    public function canViewStatistik()
    {
        return in_array($this->jabatan, ['Kepala Sekolah', 'Wakil Kepala Sekolah', 'Kepala Jurusan']);
    }

    // Untuk Wali Kelas: dapatkan ID kelas yang diampu
    public function getKelasId()
    {
        return $this->id_kelas;
    }

    // Untuk Wali Kelas: dapatkan nama kelas yang diampu
    public function getNamaKelas()
    {
        return $this->kelas ? $this->kelas->nama_kelas : '-';
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
}
