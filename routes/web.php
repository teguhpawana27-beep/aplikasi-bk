<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ======================================================
// CONTROLLERS
// ======================================================

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ProfilKonseliController;
use App\Http\Controllers\DataBmwController;
use App\Http\Controllers\DataSnpmBController;
use App\Http\Controllers\AsesmenAwalController;
use App\Http\Controllers\LayananDasarController;
use App\Http\Controllers\PeminatanPerencanaanController;
use App\Http\Controllers\LayananResponsifController;
use App\Http\Controllers\DukunganSistemController;
use App\Http\Controllers\ArsipSuratController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserController;


// ======================================================
// HALAMAN AWAL
// ======================================================

Route::get('/', function () {
    return redirect()->route('dashboard');
});


// ======================================================
// DASHBOARD
// ADMIN + GURU BK + KEPALA SEKOLAH
// ======================================================

Route::get('/dashboard', function () {

    /*
    |--------------------------------------------------------------------------
    | TAHUN AJARAN AKTIF
    |--------------------------------------------------------------------------
    */

    $tahunAjaranId = session('tahun_ajaran_id');


    /*
    |--------------------------------------------------------------------------
    | TOTAL SISWA
    |--------------------------------------------------------------------------
    */

    $totalSiswa = 0;

    if ($tahunAjaranId) {

        $totalSiswa = \App\Models\RiwayatKelasSiswa::query()
            ->whereHas('kelas', function ($query) use ($tahunAjaranId) {

                $query->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );

            })
            ->whereHas('siswa', function ($query) {

                $query->where(
                    'status',
                    'aktif'
                );

            })
            ->distinct()
            ->count('siswa_id');
    }


    /*
    |--------------------------------------------------------------------------
    | LAYANAN BK
    |--------------------------------------------------------------------------
    */

    $layananDasar = 0;
    $peminatan = 0;
    $responsif = 0;

    if ($tahunAjaranId) {

        $layananDasar = \App\Models\LayananDasar::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->count();


        $peminatan = \App\Models\PeminatanPerencanaan::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->count();


        $responsif = \App\Models\LayananResponsif::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->count();
    }


    $layananBK =
        $layananDasar +
        $peminatan +
        $responsif;


    /*
    |--------------------------------------------------------------------------
    | KASUS RESPONSIF
    |--------------------------------------------------------------------------
    */

    $kasusResponsif = $responsif;


    /*
    |--------------------------------------------------------------------------
    | PROGRAM BK
    |--------------------------------------------------------------------------
    */

    $programBK = 0;

    if ($tahunAjaranId) {

        $programBK = \App\Models\DukunganSistem::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->count();
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'dashboard',
        compact(
            'totalSiswa',
            'layananBK',
            'kasusResponsif',
            'programBK'
        )
    );

})
    ->middleware([
        'auth',
        'verified',
        'role:Admin,Guru BK,Kepala Sekolah',
        'tahun.ajaran'
    ])
    ->name('dashboard');


// ======================================================
// PROFILE USER
// ADMIN + GURU BK
// ======================================================

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [
            ProfileController::class,
            'edit'
        ]
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [
            ProfileController::class,
            'update'
        ]
    )->name('profile.update');


    Route::delete(
        '/profile',
        [
            ProfileController::class,
            'destroy'
        ]
    )->name('profile.destroy');

});


// ======================================================
// MANAJEMEN PENGGUNA
// ADMIN ONLY
// ======================================================

Route::middleware([
    'auth',
    'role:Admin',
])->group(function () {

    Route::resource(
        'users',
        UserController::class
    )->except([
        'show'
    ]);

});


// ======================================================
// ======================================================
// ADMIN + GURU BK
// KELOLA TAHUN AJARAN
// ======================================================
// ======================================================

