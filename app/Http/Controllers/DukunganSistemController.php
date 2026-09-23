<?php

namespace App\Http\Controllers;

use App\Models\DukunganSistem;
use App\Models\GuruBK;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\RiwayatKelasSiswa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DukunganSistemController extends Controller
{
    /**
     * Mendapatkan Tahun Ajaran yang sedang aktif
     * berdasarkan session.
     */
    private function getTahunAjaranAktif()
    {
        $tahunAjaranId = session('tahun_ajaran_id');

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

        $tahunAjaran = TahunAjaran::find(
            $tahunAjaranId
        );

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


    /**
     * Daftar Dukungan Sistem
     */
    public function index(Request $request)
    {
        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        $query = DukunganSistem::with([
            'guruBK',
            'tahunAjaran',
            'siswa',
        ])
            ->latest('tanggal');


        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        if ($tahunAjaranId) {

            $query->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER JENIS KEGIATAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jenis')) {

            $query->where(
                'jenis_kegiatan',
                $request->jenis
            );
        }


        $dukunganSistem =
            $query->get();


        return view(
            'dukungan-sistem.index',
            compact(
                'dukunganSistem',
                'tahunAjaran',
                'tahunAjaranId'
            )
        );
    }


    /**
     * Form Tambah
     */
    public function create()
    {
        $tahunAjaranAktif =
            $this->getTahunAjaranAktif();


        if (!$tahunAjaranAktif) {

            return redirect()
                ->route(
                    'dukungan-sistem.index'
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
        | GURU BK
        |--------------------------------------------------------------------------
        */

        $guruBK =
            GuruBK::orderBy(
                'nama_lengkap'
            )->get();


        /*
        |--------------------------------------------------------------------------
        | DAFTAR TAHUN AJARAN
        |--------------------------------------------------------------------------
        |
        | Tetap dikirim ke Blade agar tidak merusak
        | form yang sudah dibuat.
        |
        */

        $tahunAjaran =
            TahunAjaran::orderByDesc(
                'tanggal_mulai'
            )->get();


        /*
        |--------------------------------------------------------------------------
        | TINGKAT
        |--------------------------------------------------------------------------
        |
        | Tingkat hanya dari kelas Tahun Ajaran aktif.
        |
        */

        $tingkat =
            Kelas::query()
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
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


        /*
        |--------------------------------------------------------------------------
        | JURUSAN
        |--------------------------------------------------------------------------
        */

        $jurusans =
            Jurusan::query()
                ->where(
                    'is_active',
                    true
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
                ->orderBy(
                    'nama'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | JENIS KEGIATAN
        |--------------------------------------------------------------------------
        */

        $jenisKegiatan = [
            'Kolaborasi',
            'Home Visit',
            'Pelaksanaan dan Tindak Lanjut Asesmen',
            'Penyusunan dan Pelaporan Program BK',
            'Evaluasi BK',
            'Pelaksanaan Administrasi dan Mekanisme BK',
            'Kegiatan Tambahan',
            'Pengembangan Keprofesian Guru BK',
        ];


        return view(
            'dukungan-sistem.create',
            compact(
                'guruBK',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'tingkat',
                'jurusans',
                'jenisKegiatan'
            )
        );
    }


    /**
     * Simpan Data
     */
    public function store(
        Request $request
    ) {
        $tahunAjaran =
            $this->getTahunAjaranAktif();


        if (!$tahunAjaran) {

            return redirect()
                ->route(
                    'dukungan-sistem.index'
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
        | VALIDASI
        |--------------------------------------------------------------------------
        |
        | tahun_ajaran_id sengaja TIDAK divalidasi
        | dari request karena harus mengikuti session.
        |
        */

        $validated =
            $request->validate(
                [
                    'guru_bk_id' =>
                        'required|exists:guru_bk,id',

                    'siswa_id' =>
                        'nullable|exists:siswa,id',

                    'jenis_kegiatan' =>
                        'required|string|max:100',

                    'tanggal' =>
                        'required|date',

                    'sasaran' =>
                        'required|string|max:255',

                    'uraian_kegiatan' =>
                        'required|string',

                    'hasil' =>
                        'nullable|string',

                    'evaluasi' =>
                        'nullable|string',

                    'tindak_lanjut' =>
                        'nullable|string',

                    'keterangan' =>
                        'nullable|string',
                ],
                [
                    'guru_bk_id.required' =>
                        'Guru BK wajib dipilih.',

                    'jenis_kegiatan.required' =>
                        'Jenis kegiatan wajib dipilih.',

                    'tanggal.required' =>
                        'Tanggal wajib diisi.',

                    'sasaran.required' =>
                        'Sasaran wajib diisi.',

                    'uraian_kegiatan.required' =>
                        'Uraian kegiatan wajib diisi.',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SISWA
        |--------------------------------------------------------------------------
        |
        | Jika siswa dipilih, siswa tersebut harus mempunyai
        | riwayat kelas pada Tahun Ajaran aktif.
        |
        */

        if (
            !empty(
                $validated['siswa_id']
            )
        ) {

            $siswaValid =
                Siswa::query()
                    ->where(
                        'id',
                        $validated['siswa_id']
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
                        'Siswa yang dipilih tidak terdaftar pada Tahun Ajaran ' .
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
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        DukunganSistem::create(
            $validated
        );


        return redirect()
            ->route(
                'dukungan-sistem.index'
            )
            ->with(
                'success',
                'Dukungan Sistem berhasil ditambahkan pada Tahun Ajaran ' .
                $tahunAjaran->nama .
                '.'
            );
    }


    /**
     * Detail
     */
    public function show(
        $id
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

        $dukunganSistem =
            DukunganSistem::with([
                'guruBK',
                'tahunAjaran',
                'siswa',
            ])
                ->where(
                    'id',
                    $id
                )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->firstOrFail();


        return view(
            'dukungan-sistem.show',
            compact(
                'dukunganSistem'
            )
        );
    }


    /**
     * Form Edit
     */
    public function edit(
        $id
    ) {

        $tahunAjaranAktif =
            $this->getTahunAjaranAktif();


        if (!$tahunAjaranAktif) {

            return redirect()
                ->route(
                    'dukungan-sistem.index'
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
        | DATA YANG BOLEH DIEDIT HANYA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $dukunganSistem =
            DukunganSistem::where(
                'id',
                $id
            )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->firstOrFail();


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
        | TINGKAT
        |--------------------------------------------------------------------------
        */

        $tingkat =
            Kelas::query()
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
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


        /*
        |--------------------------------------------------------------------------
        | JURUSAN
        |--------------------------------------------------------------------------
        */

        $jurusans =
            Jurusan::query()
                ->where(
                    'is_active',
                    true
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
                ->orderBy(
                    'nama'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | JENIS KEGIATAN
        |--------------------------------------------------------------------------
        */

        $jenisKegiatan = [
            'Kolaborasi',
            'Home Visit',
            'Pelaksanaan dan Tindak Lanjut Asesmen',
            'Penyusunan dan Pelaporan Program BK',
            'Evaluasi BK',
            'Pelaksanaan Administrasi dan Mekanisme BK',
            'Kegiatan Tambahan',
            'Pengembangan Keprofesian Guru BK',
        ];


        return view(
            'dukungan-sistem.edit',
            compact(
                'dukunganSistem',
                'guruBK',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'tingkat',
                'jurusans',
                'jenisKegiatan'
            )
        );
    }


    /**
     * Update
     */
    public function update(
        Request $request,
        $id
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();


        if (!$tahunAjaran) {

            return redirect()
                ->route(
                    'dukungan-sistem.index'
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
        | AMBIL DATA HANYA DARI TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $dukunganSistem =
            DukunganSistem::where(
                'id',
                $id
            )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->firstOrFail();


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

                    'siswa_id' =>
                        'nullable|exists:siswa,id',

                    'jenis_kegiatan' =>
                        'required|string|max:100',

                    'tanggal' =>
                        'required|date',

                    'sasaran' =>
                        'required|string|max:255',

                    'uraian_kegiatan' =>
                        'required|string',

                    'hasil' =>
                        'nullable|string',

                    'evaluasi' =>
                        'nullable|string',

                    'tindak_lanjut' =>
                        'nullable|string',

                    'keterangan' =>
                        'nullable|string',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SISWA
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['siswa_id']
            )
        ) {

            $siswaValid =
                Siswa::query()
                    ->where(
                        'id',
                        $validated['siswa_id']
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
                        'Siswa yang dipilih tidak terdaftar pada Tahun Ajaran ' .
                        $tahunAjaran->nama .
                        '.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | JANGAN IZINKAN PERUBAHAN TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['tahun_ajaran_id']
        );


        $dukunganSistem->update(
            $validated
        );


        return redirect()
            ->route(
                'dukungan-sistem.index'
            )
            ->with(
                'success',
                'Dukungan Sistem berhasil diperbarui.'
            );
    }


    /**
     * Hapus
     */
    public function destroy(
        $id
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | HANYA BOLEH HAPUS DATA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $dukunganSistem =
            DukunganSistem::where(
                'id',
                $id
            )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->firstOrFail();


        $dukunganSistem->delete();


        return redirect()
            ->route(
                'dukungan-sistem.index'
            )
            ->with(
                'success',
                'Dukungan Sistem berhasil dihapus.'
            );
    }


    /**
     * PDF
     */
    public function downloadPdf(
        DukunganSistem $dukunganSistem
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
            $dukunganSistem
                ->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        $dukunganSistem->load([
            'guruBK',
            'tahunAjaran',
            'siswa',
        ]);


        $pdf =
            Pdf::loadView(
                'dukungan-sistem.pdf',
                compact(
                    'dukunganSistem'
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
                $dukunganSistem
                    ->jenis_kegiatan
                    ?? 'Data'
            );


        $namaTahun =
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $dukunganSistem
                    ->tahunAjaran
                    ?->nama
                    ?? 'Tahun-Ajaran'
            );


        $namaFile =
            'Dukungan-Sistem-' .
            $namaJenis .
            '-' .
            $namaTahun .
            '-' .
            $dukunganSistem->id .
            '.pdf';


        return $pdf->download(
            $namaFile
        );
    }


    /**
     * AJAX:
     * Tahun Ajaran + Tingkat
     * -> Jurusan
     */
    public function getJurusan(
        Request $request
    ) {

        /*
        |--------------------------------------------------------------------------
        | PAKAI TAHUN AJARAN DARI SESSION
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


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


        /*
        |--------------------------------------------------------------------------
        | AMBIL JURUSAN YANG TERSEDIA DI TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $jurusanIds =
            Kelas::query()
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


        return Jurusan::query()
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
    }


    /**
     * AJAX:
     * Tahun Ajaran + Tingkat + Jurusan
     * -> Kelas
     */
    public function getKelas(
        Request $request
    ) {

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN DARI SESSION
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


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


        return Kelas::query()
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
                'nama_kelas',
            ]);
    }


    /**
     * AJAX:
     * Kelas -> Siswa
     */
    public function getSiswa(
        Request $request
    ) {

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        $kelasId =
            $request->get(
                'kelas_id'
            );


        if (
            !$tahunAjaranId ||
            !$kelasId
        ) {

            return response()->json([]);
        }


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN KELAS MILIK TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

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


        if (!$kelasValid) {

            return response()->json([]);
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL SISWA DARI RIWAYAT KELAS
        |--------------------------------------------------------------------------
        */

        $siswaIds =
            RiwayatKelasSiswa::query()
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


        /*
        |--------------------------------------------------------------------------
        | DATA SISWA
        |--------------------------------------------------------------------------
        */

        return Siswa::query()
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
                'status',
            ]);
    }
}