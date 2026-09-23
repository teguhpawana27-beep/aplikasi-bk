<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AsesmenAwal extends Model
{
    protected $table = 'asesmen_awal';

    protected $fillable = [
        'siswa_id',
        'guru_bk_id',
        'tahun_ajaran_id',
        'tanggal_asesmen',
        'instrumen',
        'hasil',
        'rekomendasi',
        'tindak_lanjut',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_asesmen' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guruBK(): BelongsTo
    {
        return $this->belongsTo(GuruBK::class, 'guru_bk_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function tindakLanjut(): HasMany
    {
        return $this->hasMany(TindakLanjut::class, 'asesmen_awal_id');
    }
}