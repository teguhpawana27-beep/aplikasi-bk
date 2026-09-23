<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BidangLayanan extends Model
{
    protected $table = 'bidang_layanan';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function peminatanPerencanaan(): HasMany
    {
        return $this->hasMany(PeminatanPerencanaan::class, 'bidang_layanan_id');
    }

    public function layananResponsif(): HasMany
    {
        return $this->hasMany(LayananResponsif::class, 'bidang_layanan_id');
    }
}