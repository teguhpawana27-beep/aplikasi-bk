<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilKonseli extends Model
{
    protected $table = 'profil_konseli';

    protected $fillable = [
        'tahun_ajaran_id',
        'siswa_id',
        'kondisi_pribadi',
        'kondisi_sosial',
        'kondisi_belajar',
        'kondisi_karir',
        'kondisi_keluarga',
        'catatan',
    ];

    /**
     * Relasi ke Tahun Ajaran
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(
            TahunAjaran::class,
            'tahun_ajaran_id'
        );
    }

    /**
     * Relasi ke Siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(
            Siswa::class,
            'siswa_id'
        );
    }
}