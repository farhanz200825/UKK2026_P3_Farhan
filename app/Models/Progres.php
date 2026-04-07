<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    use HasFactory;

    protected $table = 'progres';
    protected $primaryKey = 'id_progres';
    public $timestamps = false;

    protected $fillable = [
        'id_aspirasi',
        'user_id',
        'keterangan_progres',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class, 'id_aspirasi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}