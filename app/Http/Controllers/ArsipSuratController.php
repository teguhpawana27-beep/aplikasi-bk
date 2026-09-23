<?php

namespace App\Http\Controllers;

use App\Models\ArsipSurat;
use App\Models\Siswa;
use App\Models\GuruBK;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\RiwayatKelasSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ArsipSuratController extends Controller
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
            $tahunAjaran = TahunAjaran::find($tahunAjaranId);

            if ($tahunAjaran) {
                return $tahunAjaran;
            }
        }

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

        return $tahunAjaran;
    }


    /*
    |--------------------------------------------------------------------------
    | DAFTAR ARSIP SURAT
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $tahunAjaran = $this->getTahunAjaranAktif();

        $query = ArsipSurat::with([
            'siswa',
            'guruBK',
            'tahunAjaran',
        ]);

        if ($tahunAjaran) {
            $query->where(
                'tahun_ajaran_id',
                $tahunAjaran->id
            );
        }

        if ($request->filled('jenis')) {
            $query->where(
                'jenis_surat',
                $request->jenis
            );
        }

        $arsipSurat = $query
            ->latest('tanggal_surat')
            ->get();

        return view(
            'arsip-surat.index',
            compact(
                'arsipSurat',
                'tahunAjaran'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $tahunAjaran = $this->getTahunAjaranAktif();

        $guruBK = GuruBK::orderBy(
            'nama_lengkap',
            'asc'
        )->get();

        /*
        |--------------------------------------------------------------------------
        | TINGKAT PADA TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $tingkat = collect();

        if ($tahunAjaran) {
            $tingkat = Kelas::query()
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaran->id
                )
                ->where(
                    'is_active',
                    true
                )
                ->whereNotNull('tingkat')
                ->select('tingkat')
                ->distinct()
                ->orderBy('tingkat')
                ->pluck('tingkat')
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | JENIS SURAT
        |--------------------------------------------------------------------------
        */

        $jenisSurat = [
            'SP 1',
            'SP 2',
            'SP 3',
            'Pemanggilan Orang Tua',
            'Home Visit',
            'Pengunduran Diri',
            'Peringatan',
        ];

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |
        | Tetap dikirim untuk kompatibilitas Blade lama.
        | Tetapi tahun yang digunakan untuk menyimpan data
        | selalu berasal dari session/context.
        |--------------------------------------------------------------------------
        */

        $tahunAjaranList = TahunAjaran::orderByDesc(
            'tanggal_mulai'
        )->get();

        return view(
            'arsip-surat.create',
            [
                'guruBK' => $guruBK,
                'tahunAjaran' => $tahunAjaranList,
                'tahunAjaranAktif' => $tahunAjaran,
                'tahunAjaranId' => $tahunAjaran?->id,
                'tingkat' => $tingkat,
                'jenisSurat' => $jenisSurat,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $tahunAjaran = $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Tahun ajaran aktif belum tersedia.'
                );
        }

        $validated = $request->validate([
            'siswa_id' => [
                'required',
                'exists:siswa,id',
            ],

            'guru_bk_id' => [
                'required',
                'exists:guru_bk,id',
            ],

            'jenis_surat' => [
                'required',
                'string',
                'max:100',
            ],

            'nomor_surat' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tanggal_surat' => [
                'required',
                'date',
            ],

            'perihal' => [
                'required',
                'string',
                'max:255',
            ],

            'isi_ringkas' => [
                'nullable',
                'string',
            ],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SISWA HARUS BERADA PADA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $siswaValid = RiwayatKelasSiswa::query()
            ->where(
                'siswa_id',
                $validated['siswa_id']
            )
            ->whereHas(
                'kelas',
                function ($query) use ($tahunAjaran) {
                    $query
                        ->where(
                            'tahun_ajaran_id',
                            $tahunAjaran->id
                        )
                        ->where(
                            'is_active',
                            true
                        );
                }
            )
            ->exists();

        if (!$siswaValid) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Siswa tidak terdaftar pada tahun ajaran yang sedang aktif.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN OTOMATIS
        |--------------------------------------------------------------------------
        */

        $validated['tahun_ajaran_id'] =
            $tahunAjaran->id;


        /*
        |--------------------------------------------------------------------------
        | UPLOAD FILE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            $validated['file_path'] =
                $request
                    ->file('file')
                    ->store(
                        'arsip-surat',
                        'public'
                    );
        }

        unset($validated['file']);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        ArsipSurat::create(
            $validated
        );

        return redirect()
            ->route('arsip-surat.index')
            ->with(
                'success',
                'Arsip surat berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $tahunAjaran = $this->getTahunAjaranAktif();

        $arsipSurat = ArsipSurat::with([
            'siswa',
            'guruBK',
            'tahunAjaran',
        ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaran?->id
            )
            ->findOrFail($id);

        return view(
            'arsip-surat.show',
            compact(
                'arsipSurat',
                'tahunAjaran'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BUKA FILE SURAT
    |--------------------------------------------------------------------------
    */

    public function file($id)
    {
        $tahunAjaran = $this->getTahunAjaranAktif();

        /*
        |--------------------------------------------------------------------------
        | HANYA FILE TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $arsipSurat = ArsipSurat::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAjaran?->id
            )
            ->findOrFail($id);


        if (!$arsipSurat->file_path) {

            return back()->with(
                'error',
                'File surat belum tersedia.'
            );
        }


        if (
            !Storage::disk('public')
                ->exists(
                    $arsipSurat->file_path
                )
        ) {

            return back()->with(
                'error',
                'File surat tidak ditemukan.'
            );
        }


        return response()->file(
            Storage::disk('public')
                ->path(
                    $arsipSurat->file_path
                )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CETAK PDF
    |--------------------------------------------------------------------------
    */

    public function downloadPdf($id)
    {
        $tahunAjaran = $this->getTahunAjaranAktif();

        $arsipSurat = ArsipSurat::with([
            'siswa',
            'guruBK',
            'tahunAjaran',
        ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaran?->id
            )
            ->findOrFail($id);


        $pdf = Pdf::loadView(
            'arsip-surat.pdf',
            compact('arsipSurat')
        );


        $pdf->setPaper(
            'A4',
            'portrait'
        );


        $namaTahun = $arsipSurat->tahunAjaran?->nama
            ?? 'tahun-ajaran';

        $namaTahun = str_replace(
            ['/', '\\', ' '],
            ['-', '-', '_'],
            $namaTahun
        );


        return $pdf->stream(
            'arsip-surat-' .
            $namaTahun .
            '-' .
            $arsipSurat->id .
            '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $tahunAjaran = $this->getTahunAjaranAktif();

        /*
        |--------------------------------------------------------------------------
        | HANYA DATA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $arsipSurat = ArsipSurat::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAjaran?->id
            )
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | DATA GURU BK
        |--------------------------------------------------------------------------
        */

        $guruBK = GuruBK::orderBy(
            'nama_lengkap',
            'asc'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | DATA TAHUN AJARAN
        |
        | Tetap dikirim untuk kompatibilitas Blade.
        |--------------------------------------------------------------------------
        */

        $tahunAjaranList =
            TahunAjaran::orderByDesc(
                'tanggal_mulai'
            )->get();


        /*
        |--------------------------------------------------------------------------
        | JENIS SURAT
        |--------------------------------------------------------------------------
        */

        $jenisSurat = [
            'SP 1',
            'SP 2',
            'SP 3',
            'Pemanggilan Orang Tua',
            'Home Visit',
            'Pengunduran Diri',
            'Peringatan',
        ];


        /*
        |--------------------------------------------------------------------------
        | CARI RIWAYAT KELAS SISWA
        |--------------------------------------------------------------------------
        */

        $riwayatKelas =
            RiwayatKelasSiswa::with([
                'kelas.jurusan',
            ])
                ->where(
                    'siswa_id',
                    $arsipSurat->siswa_id
                )
                ->whereHas(
                    'kelas',
                    function ($query) use ($tahunAjaran) {

                        $query->where(
                            'tahun_ajaran_id',
                            $tahunAjaran->id
                        );
                    }
                )
                ->orderByDesc(
                    'tanggal_mulai'
                )
                ->first();


        /*
        |--------------------------------------------------------------------------
        | NILAI LAMA UNTUK DROPDOWN EDIT
        |--------------------------------------------------------------------------
        */

        $kelasLamaModel =
            $riwayatKelas?->kelas;

        $tingkatLama =
            $kelasLamaModel?->tingkat;

        $jurusanLama =
            $kelasLamaModel?->jurusan_id;

        $kelasLama =
            $kelasLamaModel?->id;


        /*
        |--------------------------------------------------------------------------
        | TINGKAT TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $tingkat = Kelas::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAjaran->id
            )
            ->where(
                'is_active',
                true
            )
            ->whereNotNull('tingkat')
            ->select('tingkat')
            ->distinct()
            ->orderBy('tingkat')
            ->pluck('tingkat')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | KIRIM KE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'arsip-surat.edit',
            [
                'arsipSurat' => $arsipSurat,

                'guruBK' => $guruBK,

                'tahunAjaran' => $tahunAjaranList,

                'tahunAjaranAktif' => $tahunAjaran,

                'tahunAjaranId' => $tahunAjaran->id,

                'jenisSurat' => $jenisSurat,

                'tingkat' => $tingkat,

                'tingkatLama' => $tingkatLama,

                'jurusanLama' => $jurusanLama,

                'kelasLama' => $kelasLama,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $tahunAjaran = $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Tahun ajaran aktif belum tersedia.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | HANYA RECORD TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $arsipSurat =
            ArsipSurat::query()
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaran->id
                )
                ->findOrFail($id);


        $validated = $request->validate([
            'siswa_id' => [
                'required',
                'exists:siswa,id',
            ],

            'guru_bk_id' => [
                'required',
                'exists:guru_bk,id',
            ],

            'jenis_surat' => [
                'required',
                'string',
                'max:100',
            ],

            'nomor_surat' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tanggal_surat' => [
                'required',
                'date',
            ],

            'perihal' => [
                'required',
                'string',
                'max:255',
            ],

            'isi_ringkas' => [
                'nullable',
                'string',
            ],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SISWA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $siswaValid = RiwayatKelasSiswa::query()
            ->where(
                'siswa_id',
                $validated['siswa_id']
            )
            ->whereHas(
                'kelas',
                function ($query) use ($tahunAjaran) {
                    $query
                        ->where(
                            'tahun_ajaran_id',
                            $tahunAjaran->id
                        )
                        ->where(
                            'is_active',
                            true
                        );
                }
            )
            ->exists();

        if (!$siswaValid) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Siswa tidak terdaftar pada tahun ajaran yang sedang aktif.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN TIDAK BOLEH DIUBAH DARI FORM
        |--------------------------------------------------------------------------
        */

        $validated['tahun_ajaran_id'] =
            $tahunAjaran->id;


        /*
        |--------------------------------------------------------------------------
        | FILE BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            /*
            |--------------------------------------------------------------------------
            | HAPUS FILE LAMA
            |--------------------------------------------------------------------------
            */

            if (
                $arsipSurat->file_path &&
                Storage::disk('public')
                    ->exists(
                        $arsipSurat->file_path
                    )
            ) {

                Storage::disk('public')
                    ->delete(
                        $arsipSurat->file_path
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | SIMPAN FILE BARU
            |--------------------------------------------------------------------------
            */

            $validated['file_path'] =
                $request
                    ->file('file')
                    ->store(
                        'arsip-surat',
                        'public'
                    );
        }


        unset(
            $validated['file']
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATABASE
        |--------------------------------------------------------------------------
        */

        $arsipSurat->update(
            $validated
        );


        return redirect()
            ->route(
                'arsip-surat.index'
            )
            ->with(
                'success',
                'Arsip surat berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $tahunAjaran = $this->getTahunAjaranAktif();

        /*
        |--------------------------------------------------------------------------
        | HANYA DATA TAHUN AKTIF YANG BOLEH DIHAPUS
        |--------------------------------------------------------------------------
        */

        $arsipSurat =
            ArsipSurat::query()
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaran?->id
                )
                ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE
        |--------------------------------------------------------------------------
        */

        if (
            $arsipSurat->file_path &&
            Storage::disk('public')
                ->exists(
                    $arsipSurat->file_path
                )
        ) {

            Storage::disk('public')
                ->delete(
                    $arsipSurat->file_path
                );
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS DATA
        |--------------------------------------------------------------------------
        */

        $arsipSurat->delete();


        return redirect()
            ->route(
                'arsip-surat.index'
            )
            ->with(
                'success',
                'Arsip surat berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX 1
    | TAHUN AJARAN -> TINGKAT
    |--------------------------------------------------------------------------
    */

    public function tingkat(
        Request $request
    ) {

        $tahunAjaran = $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {
            return response()->json([]);
        }


        $tingkat =
            Kelas::query()
                ->where(
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
                )
                ->values();


        return response()->json(
            $tingkat
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX 2
    | TAHUN AJARAN + TINGKAT -> JURUSAN
    |--------------------------------------------------------------------------
    */

    public function jurusan(
        Request $request
    ) {

        $tahunAjaran = $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {
            return response()->json([]);
        }

        $request->validate([
            'tingkat' => [
                'required',
            ],
        ]);


        $jurusanIds =
            Kelas::query()
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
                )
                ->whereNotNull(
                    'jurusan_id'
                )
                ->pluck(
                    'jurusan_id'
                )
                ->unique();


        $jurusan =
            Jurusan::query()
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
                    'nama',
                ]);


        return response()->json(
            $jurusan
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX 3
    | TAHUN AJARAN + TINGKAT + JURUSAN -> KELAS
    |--------------------------------------------------------------------------
    */

    public function kelas(
        Request $request
    ) {

        $tahunAjaran = $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {
            return response()->json([]);
        }

        $request->validate([
            'tingkat' => [
                'required',
            ],

            'jurusan_id' => [
                'required',
                'exists:jurusan,id',
            ],
        ]);


        $kelas =
            Kelas::query()
                ->where(
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
            $kelas
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX 4
    | KELAS -> SISWA
    |--------------------------------------------------------------------------
    */

    public function siswa(
        Request $request
    ) {

        $tahunAjaran = $this->getTahunAjaranAktif();

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
        | PASTIKAN KELAS MILIK TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $kelas = Kelas::query()
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
        | AMBIL SISWA MELALUI RIWAYAT KELAS
        |--------------------------------------------------------------------------
        */

        $riwayat =
            RiwayatKelasSiswa::with(
                'siswa'
            )
                ->where(
                    'kelas_id',
                    $kelas->id
                )
                ->orderBy(
                    'id'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | AMBIL OBJECT SISWA
        |--------------------------------------------------------------------------
        */

        $siswa =
            $riwayat
                ->pluck('siswa')
                ->filter()
                ->unique('id')
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
            $siswa
        );
    }
}