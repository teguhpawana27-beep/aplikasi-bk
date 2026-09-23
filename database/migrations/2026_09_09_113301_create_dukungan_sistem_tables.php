<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =====================================================
        // DUKUNGAN SISTEM
        // =====================================================

        Schema::create('dukungan_sistem', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guru_bk_id')
                ->constrained('guru_bk')
                ->restrictOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->restrictOnDelete();

            $table->foreignId('siswa_id')
                ->nullable()
                ->constrained('siswa')
                ->nullOnDelete();

            $table->string('jenis_kegiatan');
            $table->date('tanggal');
            $table->string('sasaran')->nullable();
            $table->text('uraian_kegiatan')->nullable();
            $table->text('hasil')->nullable();
            $table->text('evaluasi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });

        // =====================================================
        // PROGRAM BK
        // =====================================================

        Schema::create('program_bk', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guru_bk_id')
                ->constrained('guru_bk')
                ->restrictOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->restrictOnDelete();

            $table->string('nama_program');
            $table->string('komponen')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('tujuan')->nullable();
            $table->text('sasaran')->nullable();
            $table->text('rencana_kegiatan')->nullable();
            $table->text('hasil')->nullable();
            $table->text('evaluasi')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });

        // =====================================================
        // EVALUASI BK
        // =====================================================

        Schema::create('evaluasi_bk', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guru_bk_id')
                ->constrained('guru_bk')
                ->restrictOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->restrictOnDelete();

            $table->string('komponen')->nullable();
            $table->date('tanggal');
            $table->text('indikator')->nullable();
            $table->text('hasil')->nullable();
            $table->text('kendala')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });

        // =====================================================
        // HUBUNGKAN TINDAK LANJUT KE DUKUNGAN SISTEM
        // =====================================================

        Schema::table('tindak_lanjut', function (Blueprint $table) {
            $table->foreign('dukungan_sistem_id')
                ->references('id')
                ->on('dukungan_sistem')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tindak_lanjut', function (Blueprint $table) {
            $table->dropForeign(['dukungan_sistem_id']);
        });

        Schema::dropIfExists('evaluasi_bk');
        Schema::dropIfExists('program_bk');
        Schema::dropIfExists('dukungan_sistem');
    }
};