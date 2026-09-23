<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\RiwayatKelasSiswa;
use App\Models\TahunAjaran;
use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * =========================================================
     * DAFTAR SISWA
     * =========================================================
     *
     * DATA SISWA DITAMPILKAN BERDASARKAN TAHUN AJARAN
     * YANG DIPILIH PADA DASHBOARD.
     *
     * Alur:
     *
     * Tahun Ajaran
     *      ↓
     * Tingkat
     *      ↓
     * Jurusan
     *      ↓
     * Kelas
     *      ↓
     * Siswa
     *
     * DATA SISWA = MASTER
     *
     * Hubungan siswa dengan tahun ajaran:
     *
     * siswa
     *   ↓
     * riwayat_kelas_siswa
     *   ↓
     * kelas
     *   ↓
     * tahun_ajaran
     *
     * =========================================================
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN DARI SESSION
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session('tahun_ajaran_id');

        if (!$tahunAjaranId) {
            $tahunAjaran = TahunAjaran::where('is_active', true)
                ->orderByDesc('tanggal_mulai')
                ->first();

            if (!$tahunAjaran) {
                $tahunAjaran = TahunAjaran::orderByDesc('tanggal_mulai')->first();
            }

            if ($tahunAjaran) {
                $tahunAjaranId = $tahunAjaran->id;
                session(['tahun_ajaran_id' => $tahunAjaranId]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DATA TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaran = $tahunAjaranId
            ? TahunAjaran::find($tahunAjaranId)
            : null;

        if (!$tahunAjaran) {
            $tahunAjaran = TahunAjaran::where('is_active', true)
                ->orderByDesc('tanggal_mulai')
                ->first();

            if (!$tahunAjaran) {
                $tahunAjaran = TahunAjaran::orderByDesc('tanggal_mulai')->first();
            }

            if ($tahunAjaran) {
                $tahunAjaranId = $tahunAjaran->id;
                session(['tahun_ajaran_id' => $tahunAjaranId]);
            } else {
                $tahunAjaranId = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER SISWA DARI SESSION
        |--------------------------------------------------------------------------
        |
        | Tingkat dan jurusan dipertahankan ketika tahun ajaran berubah.
        | Kelas disimpan berdasarkan nama, bukan ID, karena ID kelas dapat
        | berbeda pada setiap tahun ajaran.
        |
        */

        $filterSiswa = session('siswa_filter', []);

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN TINGKAT
        |--------------------------------------------------------------------------
        */

        if ($request->has('tingkat')) {
            $tingkat = $request->get('tingkat');
        } else {
            $tingkat = $filterSiswa['tingkat'] ?? null;
        }

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN JURUSAN
        |--------------------------------------------------------------------------
        */

        if ($request->has('jurusan')) {
            $jurusanId = $request->get('jurusan');
        } else {
            $jurusanId = $filterSiswa['jurusan_id'] ?? null;
        }

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN NAMA KELAS
        |--------------------------------------------------------------------------
        */

        $kelasNama = $filterSiswa['kelas_nama'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | JIKA USER MEMILIH KELAS
        |--------------------------------------------------------------------------
        */

        if ($request->has('kelas')) {
            $requestKelasId = $request->get('kelas');

            if (!$requestKelasId) {
                $kelasNama = null;
            } else {
                $kelasDariRequest = null;

                if ($tahunAjaranId) {
                    $kelasDariRequest = Kelas::query()
                        ->where('id', $requestKelasId)
                        ->where('tahun_ajaran_id', $tahunAjaranId)
                        ->where('tingkat', $tingkat)
                        ->where('jurusan_id', $jurusanId)
                        ->first();
                }

                if ($kelasDariRequest) {
                    $kelasNama = $kelasDariRequest->nama_kelas;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PILIH TINGKAT BARU = RESET JURUSAN DAN KELAS
        |--------------------------------------------------------------------------
        */

        if ($request->has('tingkat') && !$request->has('jurusan')) {
            $jurusanId = null;
            $kelasNama = null;
        }

        /*
        |--------------------------------------------------------------------------
        | PILIH JURUSAN BARU = RESET KELAS
        |--------------------------------------------------------------------------
        */

        if ($request->has('jurusan') && !$request->has('kelas')) {
            $kelasNama = null;
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FILTER KE SESSION
        |--------------------------------------------------------------------------
        */

        session([
            'siswa_filter' => [
                'tingkat' => $tingkat,
                'jurusan_id' => $jurusanId,
                'kelas_nama' => $kelasNama,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | DEFAULT DATA
        |--------------------------------------------------------------------------
        */

        $tingkatList = collect();
        $jurusanList = collect();
        $kelasList = collect();
        $siswa = collect();
        $kelasTerpilih = null;

        /*
        |--------------------------------------------------------------------------
        | TINGKAT
        |--------------------------------------------------------------------------
        |
        | Tingkat TIDAK dibatasi tahun ajaran.
        | Dengan demikian Tingkat 10/11/12 tetap muncul walaupun tahun
        | ajaran baru belum memiliki kelas.
        |
        */

        $tingkatList = Kelas::query()
            ->select('tingkat')
            ->whereNotNull('tingkat')
            ->distinct()
            ->orderBy('tingkat')
            ->pluck('tingkat');

        if ($tingkatList->isEmpty()) {
            $tingkatList = collect([10, 11, 12]);
        }

        /*
        |--------------------------------------------------------------------------
        | JURUSAN
        |--------------------------------------------------------------------------
        |
        | Jurusan berasal dari tabel jurusan sehingga tidak hilang ketika
        | tahun ajaran berganti.
        |
        */

        $jurusanList = \App\Models\Jurusan::query()
            ->where('is_active', true)
            ->orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        |
        | Kelas tetap mengikuti tahun ajaran yang sedang dipilih.
        |
        */

        if ($tahunAjaranId && $tingkat && $jurusanId) {
            $kelasList = Kelas::query()
                ->with([
                    'jurusan',
                    'tahunAjaran',
                ])
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('tingkat', $tingkat)
                ->where('jurusan_id', $jurusanId)
                ->orderBy('nama_kelas')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | CARI KELAS TERPILIH PADA TAHUN AJARAN SEKARANG
        |--------------------------------------------------------------------------
        |
        | Gunakan kombinasi:
        | tahun ajaran + tingkat + jurusan + nama kelas.
        |
        | BUKAN menggunakan ID kelas tahun sebelumnya.
        |
        */

        if ($tahunAjaranId && $tingkat && $jurusanId && $kelasNama) {
            $kelasTerpilih = Kelas::query()
                ->with([
                    'jurusan',
                    'tahunAjaran',
                ])
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('tingkat', $tingkat)
                ->where('jurusan_id', $jurusanId)
                ->where('nama_kelas', $kelasNama)
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | KELAS ID UNTUK VIEW
        |--------------------------------------------------------------------------
        */

        $kelasId = $kelasTerpilih?->id;

        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        |
        | Siswa diambil dari riwayat kelas pada kelas yang ditemukan untuk
        | tahun ajaran aktif.
        |
        */

        if ($kelasTerpilih) {
            $siswa = Siswa::query()
                ->whereHas(
                    'riwayatKelas',
                    function ($query) use ($kelasTerpilih) {
                        $query->where(
                            'kelas_id',
                            $kelasTerpilih->id
                        );
                    }
                )
                ->orderBy('nama_lengkap')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'siswa.index',
            compact(
                'siswa',
                'tingkatList',
                'jurusanList',
                'kelasList',
                'tingkat',
                'jurusanId',
                'kelasId',
                'kelasTerpilih',
                'tahunAjaran',
                'tahunAjaranId'
            )
        );
    }

    /**
     * =========================================================
     * DOWNLOAD TEMPLATE EXCEL
     * =========================================================
     */
    public function downloadTemplate()
    {
        return Excel::download(
            new SiswaTemplateExport,
            'template-data-siswa.xlsx'
        );
    }


    /**
     * =========================================================
     * COMPATIBILITY METHOD
     * =========================================================
     */
    public function template()
    {
        return $this->downloadTemplate();
    }


    /**
     * =========================================================
     * IMPORT DATA SISWA
     * =========================================================
     */
    public function import(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN DARI DASHBOARD
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session(
            'tahun_ajaran_id'
        );

        if (!$tahunAjaranId) {

            return redirect()
                ->route('siswa.index')
                ->with(
                    'error',
                    'Tahun ajaran belum dipilih.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaran = TahunAjaran::find(
            $tahunAjaranId
        );

        if (!$tahunAjaran) {

            return redirect()
                ->route('siswa.index')
                ->with(
                    'error',
                    'Tahun ajaran yang dipilih tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI FILE
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'file' => [
                    'required',
                    'file',
                    'mimes:xlsx,xls,csv',
                    'max:10240',
                ],
            ],
            [
                'file.required' =>
                    'Silakan pilih file Excel terlebih dahulu.',

                'file.file' =>
                    'File yang dipilih tidak valid.',

                'file.mimes' =>
                    'File harus berupa Excel (.xlsx/.xls) atau CSV.',

                'file.max' =>
                    'Ukuran file maksimal 10 MB.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | IMPORT
        |--------------------------------------------------------------------------
        */

        try {

            Excel::import(
                new SiswaImport,
                $request->file('file')
            );

            return redirect()
                ->route('siswa.index')
                ->with(
                    'success',
                    'Data siswa berhasil diimport ke tahun ajaran ' .
                    $tahunAjaran->nama .
                    '.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('siswa.index')
                ->with(
                    'error',
                    'Import gagal: ' .
                    $e->getMessage()
                );
        }
    }


    /**
     * =========================================================
     * FORM TAMBAH SISWA
     * =========================================================
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN CONTEXT
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session(
            'tahun_ajaran_id'
        );

        $tahunAjaran = null;

        if ($tahunAjaranId) {

            $tahunAjaran = TahunAjaran::find(
                $tahunAjaranId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | KELAS HANYA DARI TAHUN AJARAN TERPILIH
        |--------------------------------------------------------------------------
        */

        $kelas = collect();

        if ($tahunAjaranId) {

            $kelas = Kelas::query()
                ->with([
                    'jurusan',
                    'tahunAjaran',
                ])
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->orderBy('tingkat')
                ->orderBy('nama_kelas')
                ->get();
        }

        return view(
            'siswa.create',
            compact(
                'tahunAjaran',
                'tahunAjaranId',
                'kelas'
            )
        );
    }


    /**
     * =========================================================
     * SIMPAN SISWA
     * =========================================================
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN DARI DASHBOARD
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session(
            'tahun_ajaran_id'
        );

        if (!$tahunAjaranId) {

            return redirect()
                ->route('siswa.index')
                ->with(
                    'error',
                    'Tahun ajaran belum dipilih.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'nis' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:siswa,nis',
                ],

                'nisn' => [
                    'nullable',
                    'string',
                    'max:50',
                    'unique:siswa,nisn',
                ],

                'nama_lengkap' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'jenis_kelamin' => [
                    'required',
                    'in:L,P',
                ],

                'tempat_lahir' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'tanggal_lahir' => [
                    'nullable',
                    'date',
                ],

                'alamat' => [
                    'nullable',
                    'string',
                ],

                'no_hp' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'tahun_masuk' => [
                    'required',
                    'integer',
                    'min:2000',
                    'max:2100',
                ],

                'status' => [
                    'required',
                    'in:aktif,lulus,pindah,keluar',
                ],

                'kelas_id' => [
                    'nullable',
                    'integer',
                    'exists:kelas,id',
                ],
            ],
            [
                'nis.required' =>
                    'NIS wajib diisi.',

                'nis.unique' =>
                    'NIS sudah terdaftar.',

                'nisn.unique' =>
                    'NISN sudah terdaftar.',

                'nama_lengkap.required' =>
                    'Nama lengkap wajib diisi.',

                'jenis_kelamin.required' =>
                    'Jenis kelamin wajib dipilih.',

                'jenis_kelamin.in' =>
                    'Jenis kelamin harus L atau P.',

                'tahun_masuk.required' =>
                    'Tahun masuk wajib diisi.',

                'status.required' =>
                    'Status siswa wajib dipilih.',

                'status.in' =>
                    'Status siswa tidak valid.',

                'kelas_id.exists' =>
                    'Kelas yang dipilih tidak ditemukan.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        */

        $kelasId = $validated[
            'kelas_id'
        ] ?? null;

        unset(
            $validated['kelas_id']
        );

        $kelas = null;

        if ($kelasId) {

            $kelas = Kelas::query()
                ->where(
                    'id',
                    $kelasId
                )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->first();

            if (!$kelas) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Kelas yang dipilih tidak sesuai dengan tahun ajaran saat ini.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $validated,
                $kelas
            ) {

                $siswa = Siswa::create(
                    $validated
                );

                if ($kelas) {

                    RiwayatKelasSiswa::create([
                        'siswa_id' =>
                            $siswa->id,

                        'kelas_id' =>
                            $kelas->id,

                        'tanggal_mulai' =>
                            now()->toDateString(),

                        'tanggal_selesai' =>
                            null,

                        'status' =>
                            'aktif',

                        'keterangan' =>
                            'Penempatan siswa',
                    ]);
                }
            }
        );

        return redirect()
            ->route('siswa.index')
            ->with(
                'success',
                'Data siswa berhasil ditambahkan ke tahun ajaran yang sedang dipilih.'
            );
    }


    /**
     * =========================================================
     * DETAIL
     * =========================================================
     */
    public function show(Siswa $siswa)
    {
        return view(
            'siswa.show',
            compact('siswa')
        );
    }


    /**
     * =========================================================
     * EDIT
     * =========================================================
     */
    public function edit(Siswa $siswa)
    {
        $tahunAjaranId = session(
            'tahun_ajaran_id'
        );

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT KELAS PADA TAHUN AJARAN SAAT INI
        |--------------------------------------------------------------------------
        */

        $riwayatKelas = null;

        if ($tahunAjaranId) {

            $riwayatKelas = RiwayatKelasSiswa::query()
                ->with([
                    'kelas.jurusan',
                    'kelas.tahunAjaran',
                ])
                ->where(
                    'siswa_id',
                    $siswa->id
                )
                ->whereHas(
                    'kelas',
                    function ($query) use (
                        $tahunAjaranId
                    ) {

                        $query->where(
                            'tahun_ajaran_id',
                            $tahunAjaranId
                        );
                    }
                )
                ->orderByDesc(
                    'tanggal_mulai'
                )
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | KELAS HANYA TAHUN SAAT INI
        |--------------------------------------------------------------------------
        */

        $kelas = collect();

        if ($tahunAjaranId) {

            $kelas = Kelas::query()
                ->with([
                    'jurusan',
                    'tahunAjaran',
                ])
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->orderBy('tingkat')
                ->orderBy('nama_kelas')
                ->get();
        }

        return view(
            'siswa.edit',
            compact(
                'siswa',
                'tahunAjaranId',
                'riwayatKelas',
                'kelas'
            )
        );
    }


    /**
     * =========================================================
     * UPDATE
     * =========================================================
     */
    public function update(
        Request $request,
        Siswa $siswa
    ) {

        $tahunAjaranId = session(
            'tahun_ajaran_id'
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'nis' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:siswa,nis,' .
                    $siswa->id,
                ],

                'nisn' => [
                    'nullable',
                    'string',
                    'max:50',
                    'unique:siswa,nisn,' .
                    $siswa->id,
                ],

                'nama_lengkap' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'jenis_kelamin' => [
                    'required',
                    'in:L,P',
                ],

                'tempat_lahir' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'tanggal_lahir' => [
                    'nullable',
                    'date',
                ],

                'alamat' => [
                    'nullable',
                    'string',
                ],

                'no_hp' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'tahun_masuk' => [
                    'required',
                    'integer',
                    'min:2000',
                    'max:2100',
                ],

                'status' => [
                    'required',
                    'in:aktif,lulus,pindah,keluar',
                ],

                'kelas_id' => [
                    'nullable',
                    'integer',
                    'exists:kelas,id',
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        */

        $kelasId = $validated[
            'kelas_id'
        ] ?? null;

        unset(
            $validated['kelas_id']
        );

        $kelas = null;

        if (
            $kelasId &&
            $tahunAjaranId
        ) {

            $kelas = Kelas::query()
                ->where(
                    'id',
                    $kelasId
                )
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->first();

            if (!$kelas) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Kelas tidak sesuai dengan tahun ajaran yang sedang dipilih.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $siswa,
                $validated,
                $kelas,
                $tahunAjaranId
            ) {

                /*
                |--------------------------------------------------------------------------
                | UPDATE MASTER SISWA
                |--------------------------------------------------------------------------
                */

                $siswa->update(
                    $validated
                );

                /*
                |--------------------------------------------------------------------------
                | UPDATE / BUAT RIWAYAT KELAS
                |--------------------------------------------------------------------------
                */

                if (
                    $tahunAjaranId &&
                    $kelas
                ) {

                    $riwayatLama =
                        RiwayatKelasSiswa::query()
                            ->where(
                                'siswa_id',
                                $siswa->id
                            )
                            ->whereHas(
                                'kelas',
                                function ($query) use (
                                    $tahunAjaranId
                                ) {

                                    $query->where(
                                        'tahun_ajaran_id',
                                        $tahunAjaranId
                                    );
                                }
                            )
                            ->orderByDesc(
                                'tanggal_mulai'
                            )
                            ->first();

                    /*
                    |--------------------------------------------------------------------------
                    | SUDAH ADA RIWAYAT
                    |--------------------------------------------------------------------------
                    */

                    if ($riwayatLama) {

                        if (
                            $riwayatLama->kelas_id !=
                            $kelas->id
                        ) {

                            $riwayatLama->update([
                                'tanggal_selesai' =>
                                    now()->toDateString(),

                                'status' =>
                                    'selesai',
                            ]);

                            RiwayatKelasSiswa::create([
                                'siswa_id' =>
                                    $siswa->id,

                                'kelas_id' =>
                                    $kelas->id,

                                'tanggal_mulai' =>
                                    now()->toDateString(),

                                'tanggal_selesai' =>
                                    null,

                                'status' =>
                                    'aktif',

                                'keterangan' =>
                                    'Perubahan kelas',
                            ]);
                        }

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | BELUM ADA RIWAYAT PADA TAHUN INI
                        |--------------------------------------------------------------------------
                        */

                        RiwayatKelasSiswa::create([
                            'siswa_id' =>
                                $siswa->id,

                            'kelas_id' =>
                                $kelas->id,

                            'tanggal_mulai' =>
                                now()->toDateString(),

                            'tanggal_selesai' =>
                                null,

                            'status' =>
                                'aktif',

                            'keterangan' =>
                                'Penempatan siswa',
                        ]);
                    }
                }
            }
        );

        return redirect()
            ->route('siswa.index')
            ->with(
                'success',
                'Data siswa berhasil diperbarui.'
            );
    }


    /**
     * =========================================================
     * HAPUS SISWA
     * =========================================================
     *
     * HAPUS MASTER SISWA.
     *
     * Jangan gunakan hapus untuk mengeluarkan siswa
     * dari satu tahun ajaran saja.
     *
     * Untuk perpindahan tahun/kelas gunakan
     * riwayat_kelas_siswa.
     *
     * =========================================================
     */
    public function destroy(
        Siswa $siswa
    ) {

        $siswa->delete();

        return redirect()
            ->route('siswa.index')
            ->with(
                'success',
                'Data siswa berhasil dihapus.'
            );
    }
}