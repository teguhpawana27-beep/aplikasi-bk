<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeminatanPerencanaan extends Model
{
    protected $table = 'peminatan_perencanaan';

    protected $fillable = [
        'guru_bk_id',
        'tahun_ajaran_id',
        'kelas_id',
        'bidang_layanan_id',
        'jenis_layanan',
        'sasaran',
        'uraian_kegiatan',
        'tindak_lanjut',
        'keterangan',
        'tanggal',
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

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function bidangLayanan(): BelongsTo
    {
        return $this->belongsTo(BidangLayanan::class, 'bidang_layanan_id');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(
            PesertaPeminatan::class,
            'peminatan_perencanaan_id'
        );
    }
}