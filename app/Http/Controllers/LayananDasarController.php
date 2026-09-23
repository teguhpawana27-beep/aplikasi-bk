<?php

namespace App\Http\Controllers;

use App\Models\GuruBK;
use App\Models\Kelas;
use App\Models\LayananDasar;
use App\Models\MetodeBK;
use App\Models\PesertaLayananDasar;
use App\Models\Siswa;
use App\Models\Skkpd;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LayananDasarController extends Controller
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
        | Jika session belum tersedia
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
        | Ambil Tahun Ajaran berdasarkan session
        |--------------------------------------------------------------------------
        */

        $tahunAjaran = TahunAjaran::find(
            $tahunAjaranId
        );


        /*
        |--------------------------------------------------------------------------
        | Jika session tidak valid
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
        | QUERY
        |--------------------------------------------------------------------------
        */

        $query = LayananDasar::query()
            ->with([
                'guruBK',
                'tahunAjaran',
                'kelas.jurusan',
                'skkpd',
                'metode',
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
            );


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
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'topik',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'sasaran',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'jenis_layanan',
                    'like',
                    '%' . $search . '%'
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $layananDasar = $query
            ->latest('tanggal')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'layanan-dasar.index',
            compact(
                'layananDasar',
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
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaranAktif =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {

            return redirect()
                ->route('layanan-dasar.index')
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
        | DAFTAR TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaran = TahunAjaran::orderByDesc(
            'tanggal_mulai'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        |
        | Hanya kelas Tahun Ajaran yang sedang dipilih.
        |
        */

        $kelasList = DB::table('kelas')
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
            ->orderBy(
                'kelas.tingkat'
            )
            ->orderBy(
                'jurusan.kode'
            )
            ->orderBy(
                'kelas.nama_kelas'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SISWA BERDASARKAN RIWAYAT KELAS
        |--------------------------------------------------------------------------
        |
        | Hanya siswa yang memiliki riwayat kelas pada Tahun Ajaran aktif.
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
            ->where(
                'kelas.tahun_ajaran_id',
                $tahunAjaranId
            )
            ->where(
                'kelas.is_active',
                1
            )
            ->select(
                'riwayat_kelas_siswa.id as riwayat_id',
                'riwayat_kelas_siswa.siswa_id',
                'riwayat_kelas_siswa.kelas_id',
                'kelas.tahun_ajaran_id',
                'kelas.tingkat',
                'kelas.jurusan_id',
                'siswa.nis',
                'siswa.nisn',
                'siswa.nama_lengkap',
                'siswa.jenis_kelamin',
                'siswa.status'
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
            ->orderBy(
                'siswa.nama_lengkap'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SEMUA SISWA
        |--------------------------------------------------------------------------
        |
        | Tetap menggunakan nama variabel lama agar Blade tidak error.
        |
        */

        $semuaSiswa = Siswa::query()
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
            ->get([
                'id',
                'nis',
                'nisn',
                'nama_lengkap',
                'jenis_kelamin',
                'status',
            ]);




        /*
        |--------------------------------------------------------------------------
        | SKKPD
        |--------------------------------------------------------------------------
        |
        | Ambil data SKKPD untuk pilihan pada form.
        |
        */

        $skkpd = Skkpd::query()
            ->orderBy(
                'kode'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | METODE BK
        |--------------------------------------------------------------------------
        |
        | Ambil data metode BK untuk pilihan pada form.
        |
        */

        $metode = MetodeBK::query()
            ->orderBy(
                'nama'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'layanan-dasar.create',
            compact(
                'guruBK',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'kelasList',
                'siswaKelas',
                'semuaSiswa',
                'skkpd',
                'metode'
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
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {

            return redirect()
                ->route('layanan-dasar.index')
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

                'skkpd_id' => [
                    'required',
                    'exists:skkpd,id',
                ],

                'metode_id' => [
                    'required',
                    'exists:metode_bk,id',
                ],

                'jenis_layanan' => [
                    'required',
                    'in:Bimbingan Klasikal,Bimbingan Kelompok,Bimbingan Kelas Besar / Lintas Kelas,Pengembangan Media BK',
                ],

                'tanggal' => [
                    'required',
                    'date',
                ],

                'topik' => [
                    'required',
                    'string',
                    'max:255',
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

                'hasil' => [
                    'nullable',
                    'string',
                ],

                'evaluasi' => [
                    'nullable',
                    'string',
                ],

                'keterangan' => [
                    'nullable',
                    'string',
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

                'guru_bk_id.exists' =>
                    'Guru BK tidak ditemukan.',

                'kelas_id.exists' =>
                    'Kelas tidak ditemukan.',

                'skkpd_id.required' =>
                    'SKKPD wajib dipilih.',

                'skkpd_id.exists' =>
                    'SKKPD tidak ditemukan.',

                'metode_id.required' =>
                    'Metode BK wajib dipilih.',

                'metode_id.exists' =>
                    'Metode BK tidak ditemukan.',

                'jenis_layanan.required' =>
                    'Jenis layanan wajib dipilih.',

                'tanggal.required' =>
                    'Tanggal layanan wajib diisi.',

                'topik.required' =>
                    'Topik layanan wajib diisi.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN KELAS BERASAL DARI TAHUN AKTIF
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

            $jumlahSiswaValid = Siswa::query()
                ->whereIn(
                    'id',
                    $siswaIds
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
                !== count(array_unique($siswaIds))
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
        | TAHUN AJARAN OTOMATIS
        |--------------------------------------------------------------------------
        */

        $validated['tahun_ajaran_id'] =
            $tahunAjaranId;


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $siswaIds
        ) {

            unset(
                $validated['siswa_id']
            );


            /*
            |--------------------------------------------------------------------------
            | SIMPAN LAYANAN
            |--------------------------------------------------------------------------
            */

            $layanan =
                LayananDasar::create(
                    $validated
                );


            /*
            |--------------------------------------------------------------------------
            | SIMPAN PESERTA
            |--------------------------------------------------------------------------
            */

            foreach (
                array_unique($siswaIds)
                as $siswaId
            ) {

                PesertaLayananDasar::create([
                    'layanan_dasar_id' =>
                        $layanan->id,

                    'siswa_id' =>
                        $siswaId,
                ]);
            }
        });


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('layanan-dasar.index')
            ->with(
                'success',
                'Layanan dasar berhasil ditambahkan pada Tahun Ajaran ' .
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
        LayananDasar $layananDasar
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
        | BATASI DATA
        |--------------------------------------------------------------------------
        */

        if (
            !$tahunAjaranId ||
            $layananDasar->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | RELASI
        |--------------------------------------------------------------------------
        */

        $layananDasar->load([
            'guruBK',
            'tahunAjaran',
            'kelas.jurusan',
            'skkpd',
            'metode',
            'peserta.siswa',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'layanan-dasar.show',
            compact(
                'layananDasar'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        LayananDasar $layananDasar
    ) {

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaranAktif =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {

            return redirect()
                ->route('layanan-dasar.index')
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaranAktif->id;


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN DATA DARI TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (
            $layananDasar->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | PESERTA
        |--------------------------------------------------------------------------
        */

        $layananDasar->load(
            'peserta'
        );


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

        $tahunAjaran = TahunAjaran::orderByDesc(
            'tanggal_mulai'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        */

        $kelasList = DB::table('kelas')
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
            ->orderBy(
                'kelas.tingkat'
            )
            ->orderBy(
                'jurusan.kode'
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
            ->where(
                'kelas.tahun_ajaran_id',
                $tahunAjaranId
            )
            ->where(
                'kelas.is_active',
                1
            )
            ->select(
                'riwayat_kelas_siswa.id as riwayat_id',
                'riwayat_kelas_siswa.siswa_id',
                'riwayat_kelas_siswa.kelas_id',
                'kelas.tahun_ajaran_id',
                'kelas.tingkat',
                'kelas.jurusan_id',
                'siswa.nis',
                'siswa.nisn',
                'siswa.nama_lengkap',
                'siswa.jenis_kelamin',
                'siswa.status'
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
            ->orderBy(
                'siswa.nama_lengkap'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SEMUA SISWA
        |--------------------------------------------------------------------------
        */

        $semuaSiswa = Siswa::query()
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
            ->get([
                'id',
                'nis',
                'nisn',
                'nama_lengkap',
                'jenis_kelamin',
                'status',
            ]);


        /*
        |--------------------------------------------------------------------------
        | PESERTA TERPILIH
        |--------------------------------------------------------------------------
        */

        $pesertaTerpilih =
            $layananDasar
                ->peserta
                ->pluck('siswa_id')
                ->toArray();




        /*
        |--------------------------------------------------------------------------
        | SKKPD
        |--------------------------------------------------------------------------
        |
        | Ambil data SKKPD untuk pilihan pada form.
        |
        */

        $skkpd = Skkpd::query()
            ->orderBy(
                'kode'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | METODE BK
        |--------------------------------------------------------------------------
        |
        | Ambil data metode BK untuk pilihan pada form.
        |
        */

        $metode = MetodeBK::query()
            ->orderBy(
                'nama'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'layanan-dasar.edit',
            compact(
                'layananDasar',
                'guruBK',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'kelasList',
                'siswaKelas',
                'semuaSiswa',
                'pesertaTerpilih',
                'skkpd',
                'metode'
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
        LayananDasar $layananDasar
    ) {

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {

            return redirect()
                ->route('layanan-dasar.index')
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaran->id;


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN DATA DARI TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (
            $layananDasar->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


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

                'skkpd_id' => [
                    'required',
                    'exists:skkpd,id',
                ],

                'metode_id' => [
                    'required',
                    'exists:metode_bk,id',
                ],

                'jenis_layanan' => [
                    'required',
                    'in:Bimbingan Klasikal,Bimbingan Kelompok,Bimbingan Kelas Besar / Lintas Kelas,Pengembangan Media BK',
                ],

                'tanggal' => [
                    'required',
                    'date',
                ],

                'topik' => [
                    'required',
                    'string',
                    'max:255',
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

                'hasil' => [
                    'nullable',
                    'string',
                ],

                'evaluasi' => [
                    'nullable',
                    'string',
                ],

                'keterangan' => [
                    'nullable',
                    'string',
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

                'guru_bk_id.exists' =>
                    'Guru BK tidak ditemukan.',

                'kelas_id.exists' =>
                    'Kelas tidak ditemukan.',

                'skkpd_id.required' =>
                    'SKKPD wajib dipilih.',

                'skkpd_id.exists' =>
                    'SKKPD tidak ditemukan.',

                'metode_id.required' =>
                    'Metode BK wajib dipilih.',

                'metode_id.exists' =>
                    'Metode BK tidak ditemukan.',

                'jenis_layanan.required' =>
                    'Jenis layanan wajib dipilih.',

                'tanggal.required' =>
                    'Tanggal layanan wajib diisi.',

                'topik.required' =>
                    'Topik layanan wajib diisi.',
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
        | SISWA
        |--------------------------------------------------------------------------
        */

        $siswaIds =
            $validated['siswa_id'] ?? [];


        if (!empty($siswaIds)) {

            $jumlahSiswaValid = Siswa::query()
                ->whereIn(
                    'id',
                    $siswaIds
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
                !== count(array_unique($siswaIds))
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

        DB::transaction(function () use (
            $validated,
            $layananDasar,
            $siswaIds
        ) {

            unset(
                $validated['siswa_id']
            );

            /*
            |--------------------------------------------------------------------------
            | Jangan izinkan Tahun Ajaran berubah
            |--------------------------------------------------------------------------
            */

            unset(
                $validated['tahun_ajaran_id']
            );


            /*
            |--------------------------------------------------------------------------
            | UPDATE LAYANAN
            |--------------------------------------------------------------------------
            */

            $layananDasar->update(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | HAPUS PESERTA LAMA
            |--------------------------------------------------------------------------
            */

            $layananDasar
                ->peserta()
                ->delete();


            /*
            |--------------------------------------------------------------------------
            | SIMPAN PESERTA BARU
            |--------------------------------------------------------------------------
            */

            foreach (
                array_unique($siswaIds)
                as $siswaId
            ) {

                PesertaLayananDasar::create([
                    'layanan_dasar_id' =>
                        $layananDasar->id,

                    'siswa_id' =>
                        $siswaId,
                ]);
            }
        });


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('layanan-dasar.index')
            ->with(
                'success',
                'Layanan dasar berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        LayananDasar $layananDasar
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
            $layananDasar->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $layananDasar
        ) {

            $layananDasar
                ->peserta()
                ->delete();

            $layananDasar
                ->delete();
        });


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('layanan-dasar.index')
            ->with(
                'success',
                'Layanan dasar berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */

    public function downloadPdf(
        LayananDasar $layananDasar
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
        | PASTIKAN DATA DARI TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (
            !$tahunAjaranId ||
            $layananDasar->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD RELASI
        |--------------------------------------------------------------------------
        */

        $layananDasar->load([
            'guruBK',
            'tahunAjaran',
            'kelas.jurusan',
            'skkpd',
            'metode',
            'peserta.siswa',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'layanan-dasar.pdf',
            compact(
                'layananDasar'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | PAPER
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper(
            'A4',
            'portrait'
        );


        /*
        |--------------------------------------------------------------------------
        | NAMA JENIS
        |--------------------------------------------------------------------------
        */

        $namaJenis =
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $layananDasar->jenis_layanan
                ?? 'Data'
            );


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $namaFile =
            'Layanan-Dasar-' .
            $namaJenis .
            '-' .
            $layananDasar->id .
            '-' .
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $layananDasar->tahunAjaran->nama
                ?? 'Tahun-Ajaran'
            ) .
            '.pdf';


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