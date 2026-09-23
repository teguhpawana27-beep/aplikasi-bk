<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuruBK extends Model
{
    protected $table = 'guru_bk';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'no_hp',
        'jabatan',
        'status',
    ];

    /**
     * Relasi ke tabel users.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke data penugasan BK.
     */
    public function penugasanBK(): HasMany
    {
        return $this->hasMany(
            PenugasanBK::class,
            'guru_bk_id'
        );
    }

    /**
     * Relasi ke layanan dasar.
     */
    public function layananDasar(): HasMany
    {
        return $this->hasMany(
            LayananDasar::class,
            'guru_bk_id'
        );
    }

    /**
     * Relasi ke layanan responsif.
     */
    public function layananResponsif(): HasMany
    {
        return $this->hasMany(
            LayananResponsif::class,
            'guru_bk_id'
        );
    }

    /**
     * Relasi ke dukungan sistem.
     */
    public function dukunganSistem(): HasMany
    {
        return $this->hasMany(
            DukunganSistem::class,
            'guru_bk_id'
        );
    }
}