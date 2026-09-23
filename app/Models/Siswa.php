<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'tahun_masuk',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function riwayatKelas(): HasMany
    {
        return $this->hasMany(RiwayatKelasSiswa::class, 'siswa_id');
    }

    public function penugasanBK(): HasMany
    {
        return $this->hasMany(PenugasanBK::class, 'siswa_id');
    }

    public function profilKonseli(): HasOne
    {
        return $this->hasOne(ProfilKonseli::class, 'siswa_id');
    }

    public function dataBMW(): HasMany
    {
        return $this->hasMany(DataBMW::class, 'siswa_id');
    }

    public function dataSNPMB(): HasMany
    {
        return $this->hasMany(DataSNPMB::class, 'siswa_id');
    }

    public function asesmenAwal(): HasMany
    {
        return $this->hasMany(AsesmenAwal::class, 'siswa_id');
    }

    public function pesertaLayananDasar(): HasMany
    {
        return $this->hasMany(PesertaLayananDasar::class, 'siswa_id');
    }

    public function pesertaPeminatan(): HasMany
    {
        return $this->hasMany(PesertaPeminatan::class, 'siswa_id');
    }

    public function pesertaLayananResponsif(): HasMany
    {
        return $this->hasMany(PesertaLayananResponsif::class, 'siswa_id');
    }

    public function tindakLanjut(): HasMany
    {
        return $this->hasMany(TindakLanjut::class, 'siswa_id');
    }

    public function dukunganSistem(): HasMany
    {
        return $this->hasMany(DukunganSistem::class, 'siswa_id');
    }

    public function arsipSurat(): HasMany
    {
        return $this->hasMany(ArsipSurat::class, 'siswa_id');
    }

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'siswa_id');
    }
}