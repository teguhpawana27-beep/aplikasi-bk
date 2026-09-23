<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\LayananDasar;
use App\Models\PeminatanPerencanaan;
use App\Models\LayananResponsif;
use App\Models\DukunganSistem;
use App\Models\TindakLanjut;
use App\Models\AsesmenAwal;
use App\Models\RiwayatKelasSiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HELPER TAHUN AJARAN AKTIF
    |--------------------------------------------------------------------------
    */

    private function getTahunAjaranAktif()
    {
        $tahunAjaranId = session('tahun_ajaran_id');

        if ($tahunAjaranId) {

            $tahunAjaran = TahunAjaran::find(
                $tahunAjaranId
            );

            if ($tahunAjaran) {
                return $tahunAjaran;
            }
        }


        $tahunAjaran =
            TahunAjaran::where(
                'is_active',
                true
            )
            ->orderByDesc('tanggal_mulai')
            ->first();


        if (!$tahunAjaran) {

            $tahunAjaran =
                TahunAjaran::orderByDesc(
                    'tanggal_mulai'
                )->first();
        }


        if ($tahunAjaran) {

            session([
                'tahun_ajaran_id' =>
                    $tahunAjaran->id,
            ]);
        }


        return $tahunAjaran;
    }


    /*
    |--------------------------------------------------------------------------
    | HALAMAN UTAMA LAPORAN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $tahunAjaran =
            $this->getTahunAjaranAktif();


        return view(
            'laporan.index',
            compact('tahunAjaran')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REKAPAN PER KOMPONEN
    |--------------------------------------------------------------------------
    */

    public function komponen(Request $request)
    {
        $tahunAjaran =
            $this->getTahunAjaranAktif();


        $tahunAjaranList =
            TahunAjaran::orderByDesc(
                'tanggal_mulai'
            )->get();


        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | QUERY KOMPONEN
        |--------------------------------------------------------------------------
        */

        $layananDasar =
            LayananDasar::query();

        $peminatan =
            PeminatanPerencanaan::query();

        $responsif =
            LayananResponsif::query();

        $dukungan =
            DukunganSistem::query();


        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if ($tahunAjaranId) {

            $layananDasar
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );

            $peminatan
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );

            $responsif
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );

            $dukungan
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );
        }


        $data = [

            'layananDasar' =>
                $layananDasar->count(),

            'peminatan' =>
                $peminatan->count(),

            'responsif' =>
                $responsif->count(),

            'dukungan' =>
                $dukungan->count(),

        ];


        $total =
            array_sum($data);


        return view(
            'laporan.komponen',
            compact(
                'tahunAjaranList',
                'tahunAjaran',
                'tahunAjaranId',
                'data',
                'total'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN KEGIATAN BK
    |--------------------------------------------------------------------------
    */

    public function kegiatan(Request $request)
    {
        $tahunAjaran =
            $this->getTahunAjaranAktif();


        $tahunAjaranList =
            TahunAjaran::orderByDesc(
                'tanggal_mulai'
            )->get();


        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | LAYANAN DASAR
        |--------------------------------------------------------------------------
        */

        $layananDasarQuery =
            LayananDasar::with([
                'guruBK',
                'tahunAjaran',
                'kelas',
            ]);


        /*
        |--------------------------------------------------------------------------
        | PEMINATAN & PERENCANAAN
        |--------------------------------------------------------------------------
        */

        $peminatanQuery =
            PeminatanPerencanaan::with([
                'guruBK',
                'tahunAjaran',
                'kelas',
            ]);


        /*
        |--------------------------------------------------------------------------
        | LAYANAN RESPONSIF
        |--------------------------------------------------------------------------
        */

        $responsifQuery =
            LayananResponsif::with([
                'guruBK',
                'tahunAjaran',
                'kelas',
            ]);


        /*
        |--------------------------------------------------------------------------
        | DUKUNGAN SISTEM
        |--------------------------------------------------------------------------
        */

        $dukunganQuery =
            DukunganSistem::with([
                'guruBK',
                'tahunAjaran',
            ]);


        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if ($tahunAjaranId) {

            $layananDasarQuery
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );

            $peminatanQuery
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );

            $responsifQuery
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );

            $dukunganQuery
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                );
        }


        $layananDasar =
            $layananDasarQuery
                ->latest('tanggal')
                ->get();


        $peminatan =
            $peminatanQuery
                ->latest('tanggal')
                ->get();


        $responsif =
            $responsifQuery
                ->latest('tanggal')
                ->get();


        $dukungan =
            $dukunganQuery
                ->latest('tanggal')
                ->get();


        return view(
            'laporan.kegiatan',
            compact(
                'tahunAjaranList',
                'tahunAjaran',
                'tahunAjaranId',
                'layananDasar',
                'peminatan',
                'responsif',
                'dukungan'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN PERKEMBANGAN SISWA
    |--------------------------------------------------------------------------
    */

    public function perkembangan(
        Request $request
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();


        $tahunAjaranList =
            TahunAjaran::orderByDesc(
                'tanggal_mulai'
            )->get();


        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $tingkat =
            $request->tingkat;

        $jurusanId =
            $request->jurusan_id;

        $kelasId =
            $request->kelas_id;

        $siswaId =
            $request->siswa_id;


        /*
        |--------------------------------------------------------------------------
        | DATA DEFAULT
        |--------------------------------------------------------------------------
        */

        $siswa =
            collect();

        $selectedSiswa =
            null;

        $layananDasar =
            collect();

        $peminatan =
            collect();

        $responsif =
            collect();

        $tindakLanjut =
            collect();

        $asesmenAwal =
            collect();


        /*
        |--------------------------------------------------------------------------
        | LOAD SISWA
        |--------------------------------------------------------------------------
        |
        | Kelas wajib berasal dari tahun aktif.
        |
        */

        if (
            $kelasId &&
            $tahunAjaranId
        ) {

            $kelasValid =
                Kelas::query()
                    ->where(
                        'id',
                        $kelasId
                    )
                    ->where(
                        'tahun_ajaran_id',
                        $tahunAjaranId
                    )
                    ->where(
                        'is_active',
                        true
                    )
                    ->exists();


            if ($kelasValid) {

                $riwayatSiswa =
                    RiwayatKelasSiswa::with(
                        'siswa'
                    )
                    ->where(
                        'kelas_id',
                        $kelasId
                    )
                    ->get();


                $siswa =
                    $riwayatSiswa
                        ->pluck('siswa')
                        ->filter()
                        ->unique('id')
                        ->sortBy(
                            'nama_lengkap'
                        )
                        ->values();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | JIKA SISWA DIPILIH
        |--------------------------------------------------------------------------
        */

        if (
            $siswaId &&
            $tahunAjaranId
        ) {

            /*
            |--------------------------------------------------------------------------
            | PASTIKAN SISWA MEMANG TERDAFTAR
            | PADA TAHUN AKTIF
            |--------------------------------------------------------------------------
            */

            $siswaValid =
                RiwayatKelasSiswa::query()
                    ->where(
                        'siswa_id',
                        $siswaId
                    )
                    ->whereHas(
                        'kelas',
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
                                    true
                                );
                        }
                    )
                    ->exists();


            if (!$siswaValid) {

                return redirect()
                    ->route(
                        'laporan.perkembangan'
                    )
                    ->with(
                        'error',
                        'Siswa tidak terdaftar pada tahun ajaran yang sedang aktif.'
                    );
            }


            $selectedSiswa =
                Siswa::findOrFail(
                    $siswaId
                );


            /*
            |--------------------------------------------------------------------------
            | LAYANAN DASAR
            |--------------------------------------------------------------------------
            */

            $layananDasar =
                LayananDasar::with([
                    'guruBK',
                    'tahunAjaran',
                    'kelas',
                ])
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->whereHas(
                    'peserta',
                    function ($query) use (
                        $siswaId
                    ) {

                        $query->where(
                            'siswa_id',
                            $siswaId
                        );
                    }
                )
                ->latest('tanggal')
                ->get();


            /*
            |--------------------------------------------------------------------------
            | PEMINATAN & PERENCANAAN
            |--------------------------------------------------------------------------
            */

            $peminatan =
                PeminatanPerencanaan::with([
                    'guruBK',
                    'tahunAjaran',
                    'kelas',
                ])
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->whereHas(
                    'peserta',
                    function ($query) use (
                        $siswaId
                    ) {

                        $query->where(
                            'siswa_id',
                            $siswaId
                        );
                    }
                )
                ->latest('tanggal')
                ->get();


            /*
            |--------------------------------------------------------------------------
            | LAYANAN RESPONSIF
            |--------------------------------------------------------------------------
            */

            $responsif =
                LayananResponsif::with([
                    'guruBK',
                    'tahunAjaran',
                    'kelas',
                ])
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->whereHas(
                    'peserta',
                    function ($query) use (
                        $siswaId
                    ) {

                        $query->where(
                            'siswa_id',
                            $siswaId
                        );
                    }
                )
                ->latest('tanggal')
                ->get();


            /*
            |--------------------------------------------------------------------------
            | TINDAK LANJUT
            |--------------------------------------------------------------------------
            */

            $tindakLanjut =
                TindakLanjut::with([
                    'guruBK',
                    'asesmenAwal',
                ])
                ->where(
                    'siswa_id',
                    $siswaId
                )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->latest(
                    'tanggal_rencana'
                )
                ->get();


            /*
            |--------------------------------------------------------------------------
            | ASESMEN AWAL
            |--------------------------------------------------------------------------
            */

            $asesmenAwal =
                AsesmenAwal::with([
                    'guruBK',
                    'tahunAjaran',
                ])
                ->where(
                    'siswa_id',
                    $siswaId
                )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->latest(
                    'tanggal_asesmen'
                )
                ->get();
        }


        return view(
            'laporan.perkembangan',
            compact(
                'tahunAjaranList',
                'tahunAjaran',
                'tahunAjaranId',
                'tingkat',
                'jurusanId',
                'kelasId',
                'siswaId',
                'siswa',
                'selectedSiswa',
                'layananDasar',
                'peminatan',
                'responsif',
                'tindakLanjut',
                'asesmenAwal'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX - TINGKAT
    |--------------------------------------------------------------------------
    */

    public function tingkat(
        Request $request
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();


        if (!$tahunAjaran) {

            return response()->json([]);
        }


        $data =
            Kelas::where(
                'tahun_ajaran_id',
                $tahunAjaran->id
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
            );


        return response()->json(
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX - JURUSAN
    |--------------------------------------------------------------------------
    */

    public function jurusan(
        Request $request
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();


        if (!$tahunAjaran) {

            return response()->json([]);
        }


        $request->validate([
            'tingkat' =>
                'required',
        ]);


        $data =
            Jurusan::where(
                'is_active',
                true
            )
            ->whereHas(
                'kelas',
                function ($query) use (
                    $request,
                    $tahunAjaran
                ) {

                    $query
                        ->where(
                            'tahun_ajaran_id',
                            $tahunAjaran->id
                        )
                        ->where(
                            'tingkat',
                            $request->tingkat
                        )
                        ->where(
                            'is_active',
                            true
                        );
                }
            )
            ->orderBy(
                'nama'
            )
            ->get([
                'id',
                'kode',
                'nama',
            ]);


        return response()->json(
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX - KELAS
    |--------------------------------------------------------------------------
    */

    public function kelas(
        Request $request
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();


        if (!$tahunAjaran) {

            return response()->json([]);
        }


        $request->validate([

            'tingkat' =>
                'required',

            'jurusan_id' => [
                'required',
                'exists:jurusan,id',
            ],

        ]);


        $data =
            Kelas::where(
                'tahun_ajaran_id',
                $tahunAjaran->id
            )
            ->where(
                'tingkat',
                $request->tingkat
            )
            ->where(
                'jurusan_id',
                $request->jurusan_id
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
                'nama_kelas',
            ]);


        return response()->json(
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX - SISWA
    |--------------------------------------------------------------------------
    */

    public function siswa(
        Request $request
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();


        if (!$tahunAjaran) {

            return response()->json([]);
        }


        $request->validate([
            'kelas_id' => [
                'required',
                'exists:kelas,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN KELAS TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $kelas =
            Kelas::query()
                ->where(
                    'id',
                    $request->kelas_id
                )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaran->id
                )
                ->where(
                    'is_active',
                    true
                )
                ->first();


        if (!$kelas) {

            return response()->json([]);
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL SISWA DARI RIWAYAT KELAS
        |--------------------------------------------------------------------------
        */

        $data =
            RiwayatKelasSiswa::with(
                'siswa'
            )
            ->where(
                'kelas_id',
                $kelas->id
            )
            ->get()
            ->pluck('siswa')
            ->filter()
            ->unique('id')
            ->sortBy(
                'nama_lengkap'
            )
            ->values()
            ->map(
                function ($item) {

                    return [
                        'id' =>
                            $item->id,

                        'nis' =>
                            $item->nis,

                        'nama_lengkap' =>
                            $item->nama_lengkap,
                    ];
                }
            );


        return response()->json(
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF LAPORAN KEGIATAN
    |--------------------------------------------------------------------------
    */

    public function downloadKegiatan(
        Request $request
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();


        if (!$tahunAjaran) {

            return back()->with(
                'error',
                'Tahun ajaran aktif belum tersedia.'
            );
        }


        $tahunAjaranId =
            $tahunAjaran->id;


        /*
        |--------------------------------------------------------------------------
        | QUERY
        |--------------------------------------------------------------------------
        */

        $layananDasar =
            LayananDasar::with([
                'guruBK',
                'tahunAjaran',
                'kelas',
            ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->latest('tanggal')
            ->get();


        $peminatan =
            PeminatanPerencanaan::with([
                'guruBK',
                'tahunAjaran',
                'kelas',
            ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->latest('tanggal')
            ->get();


        $responsif =
            LayananResponsif::with([
                'guruBK',
                'tahunAjaran',
                'kelas',
            ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->latest('tanggal')
            ->get();


        $dukungan =
            DukunganSistem::with([
                'guruBK',
                'tahunAjaran',
            ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->latest('tanggal')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | GENERATE PDF
        |--------------------------------------------------------------------------
        */

        $pdf =
            Pdf::loadView(
                'laporan.pdf.kegiatan',
                [
                    'tahunTerpilih' =>
                        $tahunAjaran,

                    'layananDasar' =>
                        $layananDasar,

                    'peminatan' =>
                        $peminatan,

                    'responsif' =>
                        $responsif,

                    'dukungan' =>
                        $dukungan,
                ]
            );


        $pdf->setPaper(
            'a4',
            'landscape'
        );


        $namaTahun =
            str_replace(
                ['/', '\\', ' '],
                ['-', '-', '_'],
                $tahunAjaran->nama
            );


        return $pdf->download(
            'laporan-kegiatan-bk-' .
            $namaTahun .
            '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF LAPORAN PERKEMBANGAN SISWA
    |--------------------------------------------------------------------------
    */

    public function downloadPerkembangan(
        Request $request
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();


        if (!$tahunAjaran) {

            return redirect()
                ->route(
                    'laporan.perkembangan'
                )
                ->with(
                    'error',
                    'Tahun ajaran aktif belum tersedia.'
                );
        }


        $tahunAjaranId =
            $tahunAjaran->id;


        $siswaId =
            $request->siswa_id;


        if (!$siswaId) {

            return redirect()
                ->route(
                    'laporan.perkembangan'
                )
                ->with(
                    'error',
                    'Silakan pilih siswa terlebih dahulu.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SISWA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $siswaValid =
            RiwayatKelasSiswa::query()
                ->where(
                    'siswa_id',
                    $siswaId
                )
                ->whereHas(
                    'kelas',
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
                                true
                            );
                    }
                )
                ->exists();


        if (!$siswaValid) {

            return redirect()
                ->route(
                    'laporan.perkembangan'
                )
                ->with(
                    'error',
                    'Siswa tidak terdaftar pada tahun ajaran yang sedang aktif.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

        $siswa =
            Siswa::findOrFail(
                $siswaId
            );


        /*
        |--------------------------------------------------------------------------
        | LAYANAN DASAR
        |--------------------------------------------------------------------------
        */

        $layananDasar =
            LayananDasar::with([
                'guruBK',
                'tahunAjaran',
                'kelas',
            ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->whereHas(
                'peserta',
                function ($query) use (
                    $siswaId
                ) {

                    $query->where(
                        'siswa_id',
                        $siswaId
                    );
                }
            )
            ->latest('tanggal')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PEMINATAN
        |--------------------------------------------------------------------------
        */

        $peminatan =
            PeminatanPerencanaan::with([
                'guruBK',
                'tahunAjaran',
                'kelas',
            ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->whereHas(
                'peserta',
                function ($query) use (
                    $siswaId
                ) {

                    $query->where(
                        'siswa_id',
                        $siswaId
                    );
                }
            )
            ->latest('tanggal')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RESPONSIF
        |--------------------------------------------------------------------------
        */

        $responsif =
            LayananResponsif::with([
                'guruBK',
                'tahunAjaran',
                'kelas',
            ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->whereHas(
                'peserta',
                function ($query) use (
                    $siswaId
                ) {

                    $query->where(
                        'siswa_id',
                        $siswaId
                    );
                }
            )
            ->latest('tanggal')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TINDAK LANJUT
        |--------------------------------------------------------------------------
        */

        $tindakLanjut =
            TindakLanjut::with([
                'guruBK',
                'asesmenAwal',
            ])
            ->where(
                'siswa_id',
                $siswaId
            )
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->latest(
                'tanggal_rencana'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ASESMEN AWAL
        |--------------------------------------------------------------------------
        */

        $asesmenAwal =
            AsesmenAwal::with([
                'guruBK',
                'tahunAjaran',
            ])
            ->where(
                'siswa_id',
                $siswaId
            )
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->latest(
                'tanggal_asesmen'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | GENERATE PDF
        |--------------------------------------------------------------------------
        */

        $pdf =
            Pdf::loadView(
                'laporan.pdf.perkembangan',
                [
                    'siswa' =>
                        $siswa,

                    'tahunTerpilih' =>
                        $tahunAjaran,

                    'layananDasar' =>
                        $layananDasar,

                    'peminatan' =>
                        $peminatan,

                    'responsif' =>
                        $responsif,

                    'tindakLanjut' =>
                        $tindakLanjut,

                    'asesmenAwal' =>
                        $asesmenAwal,
                ]
            );


        $pdf->setPaper(
            'a4',
            'portrait'
        );


        $namaSiswa =
            str_replace(
                ' ',
                '-',
                strtolower(
                    $siswa->nama_lengkap
                )
            );


        $namaTahun =
            str_replace(
                ['/', '\\', ' '],
                ['-', '-', '_'],
                $tahunAjaran->nama
            );


        $namaFile =
            'laporan-perkembangan-' .
            $namaSiswa .
            '-' .
            $namaTahun .
            '.pdf';


        return $pdf->download(
            $namaFile
        );
    }
}