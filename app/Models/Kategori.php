<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $timestamps = true;

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function aspirasi()
    {
        return $this->hasMany(Aspirasi::class, 'id_kategori');
    }
}