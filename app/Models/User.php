<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi dengan siswa
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    // Relasi dengan guru
    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    // Relasi dengan aspirasi
    public function aspirasi()
    {
        return $this->hasMany(Aspirasi::class, 'user_id');
    }

    // Relasi dengan progres
    public function progres()
    {
        return $this->hasMany(Progres::class, 'user_id');
    }

    // Relasi dengan history status
    public function historyStatus()
    {
        return $this->hasMany(HistoryStatus::class, 'diubah_oleh');
    }
}