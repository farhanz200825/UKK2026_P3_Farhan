<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    
    public $timestamps = false;
    const CREATED_AT = 'created_at';
    
    // Accessor untuk created_at yang selalu mengembalikan Carbon
    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        if ($value instanceof Carbon) {
            return $value;
        }
        return Carbon::parse($value);
    }
    
    // Mutator untuk created_at
    public function setCreatedAtAttribute($value)
    {
        if ($value instanceof Carbon) {
            $this->attributes['created_at'] = $value->toDateTimeString();
        } else {
            $this->attributes['created_at'] = $value;
        }
    }
    
    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class, 'id_aspirasi');
    }
    
    public function pengubah()
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}