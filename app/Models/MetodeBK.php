<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodeBK extends Model
{
    protected $table = 'metode_bk';

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function layananDasar(): HasMany
    {
        return $this->hasMany(LayananDasar::class, 'metode_id');
    }
}