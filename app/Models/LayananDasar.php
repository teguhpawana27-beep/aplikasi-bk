<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LayananDasar extends Model
{
    protected $table = 'layanan_dasar';

    protected $fillable = [
        'guru_bk_id',
        'tahun_ajaran_id',
        'kelas_id',
        'skkpd_id',
        'metode_id',
        'jenis_layanan',
        'tanggal',
        'topik',
        'sasaran',
        'uraian_kegiatan',
        'hasil',
        'evaluasi',
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

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function skkpd(): BelongsTo
    {
        return $this->belongsTo(Skkpd::class, 'skkpd_id');
    }

    public function metode(): BelongsTo
    {
        return $this->belongsTo(MetodeBK::class, 'metode_id');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(
            PesertaLayananDasar::class,
            'layanan_dasar_id'
        );
    }
}