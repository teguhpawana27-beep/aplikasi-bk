<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DukunganSistem extends Model
{
    protected $table = 'dukungan_sistem';

    protected $fillable = [
        'guru_bk_id',
        'tahun_ajaran_id',
        'siswa_id',
        'jenis_kegiatan',
        'tanggal',
        'sasaran',
        'uraian_kegiatan',
        'hasil',
        'evaluasi',
        'tindak_lanjut',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guruBK(): BelongsTo
    {
        return $this->belongsTo(
            GuruBK::class,
            'guru_bk_id'
        );
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(
            TahunAjaran::class,
            'tahun_ajaran_id'
        );
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(
            Siswa::class,
            'siswa_id'
        );
    }

    public function tindakLanjut()
    {
        return $this->hasMany(
            TindakLanjut::class,
            'dukungan_sistem_id'
        );
    }
}