Route::middleware([
    'auth',
    'role:Admin,Guru BK',
    'tahun.ajaran'
])->group(function () {


    // ==================================================
    // TAHUN AJARAN
    // ==================================================


    // --------------------------------------------------
    // PILIH / GANTI TAHUN AJARAN
    // --------------------------------------------------

    Route::post(
        '/tahun-ajaran/pilih',
        [
            TahunAjaranController::class,
            'pilih'
        ]
    )->name('tahun-ajaran.pilih');


    // --------------------------------------------------
    // DAFTAR TAHUN AJARAN
    // --------------------------------------------------

    Route::get(
        '/tahun-ajaran',
        [
            TahunAjaranController::class,
            'index'
        ]
    )->name('tahun-ajaran.index');


    // --------------------------------------------------
    // FORM TAMBAH
    // --------------------------------------------------

    Route::get(
        '/tahun-ajaran/create',
        [
            TahunAjaranController::class,
            'create'
        ]
    )->name('tahun-ajaran.create');


    // --------------------------------------------------
    // SIMPAN
    // --------------------------------------------------

    Route::post(
        '/tahun-ajaran',
        [
            TahunAjaranController::class,
            'store'
        ]
    )->name('tahun-ajaran.store');


    // --------------------------------------------------
    // FORM EDIT
    // --------------------------------------------------

    Route::get(
        '/tahun-ajaran/{tahunAjaran}/edit',
        [
            TahunAjaranController::class,
            'edit'
        ]
    )->name('tahun-ajaran.edit');


    // --------------------------------------------------
    // UPDATE
    // --------------------------------------------------

    Route::put(
        '/tahun-ajaran/{tahunAjaran}',
        [
            TahunAjaranController::class,
            'update'
        ]
    )->name('tahun-ajaran.update');


    // --------------------------------------------------
    // HAPUS
    // --------------------------------------------------

    Route::delete(
        '/tahun-ajaran/{tahunAjaran}',
        [
            TahunAjaranController::class,
            'destroy'
        ]
    )->name('tahun-ajaran.destroy');


    // --------------------------------------------------
    // AKTIFKAN
    // --------------------------------------------------

    Route::post(
        '/tahun-ajaran/{tahunAjaran}/activate',
        [
            TahunAjaranController::class,
            'activate'
        ]
    )->name('tahun-ajaran.activate');

});


// ======================================================
// ======================================================
// ADMIN + GURU BK
// SELURUH FITUR BK
// ======================================================
// ======================================================

