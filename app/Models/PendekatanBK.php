<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PendekatanBK extends Model
{
    protected $table = 'pendekatan_bk';

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function layananResponsif(): HasMany
    {
        return $this->hasMany(LayananResponsif::class, 'pendekatan_id');
    }
}