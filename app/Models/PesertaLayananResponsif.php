<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesertaLayananResponsif extends Model
{
    protected $table = 'peserta_layanan_responsif';

    // Tabel ini tidak memiliki created_at dan updated_at
    public $timestamps = false;

    protected $fillable = [
        'layanan_responsif_id',
        'siswa_id',
        'peran',
    ];

    public function layananResponsif(): BelongsTo
    {
        return $this->belongsTo(
            LayananResponsif::class,
            'layanan_responsif_id'
        );
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(
            Siswa::class,
            'siswa_id'
        );
    }
}