<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // =====================================================
        // LAYANAN RESPONSIF
        // =====================================================

        Schema::create('layanan_responsif', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guru_bk_id')
                ->constrained('guru_bk')
                ->restrictOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->restrictOnDelete();

            $table->foreignId('kelas_id')
                ->nullable()
                ->constrained('kelas')
                ->nullOnDelete();

            $table->foreignId('bidang_layanan_id')
                ->constrained('bidang_layanan')
                ->restrictOnDelete();

            $table->foreignId('pendekatan_id')
                ->constrained('pendekatan_bk')
                ->restrictOnDelete();

            $table->string('jenis_layanan');
            $table->string('tingkat')->nullable();
            $table->date('tanggal');

            $table->text('uraian_masalah')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('keterangan')->nullable();

            $table->string('status_kasus')->default('proses');

            $table->timestamps();
        });

        // =====================================================
        // PESERTA LAYANAN RESPONSIF
        // =====================================================

        Schema::create('peserta_layanan_responsif', function (Blueprint $table) {
            $table->id();

            $table->foreignId('layanan_responsif_id')
                ->constrained('layanan_responsif')
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->string('peran')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->unique([
                'layanan_responsif_id',
                'siswa_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_layanan_responsif');
        Schema::dropIfExists('layanan_responsif');
    }
};