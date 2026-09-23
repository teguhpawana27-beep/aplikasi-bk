<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =====================================================
        // ARSIP SURAT
        // =====================================================

        Schema::create('arsip_surat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->foreignId('guru_bk_id')
                ->constrained('guru_bk')
                ->restrictOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->restrictOnDelete();

            $table->string('jenis_surat');
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat');
            $table->string('perihal')->nullable();
            $table->text('isi_ringkas')->nullable();
            $table->string('file_path')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });

        // =====================================================
        // LAPORAN
        // =====================================================

        Schema::create('laporan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->restrictOnDelete();

            $table->foreignId('siswa_id')
                ->nullable()
                ->constrained('siswa')
                ->nullOnDelete();

            $table->string('jenis_laporan');
            $table->string('komponen')->nullable();

            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();

            $table->string('nama_file')->nullable();
            $table->string('file_path')->nullable();

            $table->timestamp('generated_at')->nullable();

            $table->timestamp('created_at')->useCurrent();
        });

        // =====================================================
        // AUDIT LOG
        // =====================================================

        Schema::create('audit_log', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('action');
            $table->string('table_name');
            $table->unsignedBigInteger('record_id')->nullable();

            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
        Schema::dropIfExists('laporan');
        Schema::dropIfExists('arsip_surat');
    }
};