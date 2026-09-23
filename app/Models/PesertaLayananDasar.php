<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesertaLayananDasar extends Model
{
    protected $table = 'peserta_layanan_dasar';

    public $timestamps = false;

    protected $fillable = [
        'layanan_dasar_id',
        'siswa_id',
    ];

    public function layananDasar(): BelongsTo
    {
        return $this->belongsTo(
            LayananDasar::class,
            'layanan_dasar_id'
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