<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LayananResponsif extends Model
{
    protected $table = 'layanan_responsif';

    protected $fillable = [
        'guru_bk_id',
        'tahun_ajaran_id',
        'kelas_id',
        'bidang_layanan_id',
        'pendekatan_id',
        'jenis_layanan',
        'tingkat',
        'tanggal',
        'uraian_masalah',
        'tindak_lanjut',
        'keterangan',
        'status_kasus',
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

    public function pendekatan(): BelongsTo
    {
        return $this->belongsTo(PendekatanBK::class, 'pendekatan_id');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(
            PesertaLayananResponsif::class,
            'layanan_responsif_id'
        );
    }

    public function tindakLanjut(): HasMany
    {
        return $this->hasMany(
            TindakLanjut::class,
            'layanan_responsif_id'
        );
    }
}