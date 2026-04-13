<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryStatus extends Model
{
    use HasFactory;
    
    protected $table = 'history_status';
    protected $primaryKey = 'id_history';
    
    protected $fillable = [
        'id_aspirasi',
        'status_lama',
        'status_baru',
        'diubah_oleh'
    ];
    
    // Matikan timestamps karena tabel tidak memiliki updated_at
    public $timestamps = false;
    
    // Tentukan kolom created_at jika ada
    const CREATED_AT = 'created_at';
    
    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class, 'id_aspirasi');
    }
    
    public function pengubah()
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}