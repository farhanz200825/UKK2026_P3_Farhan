<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;
    
    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';
    
    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'jenis_ruangan',
        'lokasi',
        'kapasitas',
        'kondisi',
        'deskripsi'
    ];
    
    public $timestamps = true;
    
    public function aspirasi()
    {
        return $this->hasMany(Aspirasi::class, 'id_ruangan');
    }
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Baik' => 'success',
            'Rusak Ringan' => 'warning',
            'Rusak Berat' => 'danger',
            'Dalam Perbaikan' => 'info'
        ];
        
        return $badges[$this->kondisi] ?? 'secondary';
    }
}