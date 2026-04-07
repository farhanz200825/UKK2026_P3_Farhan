<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryStatus extends Model
{
    use HasFactory;

    protected $table = 'history_status';
    protected $primaryKey = 'id_history';
    public $timestamps = false;

    protected $fillable = [
        'id_aspirasi',
        'status_lama',
        'status_baru',
        'diubah_oleh',
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
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}