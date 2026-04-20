<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

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
        'pin',
        'pin_created_at'
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'date',
        'pin_created_at' => 'datetime',
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
    
    // Cek apakah sudah punya PIN
    public function hasPin()
    {
        return !is_null($this->pin);
    }
    
    // Verifikasi PIN
    public function verifyPin($pin)
    {
        if ($this->pin && Hash::check($pin, $this->pin)) {
            return true;
        }
        return false;
    }
    
    // Buat PIN baru (hanya bisa sekali)
    public function createPin($pin)
    {
        if ($this->hasPin()) {
            return false;
        }
        
        $this->update([
            'pin' => Hash::make($pin),
            'pin_created_at' => now()
        ]);
        
        return true;
    }
}