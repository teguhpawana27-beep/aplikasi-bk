<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipSurat extends Model
{
    protected $table = 'arsip_surat';

    protected $fillable = [
        'siswa_id',
        'guru_bk_id',
        'tahun_ajaran_id',
        'jenis_surat',
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'isi_ringkas',
        'file_path',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(
            Siswa::class,
            'siswa_id'
        );
    }

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
}