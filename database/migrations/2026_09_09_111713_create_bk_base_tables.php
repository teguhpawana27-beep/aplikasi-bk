<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ROLES
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tambahkan role_id ke users bawaan Laravel
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->after('id')
                ->constrained('roles')
                ->restrictOnDelete();
            
            $table->boolean('is_active')->default(true)->after('password');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });

        // 3. GURU BK
        Schema::create('guru_bk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('nip')->nullable()->unique();
            $table->string('nama_lengkap');
            $table->string('no_hp')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        // 4. TAHUN AJARAN
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // 5. JURUSAN
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 6. KELAS
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->restrictOnDelete();
            $table->foreignId('jurusan_id')
                ->constrained('jurusan')
                ->restrictOnDelete();
            $table->string('tingkat');
            $table->string('nama_kelas');
            $table->string('wali_kelas')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique([
                'tahun_ajaran_id',
                'jurusan_id',
                'tingkat',
                'nama_kelas'
            ]);
        });

        // 7. SISWA
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('nisn')->nullable()->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->year('tahun_masuk');
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        // 8. RIWAYAT KELAS SISWA
        Schema::create('riwayat_kelas_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();
            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->restrictOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('status')->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 9. PENUGASAN BK
        Schema::create('penugasan_bk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_bk_id')
                ->constrained('guru_bk')
                ->cascadeOnDelete();
            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->restrictOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('status')->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique([
                'guru_bk_id',
                'siswa_id',
                'tahun_ajaran_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasan_bk');
        Schema::dropIfExists('riwayat_kelas_siswa');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('jurusan');
        Schema::dropIfExists('tahun_ajaran');
        Schema::dropIfExists('guru_bk');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn([
                'role_id',
                'is_active',
                'last_login_at'
            ]);
        });

        Schema::dropIfExists('roles');
    }
};