Route::middleware([
    'auth',
    'role:Admin,Guru BK',
    'tahun.ajaran'
])->group(function () {


    // ==================================================
    // 1. HIMPUNAN DATA
    // ==================================================


    // --------------------------------------------------
    // DATA SISWA
    // --------------------------------------------------

    Route::get(
        '/siswa/template',
        [
            SiswaController::class,
            'template'
        ]
    )->name('siswa.template');


    Route::post(
        '/siswa/import',
        [
            SiswaController::class,
            'import'
        ]
    )->name('siswa.import');


    Route::resource(
        'siswa',
        SiswaController::class
    );


    // ==================================================
    // PROFIL KONSELI
    // ==================================================


    // --------------------------------------------------
    // PDF PROFIL KONSELI
    // --------------------------------------------------

    Route::get(
        '/profil-konseli/{profilKonseli}/pdf',
        [
            ProfilKonseliController::class,
            'downloadPdf'
        ]
    )->name('profil-konseli.pdf');


    // --------------------------------------------------
    // AJAX PROFIL KONSELI
    // TAHUN AJARAN + TINGKAT -> JURUSAN
    // --------------------------------------------------

    Route::get(
        '/profil-konseli/jurusan',
        function (Request $request) {

            $tahunAjaranId =
                $request->get(
                    'tahun_ajaran_id'
                );

            $tingkat =
                $request->get(
                    'tingkat'
                );


            $query =
                \App\Models\Kelas::query()
                    ->where(
                        'is_active',
                        true
                    );


            if ($tahunAjaranId) {

                $query->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );
            }


            if ($tingkat) {

                $query->where(
                    'tingkat',
                    $tingkat
                );
            }


            $jurusanIds =
                $query
                    ->whereNotNull(
                        'jurusan_id'
                    )
                    ->pluck(
                        'jurusan_id'
                    )
                    ->unique();


            return \App\Models\Jurusan::query()
                ->whereIn(
                    'id',
                    $jurusanIds
                )
                ->where(
                    'is_active',
                    true
                )
                ->orderBy(
                    'nama'
                )
                ->get([
                    'id',
                    'kode',
                    'nama'
                ]);
        }
    )->name(
        'profil-konseli.jurusan'
    );


    // --------------------------------------------------
    // AJAX PROFIL KONSELI
    // TAHUN AJARAN + TINGKAT + JURUSAN -> KELAS
    // --------------------------------------------------

    Route::get(
        '/profil-konseli/kelas',
        function (Request $request) {

            $tahunAjaranId =
                $request->get(
                    'tahun_ajaran_id'
                );

            $tingkat =
                $request->get(
                    'tingkat'
                );

            $jurusanId =
                $request->get(
                    'jurusan_id'
                );


            $query =
                \App\Models\Kelas::query()
                    ->where(
                        'is_active',
                        true
                    );


            if ($tahunAjaranId) {

                $query->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );
            }


            if ($tingkat) {

                $query->where(
                    'tingkat',
                    $tingkat
                );
            }


            if ($jurusanId) {

                $query->where(
                    'jurusan_id',
                    $jurusanId
                );
            }


            return $query
                ->orderBy(
                    'nama_kelas'
                )
                ->get([
                    'id',
                    'jurusan_id',
                    'tingkat',
                    'nama_kelas'
                ]);
        }
    )->name(
        'profil-konseli.kelas'
    );


    // --------------------------------------------------
    // AJAX PROFIL KONSELI
    // KELAS -> SISWA
    // --------------------------------------------------

    Route::get(
        '/profil-konseli/siswa',
        function (Request $request) {

            $kelasId =
                $request->get(
                    'kelas_id'
                );


            if (!$kelasId) {

                return response()->json([]);
            }


            $siswaIds =
                \App\Models\RiwayatKelasSiswa::query()
                    ->where(
                        'kelas_id',
                        $kelasId
                    )
                    ->where(function ($query) {

                        $query
                            ->whereNull(
                                'tanggal_selesai'
                            )
                            ->orWhere(
                                'tanggal_selesai',
                                '>=',
                                now()->toDateString()
                            );
                    })
                    ->pluck(
                        'siswa_id'
                    )
                    ->unique();


            return \App\Models\Siswa::query()
                ->whereIn(
                    'id',
                    $siswaIds
                )
                ->where(
                    'status',
                    'aktif'
                )
                ->orderBy(
                    'nama_lengkap'
                )
                ->get([
                    'id',
                    'nis',
                    'nisn',
                    'nama_lengkap',
                    'jenis_kelamin',
                    'status'
                ]);
        }
    )->name(
        'profil-konseli.siswa'
    );


    Route::resource(
        'profil-konseli',
        ProfilKonseliController::class
    );


    // ==================================================
    // DATA BMW
    // ==================================================

    Route::get(
        '/data-bmw/{dataBmw}/pdf',
        [
            DataBmwController::class,
            'downloadPdf'
        ]
    )->name('data-bmw.pdf');


    Route::resource(
        'data-bmw',
        DataBmwController::class
    );


    // ==================================================
    // DATA SNPMB
    // ==================================================

    Route::get(
        '/data-snpmb/{dataSnpmB}/pdf',
        [
            DataSnpmBController::class,
            'downloadPdf'
        ]
    )->name('data-snpmb.pdf');


    Route::resource(
        'data-snpmb',
        DataSnpmBController::class
    );


    // ==================================================
    // ASESMEN AWAL
    // ==================================================

    Route::get(
        '/asesmen-awal/{asesmenAwal}/pdf',
        [
            AsesmenAwalController::class,
            'downloadPdf'
        ]
    )->name('asesmen-awal.pdf');


    Route::resource(
        'asesmen-awal',
        AsesmenAwalController::class
    );


    // ==================================================
    // 2. LAYANAN DASAR
    // ==================================================

    Route::get(
        '/layanan-dasar/{layananDasar}/pdf',
        [
            LayananDasarController::class,
            'downloadPdf'
        ]
    )->name('layanan-dasar.pdf');


    Route::resource(
        'layanan-dasar',
        LayananDasarController::class
    );


    // ==================================================
    // 3. PEMINATAN DAN PERENCANAAN
    // ==================================================


    // --------------------------------------------------
    // AJAX PEMINATAN
    // TAHUN AJARAN + TINGKAT -> JURUSAN
    // --------------------------------------------------

    Route::get(
        '/peminatan-perencanaan/jurusan',
        function (Request $request) {

            $tahunAjaranId =
                $request->get(
                    'tahun_ajaran_id'
                );

            $tingkat =
                $request->get(
                    'tingkat'
                );


            if (
                !$tahunAjaranId ||
                !$tingkat
            ) {

                return response()->json([]);
            }


            $jurusanIds =
                \App\Models\Kelas::query()
                    ->where(
                        'tahun_ajaran_id',
                        $tahunAjaranId
                    )
                    ->where(
                        'tingkat',
                        $tingkat
                    )
                    ->where(
                        'is_active',
                        true
                    )
                    ->whereNotNull(
                        'jurusan_id'
                    )
                    ->pluck(
                        'jurusan_id'
                    )
                    ->unique();


            return \App\Models\Jurusan::query()
                ->whereIn(
                    'id',
                    $jurusanIds
                )
                ->where(
                    'is_active',
                    true
                )
                ->orderBy(
                    'nama'
                )
                ->get([
                    'id',
                    'kode',
                    'nama'
                ]);
        }
    )->name(
        'peminatan-perencanaan.jurusan'
    );


    // --------------------------------------------------
    // AJAX PEMINATAN
    // TAHUN AJARAN + TINGKAT + JURUSAN -> KELAS
    // --------------------------------------------------

    Route::get(
        '/peminatan-perencanaan/kelas',
        function (Request $request) {

            $tahunAjaranId =
                $request->get(
                    'tahun_ajaran_id'
                );

            $tingkat =
                $request->get(
                    'tingkat'
                );

            $jurusanId =
                $request->get(
                    'jurusan_id'
                );


            if (
                !$tahunAjaranId ||
                !$tingkat ||
                !$jurusanId
            ) {

                return response()->json([]);
            }


            return \App\Models\Kelas::query()
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->where(
                    'tingkat',
                    $tingkat
                )
                ->where(
                    'jurusan_id',
                    $jurusanId
                )
                ->where(
                    'is_active',
                    true
                )
                ->orderBy(
                    'nama_kelas'
                )
                ->get([
                    'id',
                    'tahun_ajaran_id',
                    'jurusan_id',
                    'tingkat',
                    'nama_kelas'
                ]);
        }
    )->name(
        'peminatan-perencanaan.kelas'
    );


    // --------------------------------------------------
    // AJAX PEMINATAN
    // KELAS -> SISWA
    // --------------------------------------------------

    Route::get(
        '/peminatan-perencanaan/siswa',
        function (Request $request) {

            $kelasId =
                $request->get(
                    'kelas_id'
                );


            if (!$kelasId) {

                return response()->json([]);
            }


            $siswaIds =
                \App\Models\RiwayatKelasSiswa::query()
                    ->where(
                        'kelas_id',
                        $kelasId
                    )
                    ->where(function ($query) {

                        $query
                            ->whereNull(
                                'tanggal_selesai'
                            )
                            ->orWhere(
                                'tanggal_selesai',
                                '>=',
                                now()->toDateString()
                            );
                    })
                    ->pluck(
                        'siswa_id'
                    )
                    ->unique();


            return \App\Models\Siswa::query()
                ->whereIn(
                    'id',
                    $siswaIds
                )
                ->where(
                    'status',
                    'aktif'
                )
                ->orderBy(
                    'nama_lengkap'
                )
                ->get([
                    'id',
                    'nis',
                    'nisn',
                    'nama_lengkap',
                    'jenis_kelamin',
                    'status'
                ]);
        }
    )->name(
        'peminatan-perencanaan.siswa'
    );


    // --------------------------------------------------
    // PDF PEMINATAN
    // --------------------------------------------------

    Route::get(
        '/peminatan-perencanaan/{peminatanPerencanaan}/pdf',
        [
            PeminatanPerencanaanController::class,
            'downloadPdf'
        ]
    )->name(
        'peminatan-perencanaan.pdf'
    );


    Route::resource(
        'peminatan-perencanaan',
        PeminatanPerencanaanController::class
    );


    // ==================================================
    // 4. LAYANAN RESPONSIF
    // ==================================================

    Route::get(
        '/layanan-responsif/{layananResponsif}/pdf',
        [
            LayananResponsifController::class,
            'downloadPdf'
        ]
    )->name(
        'layanan-responsif.pdf'
    );


    Route::resource(
        'layanan-responsif',
        LayananResponsifController::class
    );


    // ==================================================
    // 5. DUKUNGAN SISTEM
    // ==================================================


    // --------------------------------------------------
    // AJAX
    // TAHUN AJARAN -> TINGKAT
    // --------------------------------------------------

    Route::get(
        '/dukungan-sistem/tingkat',
        function (Request $request) {

            if (
                !$request->filled(
                    'tahun_ajaran_id'
                )
            ) {

                return response()->json([]);
            }


            return \App\Models\Kelas::query()
                ->where(
                    'tahun_ajaran_id',
                    $request->tahun_ajaran_id
                )
                ->where(
                    'is_active',
                    true
                )
                ->whereNotNull(
                    'tingkat'
                )
                ->select(
                    'tingkat'
                )
                ->distinct()
                ->orderBy(
                    'tingkat'
                )
                ->pluck(
                    'tingkat'
                )
                ->values();
        }
    )->name(
        'dukungan-sistem.tingkat'
    );


    // --------------------------------------------------
    // AJAX
    // TAHUN AJARAN + TINGKAT -> JURUSAN
    // --------------------------------------------------

    Route::get(
        '/dukungan-sistem/jurusan',
        [
            DukunganSistemController::class,
            'getJurusan'
        ]
    )->name(
        'dukungan-sistem.jurusan'
    );


    // --------------------------------------------------
    // AJAX
    // TAHUN AJARAN + TINGKAT + JURUSAN -> KELAS
    // --------------------------------------------------

    Route::get(
        '/dukungan-sistem/kelas',
        [
            DukunganSistemController::class,
            'getKelas'
        ]
    )->name(
        'dukungan-sistem.kelas'
    );


    // --------------------------------------------------
    // AJAX
    // KELAS -> SISWA
    // --------------------------------------------------

    Route::get(
        '/dukungan-sistem/siswa',
        [
            DukunganSistemController::class,
            'getSiswa'
        ]
    )->name(
        'dukungan-sistem.siswa'
    );


    // --------------------------------------------------
    // PDF DUKUNGAN SISTEM
    // --------------------------------------------------

    Route::get(
        '/dukungan-sistem/{dukunganSistem}/pdf',
        [
            DukunganSistemController::class,
            'downloadPdf'
        ]
    )->name(
        'dukungan-sistem.pdf'
    );


    // --------------------------------------------------
    // CRUD DUKUNGAN SISTEM
    // --------------------------------------------------

    Route::resource(
        'dukungan-sistem',
        DukunganSistemController::class
    );


    // ==================================================
    // 6. ARSIP SURAT
    // ==================================================


    // --------------------------------------------------
    // AJAX
    // TAHUN AJARAN -> TINGKAT
    // --------------------------------------------------

    Route::get(
        '/arsip-surat/tingkat',
        function (Request $request) {

            if (
                !$request->filled(
                    'tahun_ajaran_id'
                )
            ) {

                return response()->json([]);
            }


            return \App\Models\Kelas::query()
                ->where(
                    'tahun_ajaran_id',
                    $request->tahun_ajaran_id
                )
                ->where(
                    'is_active',
                    true
                )
                ->whereNotNull(
                    'tingkat'
                )
                ->select(
                    'tingkat'
                )
                ->distinct()
                ->orderBy(
                    'tingkat'
                )
                ->pluck(
                    'tingkat'
                )
                ->values();
        }
    )->name(
        'arsip-surat.tingkat'
    );


    // --------------------------------------------------
    // AJAX
    // TAHUN AJARAN + TINGKAT -> JURUSAN
    // --------------------------------------------------

    Route::get(
        '/arsip-surat/jurusan',
        function (Request $request) {

            $tahunAjaranId =
                $request->get(
                    'tahun_ajaran_id'
                );

            $tingkat =
                $request->get(
                    'tingkat'
                );


            if (
                !$tahunAjaranId ||
                !$tingkat
            ) {

                return response()->json([]);
            }


            $jurusanIds =
                \App\Models\Kelas::query()
                    ->where(
                        'tahun_ajaran_id',
                        $tahunAjaranId
                    )
                    ->where(
                        'tingkat',
                        $tingkat
                    )
                    ->where(
                        'is_active',
                        true
                    )
                    ->whereNotNull(
                        'jurusan_id'
                    )
                    ->pluck(
                        'jurusan_id'
                    )
                    ->unique();


            return \App\Models\Jurusan::query()
                ->whereIn(
                    'id',
                    $jurusanIds
                )
                ->where(
                    'is_active',
                    true
                )
                ->orderBy(
                    'nama'
                )
                ->get([
                    'id',
                    'kode',
                    'nama'
                ]);
        }
    )->name(
        'arsip-surat.jurusan'
    );


    // --------------------------------------------------
    // AJAX
    // TAHUN AJARAN + TINGKAT + JURUSAN -> KELAS
    // --------------------------------------------------

    Route::get(
        '/arsip-surat/kelas',
        function (Request $request) {

            $tahunAjaranId =
                $request->get(
                    'tahun_ajaran_id'
                );

            $tingkat =
                $request->get(
                    'tingkat'
                );

            $jurusanId =
                $request->get(
                    'jurusan_id'
                );


            if (
                !$tahunAjaranId ||
                !$tingkat ||
                !$jurusanId
            ) {

                return response()->json([]);
            }


            return \App\Models\Kelas::query()
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->where(
                    'tingkat',
                    $tingkat
                )
                ->where(
                    'jurusan_id',
                    $jurusanId
                )
                ->where(
                    'is_active',
                    true
                )
                ->orderBy(
                    'nama_kelas'
                )
                ->get([
                    'id',
                    'tahun_ajaran_id',
                    'jurusan_id',
                    'tingkat',
                    'nama_kelas'
                ]);
        }
    )->name(
        'arsip-surat.kelas'
    );


    // --------------------------------------------------
    // AJAX
    // KELAS -> SISWA
    // --------------------------------------------------

    Route::get(
        '/arsip-surat/siswa',
        function (Request $request) {

            $kelasId =
                $request->get(
                    'kelas_id'
                );


            if (!$kelasId) {

                return response()->json([]);
            }


            $siswaIds =
                \App\Models\RiwayatKelasSiswa::query()
                    ->where(
                        'kelas_id',
                        $kelasId
                    )
                    ->where(function ($query) {

                        $query
                            ->whereNull(
                                'tanggal_selesai'
                            )
                            ->orWhere(
                                'tanggal_selesai',
                                '>=',
                                now()->toDateString()
                            );
                    })
                    ->pluck(
                        'siswa_id'
                    )
                    ->unique();


            return \App\Models\Siswa::query()
                ->whereIn(
                    'id',
                    $siswaIds
                )
                ->where(
                    'status',
                    'aktif'
                )
                ->orderBy(
                    'nama_lengkap'
                )
                ->get([
                    'id',
                    'nis',
                    'nisn',
                    'nama_lengkap',
                    'jenis_kelamin',
                    'status'
                ]);
        }
    )->name(
        'arsip-surat.siswa'
    );


    // --------------------------------------------------
    // FILE ARSIP SURAT
    // --------------------------------------------------

    Route::get(
        '/arsip-surat/{arsipSurat}/file',
        [
            ArsipSuratController::class,
            'file'
        ]
    )->name(
        'arsip-surat.file'
    );


    // --------------------------------------------------
    // PDF ARSIP SURAT
    // --------------------------------------------------

    Route::get(
        '/arsip-surat/{arsipSurat}/pdf',
        [
            ArsipSuratController::class,
            'downloadPdf'
        ]
    )->name(
        'arsip-surat.pdf'
    );


    // --------------------------------------------------
    // CRUD ARSIP SURAT
    // --------------------------------------------------

    Route::resource(
        'arsip-surat',
        ArsipSuratController::class
    );



});


