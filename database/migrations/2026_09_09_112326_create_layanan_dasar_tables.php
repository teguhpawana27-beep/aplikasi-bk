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
        // LAYANAN DASAR
        // =====================================================

        Schema::create('layanan_dasar', function (Blueprint $table) {
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

            $table->foreignId('skkpd_id')
                ->constrained('skkpd')
                ->restrictOnDelete();

            $table->foreignId('metode_id')
                ->constrained('metode_bk')
                ->restrictOnDelete();

            $table->string('jenis_layanan');
            $table->date('tanggal');
            $table->string('topik');
            $table->string('sasaran')->nullable();
            $table->text('uraian_kegiatan')->nullable();
            $table->text('hasil')->nullable();
            $table->text('evaluasi')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });

        // =====================================================
        // PESERTA LAYANAN DASAR
        // =====================================================

        Schema::create('peserta_layanan_dasar', function (Blueprint $table) {
            $table->id();

            $table->foreignId('layanan_dasar_id')
                ->constrained('layanan_dasar')
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->timestamp('created_at')->useCurrent();

            $table->unique([
                'layanan_dasar_id',
                'siswa_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_layanan_dasar');
        Schema::dropIfExists('layanan_dasar');
    }
};