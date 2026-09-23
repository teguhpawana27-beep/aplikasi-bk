<?php

namespace App\Http\Controllers;

use App\Models\LayananResponsif;
use App\Models\PesertaLayananResponsif;
use App\Models\GuruBK;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\BidangLayanan;
use App\Models\PendekatanBK;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LayananResponsifController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET TAHUN AJARAN AKTIF
    |--------------------------------------------------------------------------
    */

    private function getTahunAjaranAktif()
    {
        $tahunAjaranId = session('tahun_ajaran_id');

        if (!$tahunAjaranId) {

            $tahunAjaran = TahunAjaran::where('is_active', true)
                ->orderByDesc('tanggal_mulai')
                ->first();

            if (!$tahunAjaran) {
                $tahunAjaran = TahunAjaran::orderByDesc('tanggal_mulai')
                    ->first();
            }

            if ($tahunAjaran) {
                session([
                    'tahun_ajaran_id' => $tahunAjaran->id,
                ]);

                return $tahunAjaran;
            }

            return null;
        }

        $tahunAjaran = TahunAjaran::find($tahunAjaranId);

        if (!$tahunAjaran) {

            $tahunAjaran = TahunAjaran::where('is_active', true)
                ->orderByDesc('tanggal_mulai')
                ->first();

            if (!$tahunAjaran) {
                $tahunAjaran = TahunAjaran::orderByDesc('tanggal_mulai')
                    ->first();
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

    public function index()
    {
        $tahunAjaran = $this->getTahunAjaranAktif();

        $tahunAjaranId = $tahunAjaran?->id;

        $query = LayananResponsif::query()
            ->with([
                'guruBK',
                'tahunAjaran',
                'kelas.jurusan',
                'bidangLayanan',
                'pendekatan',
                'peserta.siswa',
            ])
            ->latest('tanggal');

        if ($tahunAjaranId) {
            $query->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            );
        }

        $layananResponsif = $query->get();

        return view(
            'layanan-responsif.index',
            compact(
                'layananResponsif',
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
        $tahunAjaranAktif =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {
            return redirect()
                ->route('layanan-responsif.index')
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
            ->orderBy('nama_lengkap')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        |
        | Tetap dikirim ke Blade agar kompatibel
        | dengan form yang sudah ada.
        |
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
        | HANYA KELAS PADA TAHUN AJARAN AKTIF
        |
        */

        $kelas = DB::table('kelas')
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
                'kelas.id',
                'kelas.tahun_ajaran_id',
                'kelas.jurusan_id',
                'kelas.tingkat',
                'kelas.nama_kelas',
                'jurusan.kode as jurusan_kode',
                'jurusan.nama as jurusan_nama',
                'tahun_ajaran.nama as tahun_ajaran_nama'
            )
            ->orderBy('kelas.tingkat')
            ->orderBy('jurusan.nama')
            ->orderBy('kelas.nama_kelas')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        |
        | HANYA SISWA YANG MEMILIKI RIWAYAT KELAS
        | PADA TAHUN AJARAN AKTIF.
        |
        */

        $siswa = DB::table(
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
            ->where(function ($query) {

                $query
                    ->whereNull(
                        'riwayat_kelas_siswa.status'
                    )
                    ->orWhereIn(
                        'riwayat_kelas_siswa.status',
                        [
                            'aktif',
                            'Aktif',
                            'active',
                            'ACTIVE',
                        ]
                    );
            })
            ->select(
                'siswa.id',
                'siswa.nis',
                'siswa.nisn',
                'siswa.nama_lengkap',
                'siswa.jenis_kelamin',
                'siswa.status',
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
        | PENDEKATAN BK
        |--------------------------------------------------------------------------
        */

        $pendekatan =
            PendekatanBK::where(
                'is_active',
                1
            )
                ->orderBy('nama')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | JENIS LAYANAN
        |--------------------------------------------------------------------------
        */

        $jenisLayanan = [
            'Konseling Individu',
            'Konseling Kelompok',
            'Konsultasi',
            'Mediasi',
            'Alih Tangan Kasus / Referal',
            'Konferensi Kasus',
            'Advokasi',
            'E-Konseling',
            'Bimbingan Teman Sebaya',
        ];


        return view(
            'layanan-responsif.create',
            compact(
                'guruBK',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'kelas',
                'jurusans',
                'siswa',
                'bidangLayanan',
                'pendekatan',
                'jenisLayanan'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ) {
        $tahunAjaran =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {
            return redirect()
                ->route('layanan-responsif.index')
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
        |
        | tahun_ajaran_id TIDAK DIAMBIL DARI INPUT USER.
        |
        */

        $validated =
            $request->validate(
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

                    'pendekatan_id' => [
                        'required',
                        'exists:pendekatan_bk,id',
                    ],

                    'jenis_layanan' => [
                        'required',
                        'string',
                        'max:255',
                    ],

                    'tingkat' => [
                        'nullable',
                        'string',
                        'max:50',
                    ],

                    'tanggal' => [
                        'required',
                        'date',
                    ],

                    'uraian_masalah' => [
                        'required',
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

                    'status_kasus' => [
                        'required',
                        'string',
                        'max:100',
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

                    'pendekatan_id.required' =>
                        'Pendekatan BK wajib dipilih.',

                    'jenis_layanan.required' =>
                        'Jenis layanan wajib dipilih.',

                    'tanggal.required' =>
                        'Tanggal wajib diisi.',

                    'uraian_masalah.required' =>
                        'Uraian masalah wajib diisi.',

                    'status_kasus.required' =>
                        'Status kasus wajib dipilih.',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI KELAS
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['kelas_id']
            )
        ) {

            $kelasValid =
                Kelas::query()
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
                array_filter(
                    $siswaIds
                )
            );

        if (
            !empty(
                $siswaIdsUnik
            )
        ) {

            $jumlahSiswaValid =
                Siswa::query()
                    ->whereIn(
                        'id',
                        $siswaIdsUnik
                    )
                    ->whereHas(
                        'riwayatKelas.kelas',
                        function ($query) use (
                            $tahunAjaranId
                        ) {

                            $query
                                ->where(
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
                $jumlahSiswaValid !==
                count($siswaIdsUnik)
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

        $validated[
            'tahun_ajaran_id'
        ] = $tahunAjaranId;


        /*
        |--------------------------------------------------------------------------
        | SIMPAN TRANSAKSI
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $validated,
                $siswaIdsUnik
            ) {

                unset(
                    $validated['siswa_id']
                );


                $layanan =
                    LayananResponsif::create(
                        $validated
                    );


                foreach (
                    $siswaIdsUnik
                    as $siswaId
                ) {

                    PesertaLayananResponsif::create(
                        [
                            'layanan_responsif_id' =>
                                $layanan->id,

                            'siswa_id' =>
                                $siswaId,

                            'peran' =>
                                'Konseli',
                        ]
                    );
                }
            }
        );


        return redirect()
            ->route(
                'layanan-responsif.index'
            )
            ->with(
                'success',
                'Data layanan responsif berhasil ditambahkan pada Tahun Ajaran ' .
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
        LayananResponsif $layananResponsif
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | CEGAH AKSES DATA TAHUN LAIN
        |--------------------------------------------------------------------------
        */

        if (
            !$tahunAjaranId ||
            $layananResponsif
                ->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        $layananResponsif->load(
            [
                'guruBK',
                'tahunAjaran',
                'kelas.jurusan',
                'bidangLayanan',
                'pendekatan',
                'peserta.siswa',
            ]
        );


        return view(
            'layanan-responsif.show',
            compact(
                'layananResponsif'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        LayananResponsif $layananResponsif
    ) {

        $tahunAjaranAktif =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {

            return redirect()
                ->route(
                    'layanan-responsif.index'
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
        | CEGAH EDIT DATA TAHUN LAIN
        |--------------------------------------------------------------------------
        */

        if (
            $layananResponsif
                ->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        $layananResponsif->load(
            [
                'peserta.siswa',
                'kelas.jurusan',
            ]
        );


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
        | KELAS TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $kelas = DB::table('kelas')
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
                'kelas.id',
                'kelas.tahun_ajaran_id',
                'kelas.jurusan_id',
                'kelas.tingkat',
                'kelas.nama_kelas',
                'jurusan.kode as jurusan_kode',
                'jurusan.nama as jurusan_nama',
                'tahun_ajaran.nama as tahun_ajaran_nama'
            )
            ->orderBy('kelas.tingkat')
            ->orderBy('jurusan.nama')
            ->orderBy('kelas.nama_kelas')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SISWA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $siswa = DB::table(
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
            ->where(function ($query) {

                $query
                    ->whereNull(
                        'riwayat_kelas_siswa.status'
                    )
                    ->orWhereIn(
                        'riwayat_kelas_siswa.status',
                        [
                            'aktif',
                            'Aktif',
                            'active',
                            'ACTIVE',
                        ]
                    );
            })
            ->select(
                'siswa.id',
                'siswa.nis',
                'siswa.nisn',
                'siswa.nama_lengkap',
                'siswa.jenis_kelamin',
                'siswa.status',
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
        | PENDEKATAN
        |--------------------------------------------------------------------------
        */

        $pendekatan =
            PendekatanBK::where(
                'is_active',
                1
            )
                ->orderBy('nama')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | JENIS LAYANAN
        |--------------------------------------------------------------------------
        */

        $jenisLayanan = [
            'Konseling Individu',
            'Konseling Kelompok',
            'Konsultasi',
            'Mediasi',
            'Alih Tangan Kasus / Referal',
            'Konferensi Kasus',
            'Advokasi',
            'E-Konseling',
            'Bimbingan Teman Sebaya',
        ];


        /*
        |--------------------------------------------------------------------------
        | PESERTA TERPILIH
        |--------------------------------------------------------------------------
        */

        $pesertaTerpilih =
            $layananResponsif
                ->peserta
                ->pluck('siswa_id')
                ->map(
                    fn ($id) =>
                        (string) $id
                )
                ->values()
                ->toArray();


        return view(
            'layanan-responsif.edit',
            compact(
                'layananResponsif',
                'guruBK',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'kelas',
                'jurusans',
                'siswa',
                'bidangLayanan',
                'pendekatan',
                'jenisLayanan',
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
        LayananResponsif $layananResponsif
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {

            return redirect()
                ->route(
                    'layanan-responsif.index'
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
        | CEGAH UPDATE DATA TAHUN LAIN
        |--------------------------------------------------------------------------
        */

        if (
            $layananResponsif
                ->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate(
                [
                    'guru_bk_id' =>
                        'required|exists:guru_bk,id',

                    'kelas_id' =>
                        'nullable|exists:kelas,id',

                    'bidang_layanan_id' =>
                        'required|exists:bidang_layanan,id',

                    'pendekatan_id' =>
                        'required|exists:pendekatan_bk,id',

                    'jenis_layanan' =>
                        'required|string|max:255',

                    'tingkat' =>
                        'nullable|string|max:50',

                    'tanggal' =>
                        'required|date',

                    'uraian_masalah' =>
                        'required|string',

                    'tindak_lanjut' =>
                        'nullable|string',

                    'keterangan' =>
                        'nullable|string',

                    'status_kasus' =>
                        'required|string|max:100',

                    'siswa_id' =>
                        'nullable|array',

                    'siswa_id.*' =>
                        'exists:siswa,id',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI KELAS
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['kelas_id']
            )
        ) {

            $kelasValid =
                Kelas::query()
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
                array_filter(
                    $siswaIds
                )
            );


        if (
            !empty(
                $siswaIdsUnik
            )
        ) {

            $jumlahSiswaValid =
                Siswa::query()
                    ->whereIn(
                        'id',
                        $siswaIdsUnik
                    )
                    ->whereHas(
                        'riwayatKelas.kelas',
                        function ($query) use (
                            $tahunAjaranId
                        ) {

                            $query
                                ->where(
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
                $jumlahSiswaValid !==
                count($siswaIdsUnik)
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
        | UPDATE TRANSAKSI
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $validated,
                $layananResponsif,
                $siswaIdsUnik
            ) {

                unset(
                    $validated['siswa_id']
                );


                /*
                | Jangan izinkan perubahan tahun ajaran.
                */

                unset(
                    $validated['tahun_ajaran_id']
                );


                $layananResponsif->update(
                    $validated
                );


                /*
                | Hapus peserta lama
                */

                $layananResponsif
                    ->peserta()
                    ->delete();


                /*
                | Simpan peserta baru
                */

                foreach (
                    $siswaIdsUnik
                    as $siswaId
                ) {

                    PesertaLayananResponsif::create(
                        [
                            'layanan_responsif_id' =>
                                $layananResponsif->id,

                            'siswa_id' =>
                                $siswaId,

                            'peran' =>
                                'Konseli',
                        ]
                    );
                }
            }
        );


        return redirect()
            ->route(
                'layanan-responsif.index'
            )
            ->with(
                'success',
                'Data layanan responsif berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        LayananResponsif $layananResponsif
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | CEGAH DELETE DATA TAHUN LAIN
        |--------------------------------------------------------------------------
        */

        if (
            !$tahunAjaranId ||
            $layananResponsif
                ->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        DB::transaction(
            function () use (
                $layananResponsif
            ) {

                $layananResponsif
                    ->peserta()
                    ->delete();


                $layananResponsif
                    ->delete();
            }
        );


        return redirect()
            ->route(
                'layanan-responsif.index'
            )
            ->with(
                'success',
                'Data layanan responsif berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */

    public function downloadPdf(
        LayananResponsif $layananResponsif
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | CEGAH PDF DATA TAHUN LAIN
        |--------------------------------------------------------------------------
        */

        if (
            !$tahunAjaranId ||
            $layananResponsif
                ->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        $layananResponsif->load(
            [
                'guruBK',
                'tahunAjaran',
                'kelas.jurusan',
                'bidangLayanan',
                'pendekatan',
                'peserta.siswa',
            ]
        );


        $pdf =
            Pdf::loadView(
                'layanan-responsif.pdf',
                compact(
                    'layananResponsif'
                )
            );


        $pdf->setPaper(
            'A4',
            'portrait'
        );


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $namaJenis =
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $layananResponsif
                    ->jenis_layanan
                    ?? 'Data'
            );


        $namaTahun =
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $layananResponsif
                    ->tahunAjaran
                    ?->nama
                    ?? 'Tahun-Ajaran'
            );


        $namaFile =
            'Layanan-Responsif-' .
            $namaJenis .
            '-' .
            $namaTahun .
            '-' .
            $layananResponsif->id .
            '.pdf';


        return $pdf->download(
            $namaFile
        );
    }
}