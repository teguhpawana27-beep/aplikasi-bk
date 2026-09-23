<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'tahun_ajaran_id',
        'jurusan_id',
        'tingkat',
        'nama_kelas',
        'wali_kelas',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function riwayatSiswa(): HasMany
    {
        return $this->hasMany(RiwayatKelasSiswa::class, 'kelas_id');
    }

    public function layananDasar(): HasMany
    {
        return $this->hasMany(LayananDasar::class, 'kelas_id');
    }

    public function peminatanPerencanaan(): HasMany
    {
        return $this->hasMany(PeminatanPerencanaan::class, 'kelas_id');
    }

    public function layananResponsif(): HasMany
    {
        return $this->hasMany(LayananResponsif::class, 'kelas_id');
    }
}