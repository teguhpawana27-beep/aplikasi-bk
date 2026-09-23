<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluasiBK extends Model
{
    protected $table = 'evaluasi_bk';

    protected $fillable = [
        'guru_bk_id',
        'tahun_ajaran_id',
        'komponen',
        'tanggal',
        'indikator',
        'hasil',
        'kendala',
        'rekomendasi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guruBK(): BelongsTo
    {
        return $this->belongsTo(GuruBK::class, 'guru_bk_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}