// ======================================================
// LAPORAN
// ADMIN + GURU BK + KEPALA SEKOLAH
// ======================================================

Route::middleware([
    'auth',
    'role:Admin,Guru BK,Kepala Sekolah',
    'tahun.ajaran'
])->group(function () {


    // ==================================================
    // 7. LAPORAN
    // ADMIN + GURU BK
    // ==================================================


    // --------------------------------------------------
    // HALAMAN UTAMA LAPORAN
    // --------------------------------------------------

    Route::get(
        '/laporan',
        [
            LaporanController::class,
            'index'
        ]
    )->name(
        'laporan.index'
    );


    // --------------------------------------------------
    // REKAPAN PER KOMPONEN
    // --------------------------------------------------

    Route::get(
        '/laporan/komponen',
        [
            LaporanController::class,
            'komponen'
        ]
    )->name(
        'laporan.komponen'
    );


    // --------------------------------------------------
    // LAPORAN KEGIATAN BK
    // --------------------------------------------------

    Route::get(
        '/laporan/kegiatan',
        [
            LaporanController::class,
            'kegiatan'
        ]
    )->name(
        'laporan.kegiatan'
    );


    Route::get(
        '/laporan/kegiatan/download',
        [
            LaporanController::class,
            'downloadKegiatan'
        ]
    )->name(
        'laporan.kegiatan.download'
    );


    // --------------------------------------------------
    // LAPORAN PERKEMBANGAN SISWA
    // --------------------------------------------------

    Route::get(
        '/laporan/perkembangan',
        [
            LaporanController::class,
            'perkembangan'
        ]
    )->name(
        'laporan.perkembangan'
    );


    // --------------------------------------------------
    // AJAX LAPORAN
    // TAHUN AJARAN -> TINGKAT
    // --------------------------------------------------

    Route::get(
        '/laporan/tingkat',
        [
            LaporanController::class,
            'tingkat'
        ]
    )->name(
        'laporan.tingkat'
    );


    // --------------------------------------------------
    // AJAX LAPORAN
    // TAHUN AJARAN + TINGKAT -> JURUSAN
    // --------------------------------------------------

    Route::get(
        '/laporan/jurusan',
        [
            LaporanController::class,
            'jurusan'
        ]
    )->name(
        'laporan.jurusan'
    );


    // --------------------------------------------------
    // AJAX LAPORAN
    // TAHUN AJARAN + TINGKAT + JURUSAN -> KELAS
    // --------------------------------------------------

    Route::get(
        '/laporan/kelas',
        [
            LaporanController::class,
            'kelas'
        ]
    )->name(
        'laporan.kelas'
    );


    // --------------------------------------------------
    // AJAX LAPORAN
    // KELAS -> SISWA
    // --------------------------------------------------

    Route::get(
        '/laporan/siswa',
        [
            LaporanController::class,
            'siswa'
        ]
    )->name(
        'laporan.siswa'
    );


    // --------------------------------------------------
    // DOWNLOAD LAPORAN PERKEMBANGAN
    // --------------------------------------------------

    Route::get(
        '/laporan/perkembangan/download',
        [
            LaporanController::class,
            'downloadPerkembangan'
        ]
    )->name(
        'laporan.perkembangan.download'
    );


});


// ======================================================
// AUTH BREEZE
// ======================================================

require __DIR__ . '/auth.php';