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
        Schema::create('tindak_lanjut', function (Blueprint $table) {
            $table->id();

            // Siswa yang menerima tindak lanjut
            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            // Guru BK yang menangani
            $table->foreignId('guru_bk_id')
                ->constrained('guru_bk')
                ->restrictOnDelete();

            // Sumber tindak lanjut
            $table->foreignId('asesmen_awal_id')
                ->nullable()
                ->constrained('asesmen_awal')
                ->nullOnDelete();

            $table->foreignId('layanan_dasar_id')
                ->nullable()
                ->constrained('layanan_dasar')
                ->nullOnDelete();

            $table->foreignId('peminatan_perencanaan_id')
                ->nullable()
                ->constrained('peminatan_perencanaan')
                ->nullOnDelete();

            $table->foreignId('layanan_responsif_id')
                ->nullable()
                ->constrained('layanan_responsif')
                ->nullOnDelete();

            // Akan terhubung ke dukungan_sistem
            // pada migration berikutnya.
            $table->foreignId('dukungan_sistem_id')
                ->nullable();

            $table->date('tanggal_rencana')->nullable();
            $table->date('tanggal_pelaksanaan')->nullable();

            $table->text('rencana')->nullable();
            $table->text('hasil')->nullable();

            $table->string('status')->default('direncanakan');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjut');
    }
};