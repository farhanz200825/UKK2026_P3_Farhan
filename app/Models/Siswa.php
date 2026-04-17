<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id',
        'nis',
        'nama',
        'kelas',
        'jurusan',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto',
        'id_kelas',
        'id_jurusan',
        'token',
        'pin',
        'pin_verified_at'
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'date',
        'pin_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function kelasRelasi()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
    
    public function jurusanRelasi()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan');
    }
    
    // Verifikasi token
    public function verifyToken($token)
    {
        if ($this->token && \Hash::check($token, $this->token)) {
            return true;
        }
        return false;
    }
    
    // Verifikasi PIN
    public function verifyPin($pin)
    {
        if ($this->pin && \Hash::check($pin, $this->pin)) {
            return true;
        }
        return false;
    }
    
    // Cek apakah PIN sudah dibuat
    public function hasPin()
    {
        return !is_null($this->pin);
    }
}