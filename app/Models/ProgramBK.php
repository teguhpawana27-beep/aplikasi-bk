<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramBK extends Model
{
    protected $table = 'program_bk';

    protected $fillable = [
        'guru_bk_id',
        'tahun_ajaran_id',
        'nama_program',
        'komponen',
        'tanggal_mulai',
        'tanggal_selesai',
        'tujuan',
        'sasaran',
        'rencana_kegiatan',
        'hasil',
        'evaluasi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function guruBK(): BelongsTo
    {
        return $this->belongsTo(GuruBK::class, 'guru_bk_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}