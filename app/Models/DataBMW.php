<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataBMW extends Model
{
    protected $table = 'data_bmw';

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'tanggal_pendataan',
        'asal_sekolah',
        'data_masuk',
        'data_orang_tua',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pendataan' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}