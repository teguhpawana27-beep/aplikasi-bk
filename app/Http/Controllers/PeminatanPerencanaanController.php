<?php

namespace App\Http\Controllers;

use App\Models\PeminatanPerencanaan;
use App\Models\PesertaPeminatan;
use App\Models\GuruBK;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\BidangLayanan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminatanPerencanaanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAHUN AJARAN CONTEXT
    |--------------------------------------------------------------------------
    */

    private function getTahunAjaranAktif()
    {
        $tahunAjaranId = session('tahun_ajaran_id');

        /*
        |--------------------------------------------------------------------------
        | SESSION BELUM TERSEDIA
        |--------------------------------------------------------------------------
        */

        if (!$tahunAjaranId) {

            $tahunAjaran = TahunAjaran::where(
                'is_active',
                true
            )
                ->orderByDesc('tanggal_mulai')
                ->first();

            if (!$tahunAjaran) {

                $tahunAjaran = TahunAjaran::orderByDesc(
                    'tanggal_mulai'
                )->first();
            }

            if ($tahunAjaran) {

                session([
                    'tahun_ajaran_id' => $tahunAjaran->id,
                ]);

                return $tahunAjaran;
            }

            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL BERDASARKAN SESSION
        |--------------------------------------------------------------------------
        */

        $tahunAjaran = TahunAjaran::find(
            $tahunAjaranId
        );


        /*
        |--------------------------------------------------------------------------
        | SESSION TIDAK VALID
        |--------------------------------------------------------------------------
        */

        if (!$tahunAjaran) {

            $tahunAjaran = TahunAjaran::where(
                'is_active',
                true
            )
                ->orderByDesc('tanggal_mulai')
                ->first();

            if (!$tahunAjaran) {

                $tahunAjaran = TahunAjaran::orderByDesc(
                    'tanggal_mulai'
                )->first();
            }

            if ($tahunAjaran) {

                session([
                    'tahun_ajaran_id' => $tahunAjaran->id,
                ]);
            }
        }

        return $tahunAjaran;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | QUERY DATA
        |--------------------------------------------------------------------------
        */

        $query = PeminatanPerencanaan::query()
            ->with([
                'guruBK',
                'tahunAjaran',
                'kelas.jurusan',
                'bidangLayanan',
                'peserta.siswa',
            ])
            ->when(
                $tahunAjaranId,
                function ($query) use (
                    $tahunAjaranId
                ) {

                    $query->where(
                        'tahun_ajaran_id',
                        $tahunAjaranId
                    );
                }
            )
            ->latest();


        /*
        |--------------------------------------------------------------------------
        | FILTER JENIS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jenis')) {

            $query->where(
                'jenis_layanan',
                $request->jenis
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $peminatan = $query->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'peminatan-perencanaan.index',
            compact(
                'peminatan',
                'tahunAjaran',
                'tahunAjaranId'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $tahunAjaranAktif =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {

            return redirect()
                ->route('peminatan-perencanaan.index')
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaranAktif->id;


        /*
        |--------------------------------------------------------------------------
        | GURU BK
        |--------------------------------------------------------------------------
        */

        $guruBK = GuruBK::query()
            ->orderBy(
                'nama_lengkap'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            TahunAjaran::orderByDesc(
                'tanggal_mulai'
            )->get();


        /*
        |--------------------------------------------------------------------------
        | JURUSAN
        |--------------------------------------------------------------------------
        */

        $jurusans =
            Jurusan::where(
                'is_active',
                1
            )
                ->orderBy('nama')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        |
        | Hanya kelas dari Tahun Ajaran yang sedang dipilih.
        |
        */

        $kelasList = Kelas::query()
            ->join(
                'tahun_ajaran',
                'kelas.tahun_ajaran_id',
                '=',
                'tahun_ajaran.id'
            )
            ->join(
                'jurusan',
                'kelas.jurusan_id',
                '=',
                'jurusan.id'
            )
            ->where(
                'kelas.tahun_ajaran_id',
                $tahunAjaranId
            )
            ->where(
                'kelas.is_active',
                1
            )
            ->select(
                'kelas.id',
                'kelas.tahun_ajaran_id',
                'kelas.jurusan_id',
                'kelas.tingkat',
                'kelas.nama_kelas',
                'jurusan.kode as jurusan_kode',
                'jurusan.nama as jurusan_nama',
                'tahun_ajaran.nama as tahun_ajaran_nama'
            )
            ->orderBy(
                'kelas.tingkat'
            )
            ->orderBy(
                'jurusan.nama'
            )
            ->orderBy(
                'kelas.nama_kelas'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SISWA BERDASARKAN KELAS
        |--------------------------------------------------------------------------
        |
        | Hanya siswa yang memiliki riwayat kelas
        | pada Tahun Ajaran aktif.
        |
        */

        $siswaKelas = DB::table(
            'riwayat_kelas_siswa'
        )
            ->join(
                'siswa',
                'siswa.id',
                '=',
                'riwayat_kelas_siswa.siswa_id'
            )
            ->join(
                'kelas',
                'kelas.id',
                '=',
                'riwayat_kelas_siswa.kelas_id'
            )
            ->join(
                'jurusan',
                'jurusan.id',
                '=',
                'kelas.jurusan_id'
            )
            ->join(
                'tahun_ajaran',
                'tahun_ajaran.id',
                '=',
                'kelas.tahun_ajaran_id'
            )
            ->where(
                'kelas.tahun_ajaran_id',
                $tahunAjaranId
            )
            ->where(
                'kelas.is_active',
                1
            )
            ->select(
                'siswa.id as siswa_id',
                'siswa.nis',
                'siswa.nisn',
                'siswa.nama_lengkap',
                'siswa.jenis_kelamin',
                'siswa.status as status_siswa',

                'kelas.id as kelas_id',
                'kelas.tahun_ajaran_id',
                'kelas.jurusan_id',
                'kelas.tingkat',
                'kelas.nama_kelas',

                'jurusan.kode as jurusan_kode',
                'jurusan.nama as jurusan_nama',

                'tahun_ajaran.nama as tahun_ajaran_nama'
            )
            ->orderBy(
                'siswa.nama_lengkap'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SEMUA SISWA
        |--------------------------------------------------------------------------
        |
        | Tetap disediakan untuk kompatibilitas Blade.
        | Tetapi hanya siswa pada Tahun Ajaran aktif.
        |
        */

        $siswa = Siswa::query()
            ->whereHas(
                'riwayatKelas.kelas',
                function ($query) use (
                    $tahunAjaranId
                ) {

                    $query->where(
                        'tahun_ajaran_id',
                        $tahunAjaranId
                    )
                    ->where(
                        'is_active',
                        1
                    );
                }
            )
            ->orderBy(
                'nama_lengkap'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BIDANG LAYANAN
        |--------------------------------------------------------------------------
        */

        $bidangLayanan =
            BidangLayanan::where(
                'is_active',
                1
            )
                ->orderBy('nama')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'peminatan-perencanaan.create',
            compact(
                'guruBK',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'jurusans',
                'kelasList',
                'siswaKelas',
                'siswa',
                'bidangLayanan'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {

            return redirect()
                ->route('peminatan-perencanaan.index')
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaran->id;


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [

                'guru_bk_id' => [
                    'required',
                    'exists:guru_bk,id',
                ],

                'kelas_id' => [
                    'nullable',
                    'exists:kelas,id',
                ],

                'bidang_layanan_id' => [
                    'required',
                    'exists:bidang_layanan,id',
                ],

                'jenis_layanan' => [
                    'required',
                    'in:Bimbingan Klasikal,Bimbingan Kelas Besar,Bimbingan Kelompok,Konseling Individu,Konseling Kelompok,Konsultasi,Kolaborasi',
                ],

                'sasaran' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'uraian_kegiatan' => [
                    'nullable',
                    'string',
                ],

                'tindak_lanjut' => [
                    'nullable',
                    'string',
                ],

                'keterangan' => [
                    'nullable',
                    'string',
                ],

                'tanggal' => [
                    'required',
                    'date',
                ],

                'siswa_id' => [
                    'nullable',
                    'array',
                ],

                'siswa_id.*' => [
                    'exists:siswa,id',
                ],
            ],
            [

                'guru_bk_id.required' =>
                    'Guru BK wajib dipilih.',

                'bidang_layanan_id.required' =>
                    'Bidang layanan wajib dipilih.',

                'jenis_layanan.required' =>
                    'Jenis layanan wajib dipilih.',

                'tanggal.required' =>
                    'Tanggal wajib diisi.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI KELAS
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['kelas_id'])) {

            $kelasValid = Kelas::query()
                ->where(
                    'id',
                    $validated['kelas_id']
                )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->where(
                    'is_active',
                    1
                )
                ->exists();

            if (!$kelasValid) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Kelas yang dipilih tidak sesuai dengan Tahun Ajaran ' .
                        $tahunAjaran->nama .
                        '.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SISWA
        |--------------------------------------------------------------------------
        */

        $siswaIds =
            $validated['siswa_id'] ?? [];


        if (!empty($siswaIds)) {

            $siswaIdsUnik =
                array_unique(
                    array_filter($siswaIds)
                );


            $jumlahSiswaValid = Siswa::query()
                ->whereIn(
                    'id',
                    $siswaIdsUnik
                )
                ->whereHas(
                    'riwayatKelas.kelas',
                    function ($query) use (
                        $tahunAjaranId
                    ) {

                        $query->where(
                            'tahun_ajaran_id',
                            $tahunAjaranId
                        )
                        ->where(
                            'is_active',
                            1
                        );
                    }
                )
                ->count();


            if (
                $jumlahSiswaValid
                !== count($siswaIdsUnik)
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Terdapat siswa yang tidak terdaftar pada Tahun Ajaran ' .
                        $tahunAjaran->nama .
                        '.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SET TAHUN AJARAN OTOMATIS
        |--------------------------------------------------------------------------
        */

        $validated['tahun_ajaran_id'] =
            $tahunAjaranId;


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $validated,
                $siswaIds
            ) {

                unset(
                    $validated['siswa_id']
                );


                /*
                |--------------------------------------------------------------------------
                | SIMPAN DATA PEMINATAN
                |--------------------------------------------------------------------------
                */

                $peminatan =
                    PeminatanPerencanaan::create(
                        $validated
                    );


                /*
                |--------------------------------------------------------------------------
                | SIMPAN PESERTA
                |--------------------------------------------------------------------------
                */

                foreach (
                    array_unique(
                        array_filter($siswaIds)
                    )
                    as $siswaId
                ) {

                    PesertaPeminatan::create([
                        'peminatan_perencanaan_id' =>
                            $peminatan->id,

                        'siswa_id' =>
                            $siswaId,
                    ]);
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'peminatan-perencanaan.index'
            )
            ->with(
                'success',
                'Data peminatan dan perencanaan individu berhasil ditambahkan pada Tahun Ajaran ' .
                $tahunAjaran->nama .
                '.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        PeminatanPerencanaan $peminatanPerencanaan
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | BATASI DATA BERDASARKAN TAHUN
        |--------------------------------------------------------------------------
        */

        if (
            !$tahunAjaranId ||
            $peminatanPerencanaan->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD RELASI
        |--------------------------------------------------------------------------
        */

        $peminatanPerencanaan->load([
            'guruBK',
            'tahunAjaran',
            'kelas.jurusan',
            'bidangLayanan',
            'peserta.siswa',
        ]);


        /*
        |--------------------------------------------------------------------------
        | NAMA VARIABEL SESUAI BLADE
        |--------------------------------------------------------------------------
        */

        $peminatan =
            $peminatanPerencanaan;


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'peminatan-perencanaan.show',
            compact(
                'peminatan'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        PeminatanPerencanaan $peminatanPerencanaan
    ) {

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $tahunAjaranAktif =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {

            return redirect()
                ->route(
                    'peminatan-perencanaan.index'
                )
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaranAktif->id;


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN DATA SESUAI TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (
            $peminatanPerencanaan->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | DATA UTAMA
        |--------------------------------------------------------------------------
        */

        $peminatan =
            $peminatanPerencanaan->load([
                'peserta.siswa',
                'kelas.jurusan',
                'bidangLayanan',
                'guruBK',
                'tahunAjaran',
            ]);


        /*
        |--------------------------------------------------------------------------
        | GURU BK
        |--------------------------------------------------------------------------
        */

        $guruBK =
            GuruBK::orderBy(
                'nama_lengkap'
            )->get();


        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            TahunAjaran::orderByDesc(
                'tanggal_mulai'
            )->get();


        /*
        |--------------------------------------------------------------------------
        | JURUSAN
        |--------------------------------------------------------------------------
        */

        $jurusans =
            Jurusan::where(
                'is_active',
                1
            )
                ->orderBy('nama')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        */

        $kelasList = Kelas::query()
            ->join(
                'tahun_ajaran',
                'kelas.tahun_ajaran_id',
                '=',
                'tahun_ajaran.id'
            )
            ->join(
                'jurusan',
                'kelas.jurusan_id',
                '=',
                'jurusan.id'
            )
            ->where(
                'kelas.tahun_ajaran_id',
                $tahunAjaranId
            )
            ->where(
                'kelas.is_active',
                1
            )
            ->select(
                'kelas.id',
                'kelas.tahun_ajaran_id',
                'kelas.jurusan_id',
                'kelas.tingkat',
                'kelas.nama_kelas',
                'jurusan.kode as jurusan_kode',
                'jurusan.nama as jurusan_nama',
                'tahun_ajaran.nama as tahun_ajaran_nama'
            )
            ->orderBy(
                'kelas.tingkat'
            )
            ->orderBy(
                'jurusan.nama'
            )
            ->orderBy(
                'kelas.nama_kelas'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

        $siswaKelas = DB::table(
            'riwayat_kelas_siswa'
        )
            ->join(
                'siswa',
                'siswa.id',
                '=',
                'riwayat_kelas_siswa.siswa_id'
            )
            ->join(
                'kelas',
                'kelas.id',
                '=',
                'riwayat_kelas_siswa.kelas_id'
            )
            ->join(
                'jurusan',
                'jurusan.id',
                '=',
                'kelas.jurusan_id'
            )
            ->join(
                'tahun_ajaran',
                'tahun_ajaran.id',
                '=',
                'kelas.tahun_ajaran_id'
            )
            ->where(
                'kelas.tahun_ajaran_id',
                $tahunAjaranId
            )
            ->where(
                'kelas.is_active',
                1
            )
            ->select(
                'siswa.id as siswa_id',
                'siswa.nis',
                'siswa.nisn',
                'siswa.nama_lengkap',
                'siswa.jenis_kelamin',
                'siswa.status as status_siswa',

                'kelas.id as kelas_id',
                'kelas.tahun_ajaran_id',
                'kelas.jurusan_id',
                'kelas.tingkat',
                'kelas.nama_kelas',

                'jurusan.kode as jurusan_kode',
                'jurusan.nama as jurusan_nama',

                'tahun_ajaran.nama as tahun_ajaran_nama'
            )
            ->orderBy(
                'siswa.nama_lengkap'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SEMUA SISWA
        |--------------------------------------------------------------------------
        */

        $siswa =
            Siswa::query()
                ->whereHas(
                    'riwayatKelas.kelas',
                    function ($query) use (
                        $tahunAjaranId
                    ) {

                        $query->where(
                            'tahun_ajaran_id',
                            $tahunAjaranId
                        )
                        ->where(
                            'is_active',
                            1
                        );
                    }
                )
                ->orderBy(
                    'nama_lengkap'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | BIDANG LAYANAN
        |--------------------------------------------------------------------------
        */

        $bidangLayanan =
            BidangLayanan::where(
                'is_active',
                1
            )
                ->orderBy('nama')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | PESERTA TERPILIH
        |--------------------------------------------------------------------------
        */

        $pesertaTerpilih =
            $peminatan
                ->peserta
                ->pluck('siswa_id')
                ->map(
                    fn ($id) => (int) $id
                )
                ->values()
                ->toArray();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'peminatan-perencanaan.edit',
            compact(
                'peminatan',
                'guruBK',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'jurusans',
                'kelasList',
                'siswaKelas',
                'siswa',
                'bidangLayanan',
                'pesertaTerpilih'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        PeminatanPerencanaan $peminatanPerencanaan
    ) {

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {

            return redirect()
                ->route(
                    'peminatan-perencanaan.index'
                )
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaran->id;


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN DATA SESUAI TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (
            $peminatanPerencanaan->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'guru_bk_id' =>
                'required|exists:guru_bk,id',

            'kelas_id' =>
                'nullable|exists:kelas,id',

            'bidang_layanan_id' =>
                'required|exists:bidang_layanan,id',

            'jenis_layanan' =>
                'required|in:Bimbingan Klasikal,Bimbingan Kelas Besar,Bimbingan Kelompok,Konseling Individu,Konseling Kelompok,Konsultasi,Kolaborasi',

            'sasaran' =>
                'nullable|string|max:255',

            'uraian_kegiatan' =>
                'nullable|string',

            'tindak_lanjut' =>
                'nullable|string',

            'keterangan' =>
                'nullable|string',

            'tanggal' =>
                'required|date',

            'siswa_id' =>
                'nullable|array',

            'siswa_id.*' =>
                'exists:siswa,id',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDASI KELAS
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['kelas_id'])) {

            $kelasValid = Kelas::query()
                ->where(
                    'id',
                    $validated['kelas_id']
                )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->where(
                    'is_active',
                    1
                )
                ->exists();

            if (!$kelasValid) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Kelas yang dipilih tidak sesuai dengan Tahun Ajaran ' .
                        $tahunAjaran->nama .
                        '.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SISWA
        |--------------------------------------------------------------------------
        */

        $siswaIds =
            $validated['siswa_id'] ?? [];


        $siswaIdsUnik =
            array_unique(
                array_filter($siswaIds)
            );


        if (!empty($siswaIdsUnik)) {

            $jumlahSiswaValid = Siswa::query()
                ->whereIn(
                    'id',
                    $siswaIdsUnik
                )
                ->whereHas(
                    'riwayatKelas.kelas',
                    function ($query) use (
                        $tahunAjaranId
                    ) {

                        $query->where(
                            'tahun_ajaran_id',
                            $tahunAjaranId
                        )
                        ->where(
                            'is_active',
                            1
                        );
                    }
                )
                ->count();


            if (
                $jumlahSiswaValid
                !== count($siswaIdsUnik)
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Terdapat siswa yang tidak terdaftar pada Tahun Ajaran ' .
                        $tahunAjaran->nama .
                        '.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $validated,
                $peminatanPerencanaan,
                $siswaIdsUnik
            ) {

                /*
                |--------------------------------------------------------------------------
                | Jangan izinkan Tahun Ajaran berubah
                |--------------------------------------------------------------------------
                */

                unset(
                    $validated['tahun_ajaran_id']
                );


                unset(
                    $validated['siswa_id']
                );


                /*
                |--------------------------------------------------------------------------
                | UPDATE DATA UTAMA
                |--------------------------------------------------------------------------
                */

                $peminatanPerencanaan->update(
                    $validated
                );


                /*
                |--------------------------------------------------------------------------
                | HAPUS PESERTA LAMA
                |--------------------------------------------------------------------------
                */

                $peminatanPerencanaan
                    ->peserta()
                    ->delete();


                /*
                |--------------------------------------------------------------------------
                | SIMPAN PESERTA BARU
                |--------------------------------------------------------------------------
                */

                foreach (
                    $siswaIdsUnik
                    as $siswaId
                ) {

                    PesertaPeminatan::create([
                        'peminatan_perencanaan_id' =>
                            $peminatanPerencanaan->id,

                        'siswa_id' =>
                            $siswaId,
                    ]);
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'peminatan-perencanaan.index'
            )
            ->with(
                'success',
                'Data peminatan dan perencanaan individu berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        PeminatanPerencanaan $peminatanPerencanaan
    ) {

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | HANYA DATA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (
            !$tahunAjaranId ||
            $peminatanPerencanaan->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $peminatanPerencanaan
            ) {

                $peminatanPerencanaan
                    ->peserta()
                    ->delete();

                $peminatanPerencanaan
                    ->delete();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'peminatan-perencanaan.index'
            )
            ->with(
                'success',
                'Data peminatan dan perencanaan individu berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */

    public function downloadPdf(
        PeminatanPerencanaan $peminatanPerencanaan
    ) {

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN DATA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (
            !$tahunAjaranId ||
            $peminatanPerencanaan->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD RELASI
        |--------------------------------------------------------------------------
        */

        $peminatanPerencanaan->load([
            'guruBK',
            'tahunAjaran',
            'kelas.jurusan',
            'bidangLayanan',
            'peserta.siswa',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'peminatan-perencanaan.pdf',
            [
                'peminatan' =>
                    $peminatanPerencanaan,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $namaFile =
            'Peminatan-Perencanaan';


        if (
            $peminatanPerencanaan
                ->peserta
                ->first()
                ?->siswa
                ?->nama_lengkap
        ) {

            $namaSiswa =
                $peminatanPerencanaan
                    ->peserta
                    ->first()
                    ->siswa
                    ->nama_lengkap;

            $namaFile .= '-' .
                preg_replace(
                    '/[^A-Za-z0-9\-]/',
                    '-',
                    $namaSiswa
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN PADA NAMA FILE
        |--------------------------------------------------------------------------
        */

        if (
            $peminatanPerencanaan
                ->tahunAjaran
                ?->nama
        ) {

            $namaFile .= '-' .
                preg_replace(
                    '/[^A-Za-z0-9\-]/',
                    '-',
                    $peminatanPerencanaan
                        ->tahunAjaran
                        ->nama
                );
        }


        $namaFile .= '.pdf';


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        return $pdf->download(
            $namaFile
        );
    }
}