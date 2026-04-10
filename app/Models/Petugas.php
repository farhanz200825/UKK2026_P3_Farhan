<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;
    
    protected $table = 'petugas';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto',
        'status'
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function aspirasi()
    {
        return $this->hasMany(Aspirasi::class, 'petugas_id');
    }
    
    public function getJenisKelaminTextAttribute()
    {
        return $this->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
    }
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Aktif' => 'success',
            'Tidak Aktif' => 'danger'
        ];
        return $badges[$this->status] ?? 'secondary';
    }
    
    public function getFotoUrlAttribute()
    {
        if ($this->foto && file_exists(public_path('storage/' . $this->foto))) {
            return asset('storage/' . $this->foto);
        }
        return asset('assets/img/default-avatar.png');
    }
}