<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesertaPeminatan extends Model
{
    protected $table = 'peserta_peminatan';

    public $timestamps = false;

    protected $fillable = [
        'peminatan_perencanaan_id',
        'siswa_id',
    ];

    public function peminatan(): BelongsTo
    {
        return $this->belongsTo(
            PeminatanPerencanaan::class,
            'peminatan_perencanaan_id'
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