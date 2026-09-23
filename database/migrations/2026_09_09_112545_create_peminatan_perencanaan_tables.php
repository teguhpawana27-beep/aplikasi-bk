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
        // PEMINATAN DAN PERENCANAAN INDIVIDU
        // =====================================================

        Schema::create('peminatan_perencanaan', function (Blueprint $table) {
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

            $table->string('jenis_layanan');
            $table->string('sasaran')->nullable();
            $table->text('uraian_kegiatan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal');

            $table->timestamps();
        });

        // =====================================================
        // PESERTA PEMINATAN
        // =====================================================

        Schema::create('peserta_peminatan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peminatan_perencanaan_id')
                ->constrained('peminatan_perencanaan')
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->timestamp('created_at')->useCurrent();

            $table->unique([
                'peminatan_perencanaan_id',
                'siswa_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_peminatan');
        Schema::dropIfExists('peminatan_perencanaan');
    }
};