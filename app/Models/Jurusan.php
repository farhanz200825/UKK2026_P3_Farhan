<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;
    
    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';
    
    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
        'deskripsi'
    ];
    
    public $timestamps = true; // Pastikan ini true
    
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_jurusan');
    }
    
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_jurusan');
    }
}