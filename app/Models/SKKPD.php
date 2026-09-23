<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SKKPD extends Model
{
    protected $table = 'skkpd';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function layananDasar(): HasMany
    {
        return $this->hasMany(LayananDasar::class, 'skkpd_id');
    }
}