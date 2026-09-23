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
        // HIMPUNAN DATA
        // =====================================================

        // 1. PROFIL KONSELI
        Schema::create('profil_konseli', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')
                ->unique()
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->text('kondisi_pribadi')->nullable();
            $table->text('kondisi_sosial')->nullable();
            $table->text('kondisi_belajar')->nullable();
            $table->text('kondisi_karir')->nullable();
            $table->text('kondisi_keluarga')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();
        });

        // 2. DATA BMW
        Schema::create('data_bmw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->restrictOnDelete();

            $table->date('tanggal_pendataan');
            $table->string('asal_sekolah')->nullable();
            $table->text('data_masuk')->nullable();
            $table->text('data_orang_tua')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });

        // 3. DATA SNPMB
        Schema::create('data_snpmb', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->restrictOnDelete();

            $table->date('tanggal_pendataan');
            $table->string('jalur')->nullable();
            $table->string('perguruan_tinggi')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('status_pendaftaran')->nullable();
            $table->string('hasil')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });

        // 4. ASESMEN AWAL
        Schema::create('asesmen_awal', function (Blueprint $table) {
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

            $table->date('tanggal_asesmen');
            $table->string('instrumen')->nullable();
            $table->text('hasil')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });


        // =====================================================
        // MASTER LAYANAN BK
        // =====================================================

        // 5. BIDANG LAYANAN
        Schema::create('bidang_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // 6. SKKPD
        Schema::create('skkpd', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // 7. METODE BK
        Schema::create('metode_bk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // 8. PENDEKATAN BK
        Schema::create('pendekatan_bk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendekatan_bk');
        Schema::dropIfExists('metode_bk');
        Schema::dropIfExists('skkpd');
        Schema::dropIfExists('bidang_layanan');

        Schema::dropIfExists('asesmen_awal');
        Schema::dropIfExists('data_snpmb');
        Schema::dropIfExists('data_bmw');
        Schema::dropIfExists('profil_konseli');
    }
};