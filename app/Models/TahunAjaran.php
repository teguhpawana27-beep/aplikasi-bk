<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    public function kelas(): HasMany
    {
        return $this->hasMany(
            Kelas::class,
            'tahun_ajaran_id'
        );
    }

    public function layananDasar(): HasMany
    {
        return $this->hasMany(
            LayananDasar::class,
            'tahun_ajaran_id'
        );
    }

    public function peminatanPerencanaan(): HasMany
    {
        return $this->hasMany(
            PeminatanPerencanaan::class,
            'tahun_ajaran_id'
        );
    }

    public function layananResponsif(): HasMany
    {
        return $this->hasMany(
            LayananResponsif::class,
            'tahun_ajaran_id'
        );
    }

    public function dukunganSistem(): HasMany
    {
        return $this->hasMany(
            DukunganSistem::class,
            'tahun_ajaran_id'
        );
    }

    public function arsipSurat(): HasMany
    {
        return $this->hasMany(
            ArsipSurat::class,
            'tahun_ajaran_id'
        );
    }
}