<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    use HasFactory;
    
    protected $table = 'progres';
    protected $primaryKey = 'id_progres';
    
    protected $fillable = [
        'id_aspirasi',
        'user_id',
        'keterangan_progres'
    ];
    
    // Tabel progres hanya memiliki created_at, tidak ada updated_at
    public $timestamps = true;
    const UPDATED_AT = null;
    
    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class, 'id_aspirasi');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}