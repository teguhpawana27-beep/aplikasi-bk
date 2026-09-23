<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. TAMBAHKAN TAHUN AJARAN
        |--------------------------------------------------------------------------
        |
        | Migration sebelumnya sudah sempat menambahkan kolom ini sebelum
        | gagal. Karena itu kita cek terlebih dahulu agar tidak terjadi
        | Duplicate column.
        |
        */

        if (!Schema::hasColumn('profil_konseli', 'tahun_ajaran_id')) {

            Schema::table('profil_konseli', function (Blueprint $table) {

                $table->foreignId('tahun_ajaran_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('tahun_ajaran')
                    ->restrictOnDelete();

            });
        }


        /*
        |--------------------------------------------------------------------------
        | 2. ISI TAHUN AJARAN UNTUK DATA PROFIL LAMA
        |--------------------------------------------------------------------------
        |
        | Hubungan:
        |
        | profil_konseli
        |       ↓
        | siswa_id
        |       ↓
        | riwayat_kelas_siswa
        |       ↓
        | kelas
        |       ↓
        | tahun_ajaran_id
        |
        */

        $profilLama = DB::table('profil_konseli')
            ->select(
                'id',
                'siswa_id'
            )
            ->whereNull('tahun_ajaran_id')
            ->get();


        foreach ($profilLama as $profil) {

            $riwayatTerbaru = DB::table(
                'riwayat_kelas_siswa'
            )
                ->join(
                    'kelas',
                    'riwayat_kelas_siswa.kelas_id',
                    '=',
                    'kelas.id'
                )
                ->where(
                    'riwayat_kelas_siswa.siswa_id',
                    $profil->siswa_id
                )
                ->orderByDesc(
                    'riwayat_kelas_siswa.id'
                )
                ->select(
                    'kelas.tahun_ajaran_id'
                )
                ->first();


            if ($riwayatTerbaru) {

                DB::table('profil_konseli')
                    ->where(
                        'id',
                        $profil->id
                    )
                    ->update([
                        'tahun_ajaran_id' =>
                            $riwayatTerbaru->tahun_ajaran_id,
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 3. FALLBACK TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        |
        | Kalau ada data lama yang tidak memiliki riwayat kelas,
        | gunakan tahun ajaran aktif.
        |
        */

        $tahunAjaranAktif = DB::table(
            'tahun_ajaran'
        )
            ->where(
                'is_active',
                true
            )
            ->orderByDesc(
                'tanggal_mulai'
            )
            ->first();


        if ($tahunAjaranAktif) {

            DB::table('profil_konseli')
                ->whereNull(
                    'tahun_ajaran_id'
                )
                ->update([
                    'tahun_ajaran_id' =>
                        $tahunAjaranAktif->id,
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 4. LEPAS FOREIGN KEY SISWA
        |--------------------------------------------------------------------------
        |
        | PENTING:
        |
        | siswa_id memiliki:
        |
        | UNIQUE INDEX
        | +
        | FOREIGN KEY
        |
        | Jadi foreign key harus dilepas terlebih dahulu sebelum
        | UNIQUE INDEX dihapus.
        |
        */

        $foreignKeyExists = DB::select(
            "
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'profil_konseli'
            AND COLUMN_NAME = 'siswa_id'
            AND REFERENCED_TABLE_NAME = 'siswa'
            "
        );


        if (!empty($foreignKeyExists)) {

            Schema::table(
                'profil_konseli',
                function (Blueprint $table) {

                    $table->dropForeign(
                        'profil_konseli_siswa_id_foreign'
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 5. HAPUS UNIQUE SISWA_ID LAMA
        |--------------------------------------------------------------------------
        |
        | Sebelumnya:
        |
        | siswa_id UNIQUE
        |
        | Sekarang akan menjadi:
        |
        | tahun_ajaran_id + siswa_id UNIQUE
        |
        */

        $uniqueExists = DB::select(
            "
            SELECT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'profil_konseli'
            AND INDEX_NAME = 'profil_konseli_siswa_id_unique'
            "
        );


        if (!empty($uniqueExists)) {

            Schema::table(
                'profil_konseli',
                function (Blueprint $table) {

                    $table->dropUnique(
                        'profil_konseli_siswa_id_unique'
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 6. BUAT KEMBALI FOREIGN KEY SISWA
        |--------------------------------------------------------------------------
        */

        Schema::table(
            'profil_konseli',
            function (Blueprint $table) {

                $table->foreign('siswa_id')
                    ->references('id')
                    ->on('siswa')
                    ->cascadeOnDelete();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | 7. UNIQUE PER TAHUN AJARAN
        |--------------------------------------------------------------------------
        |
        | Sekarang satu siswa bisa memiliki profil berbeda pada
        | tahun ajaran berbeda.
        |
        | Contoh:
        |
        | 2026/2027 + siswa 10 → boleh
        | 2027/2028 + siswa 10 → boleh
        |
        | Tetapi:
        |
        | 2026/2027 + siswa 10 → tidak boleh dua kali.
        |
        */

        $compositeUniqueExists = DB::select(
            "
            SELECT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'profil_konseli'
            AND INDEX_NAME = 'profil_konseli_tahun_siswa_unique'
            "
        );


        if (empty($compositeUniqueExists)) {

            Schema::table(
                'profil_konseli',
                function (Blueprint $table) {

                    $table->unique(
                        [
                            'tahun_ajaran_id',
                            'siswa_id',
                        ],
                        'profil_konseli_tahun_siswa_unique'
                    );

                }
            );
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | HAPUS UNIQUE GABUNGAN
        |--------------------------------------------------------------------------
        */

        if (Schema::hasTable('profil_konseli')) {

            $compositeUniqueExists = DB::select(
                "
                SELECT INDEX_NAME
                FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'profil_konseli'
                AND INDEX_NAME = 'profil_konseli_tahun_siswa_unique'
                "
            );


            if (!empty($compositeUniqueExists)) {

                Schema::table(
                    'profil_konseli',
                    function (Blueprint $table) {

                        $table->dropUnique(
                            'profil_konseli_tahun_siswa_unique'
                        );

                    }
                );
            }


            /*
            |--------------------------------------------------------------------------
            | HAPUS FOREIGN KEY SISWA
            |--------------------------------------------------------------------------
            */

            Schema::table(
                'profil_konseli',
                function (Blueprint $table) {

                    $table->dropForeign(
                        'profil_konseli_siswa_id_foreign'
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | BUAT KEMBALI UNIQUE SISWA_ID
            |--------------------------------------------------------------------------
            */

            Schema::table(
                'profil_konseli',
                function (Blueprint $table) {

                    $table->unique(
                        'siswa_id'
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | HAPUS FOREIGN KEY TAHUN AJARAN
            |--------------------------------------------------------------------------
            */

            Schema::table(
                'profil_konseli',
                function (Blueprint $table) {

                    $table->dropForeign(
                        'profil_konseli_tahun_ajaran_id_foreign'
                    );

                    $table->dropColumn(
                        'tahun_ajaran_id'
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | BUAT KEMBALI FOREIGN KEY SISWA
            |--------------------------------------------------------------------------
            */

            Schema::table(
                'profil_konseli',
                function (Blueprint $table) {

                    $table->foreign('siswa_id')
                        ->references('id')
                        ->on('siswa')
                        ->cascadeOnDelete();

                }
            );
        }
    }
};