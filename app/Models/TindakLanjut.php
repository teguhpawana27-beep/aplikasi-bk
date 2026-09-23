<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TindakLanjut extends Model
{
    protected $table = 'tindak_lanjut';

    protected $fillable = [
        'siswa_id',
        'guru_bk_id',
        'asesmen_awal_id',
        'layanan_dasar_id',
        'peminatan_perencanaan_id',
        'layanan_responsif_id',
        'dukungan_sistem_id',
        'tanggal_rencana',
        'tanggal_pelaksanaan',
        'rencana',
        'hasil',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_rencana' => 'date',
        'tanggal_pelaksanaan' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guruBK(): BelongsTo
    {
        return $this->belongsTo(GuruBK::class, 'guru_bk_id');
    }

    public function asesmenAwal(): BelongsTo
    {
        return $this->belongsTo(AsesmenAwal::class, 'asesmen_awal_id');
    }

    public function layananDasar(): BelongsTo
    {
        return $this->belongsTo(LayananDasar::class, 'layanan_dasar_id');
    }

    public function peminatanPerencanaan(): BelongsTo
    {
        return $this->belongsTo(
            PeminatanPerencanaan::class,
            'peminatan_perencanaan_id'
        );
    }

    public function layananResponsif(): BelongsTo
    {
        return $this->belongsTo(
            LayananResponsif::class,
            'layanan_responsif_id'
        );
    }

    public function dukunganSistem(): BelongsTo
    {
        return $this->belongsTo(
            DukunganSistem::class,
            'dukungan_sistem_id'
        );
    }
}