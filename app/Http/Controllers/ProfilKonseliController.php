<?php

namespace App\Http\Controllers;

use App\Models\ProfilKonseli;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfilKonseliController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     *
     * Menampilkan Profil Konseli berdasarkan Tahun Ajaran
     * yang sedang dipilih pada Dashboard.
     *
     * Data tahun sebelumnya TIDAK dihapus.
     *
     * =========================================================
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN DARI SESSION
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session('tahun_ajaran_id');

        /*
        |--------------------------------------------------------------------------
        | FALLBACK TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        if (!$tahunAjaranId) {

            $tahunAjaranAktif = TahunAjaran::where('is_active', true)
                ->orderByDesc('tanggal_mulai')
                ->first();

            if (!$tahunAjaranAktif) {
                $tahunAjaranAktif = TahunAjaran::orderByDesc('tanggal_mulai')
                    ->first();
            }

            if ($tahunAjaranAktif) {
                $tahunAjaranId = $tahunAjaranAktif->id;

                session([
                    'tahun_ajaran_id' => $tahunAjaranId,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DATA TAHUN AJARAN TERPILIH
        |--------------------------------------------------------------------------
        */

        $tahunAjaran = null;

        if ($tahunAjaranId) {
            $tahunAjaran = TahunAjaran::find($tahunAjaranId);
        }

        /*
        |--------------------------------------------------------------------------
        | PROFIL KONSELI
        |--------------------------------------------------------------------------
        */

        $profilKonseli = ProfilKonseli::query()
            ->with([
                'siswa',
                'tahunAjaran',
            ])
            ->when(
                $tahunAjaranId,
                function ($query) use ($tahunAjaranId) {
                    $query->where(
                        'tahun_ajaran_id',
                        $tahunAjaranId
                    );
                }
            )
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'profil-konseli.index',
            compact(
                'profilKonseli',
                'tahunAjaran',
                'tahunAjaranId'
            )
        );
    }


    /**
     * =========================================================
     * CREATE
     * =========================================================
     *
     * Form tambah Profil Konseli.
     *
     * Hierarki:
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
     * =========================================================
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL TAHUN AJARAN DARI SESSION
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session('tahun_ajaran_id');

        /*
        |--------------------------------------------------------------------------
        | JIKA SESSION BELUM ADA
        |--------------------------------------------------------------------------
        */

        if (!$tahunAjaranId) {

            $tahunAjaranAktif = TahunAjaran::where(
                'is_active',
                true
            )
                ->orderByDesc('tanggal_mulai')
                ->first();

            if (!$tahunAjaranAktif) {
                $tahunAjaranAktif = TahunAjaran::orderByDesc(
                    'tanggal_mulai'
                )->first();
            }

            if ($tahunAjaranAktif) {

                $tahunAjaranId = $tahunAjaranAktif->id;

                session([
                    'tahun_ajaran_id' => $tahunAjaranId,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SEMUA TAHUN AJARAN
        |--------------------------------------------------------------------------
        |
        | PENTING:
        | Blade create menggunakan:
        |
        | @foreach ($tahunAjaran as $item)
        |
        | Jadi $tahunAjaran HARUS collection.
        |
        */

        $tahunAjaran = TahunAjaran::query()
            ->orderByDesc('tanggal_mulai')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | JURUSAN
        |--------------------------------------------------------------------------
        */

        $jurusans = Jurusan::query()
            ->where('is_active', true)
            ->orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        |
        | Hanya kelas pada tahun ajaran yang sedang dipilih.
        |
        */

        $kelasList = collect();

        if ($tahunAjaranId) {

            $kelasList = Kelas::query()
                ->with([
                    'jurusan',
                    'tahunAjaran',
                ])
                ->where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                ->where(
                    'is_active',
                    true
                )
                ->orderBy('tingkat')
                ->orderBy('nama_kelas')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        |
        | Siswa adalah master data.
        |
        | Siswa tidak mempunyai:
        |
        | - tahun_ajaran_id
        | - kelas_id
        |
        | Hubungannya:
        |
        | siswa
        |    ↓
        | riwayat_kelas_siswa
        |    ↓
        | kelas
        |    ↓
        | tahun_ajaran
        |
        */

        $siswa = collect();

        if ($tahunAjaranId) {

            $siswa = Siswa::query()
                ->with([
                    'riwayatKelas.kelas.jurusan',
                ])
                ->whereHas(
                    'riwayatKelas.kelas',
                    function ($query) use ($tahunAjaranId) {

                        $query->where(
                            'tahun_ajaran_id',
                            $tahunAjaranId
                        );
                    }
                )
                ->orderBy('nama_lengkap')
                ->get()
                ->map(function ($item) use ($tahunAjaranId) {

                    /*
                    |--------------------------------------------------------------------------
                    | CARI RIWAYAT KELAS PADA TAHUN AJARAN TERPILIH
                    |--------------------------------------------------------------------------
                    */

                    $riwayat = $item->riwayatKelas
                        ->first(
                            function ($riwayat) use ($tahunAjaranId) {

                                return $riwayat->kelas
                                    && (int) $riwayat->kelas->tahun_ajaran_id
                                    === (int) $tahunAjaranId;
                            }
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | TAMBAHKAN DATA UNTUK ALPINE.JS
                    |--------------------------------------------------------------------------
                    |
                    | create.blade.php membutuhkan:
                    |
                    | siswa.kelas_id
                    | siswa.tahun_ajaran_id
                    |
                    */

                    $item->kelas_id = $riwayat?->kelas_id;

                    $item->tahun_ajaran_id = $tahunAjaranId;

                    return $item;
                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'profil-konseli.create',
            compact(
                'tahunAjaran',
                'tahunAjaranId',
                'jurusans',
                'kelasList',
                'siswa'
            )
        );
    }


    /**
     * =========================================================
     * STORE
     * =========================================================
     *
     * Menyimpan Profil Konseli.
     *
     * Data tetap tersimpan berdasarkan tahun ajaran.
     *
     * =========================================================
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN DARI SESSION
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session('tahun_ajaran_id');

        if (!$tahunAjaranId) {

            return redirect()
                ->route('profil-konseli.index')
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
                ->route('profil-konseli.index')
                ->with(
                    'error',
                    'Tahun ajaran tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [

                'siswa_id' => [
                    'required',
                    'exists:siswa,id',

                    Rule::unique(
                        'profil_konseli',
                        'siswa_id'
                    )->where(
                        function ($query) use ($tahunAjaranId) {

                            return $query->where(
                                'tahun_ajaran_id',
                                $tahunAjaranId
                            );
                        }
                    ),
                ],

                'kondisi_pribadi' =>
                    'nullable|string',

                'kondisi_sosial' =>
                    'nullable|string',

                'kondisi_belajar' =>
                    'nullable|string',

                'kondisi_karir' =>
                    'nullable|string',

                'kondisi_keluarga' =>
                    'nullable|string',

                'catatan' =>
                    'nullable|string',
            ],
            [

                'siswa_id.required' =>
                    'Siswa wajib dipilih.',

                'siswa_id.exists' =>
                    'Siswa tidak ditemukan.',

                'siswa_id.unique' =>
                    'Siswa tersebut sudah memiliki profil konseli pada tahun ajaran ini.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | CEK SISWA BERASAL DARI TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $siswaValid = Siswa::query()
            ->where(
                'id',
                $validated['siswa_id']
            )
            ->whereHas(
                'riwayatKelas.kelas',
                function ($query) use ($tahunAjaranId) {

                    $query->where(
                        'tahun_ajaran_id',
                        $tahunAjaranId
                    );
                }
            )
            ->exists();

        if (!$siswaValid) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Siswa tidak terdaftar pada tahun ajaran yang sedang dipilih.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | TAMBAHKAN TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $validated['tahun_ajaran_id'] =
            $tahunAjaranId;

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        ProfilKonseli::create(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('profil-konseli.index')
            ->with(
                'success',
                'Profil konseli berhasil ditambahkan untuk tahun ajaran '
                . $tahunAjaran->nama
                . '.'
            );
    }


    /**
     * =========================================================
     * SHOW
     * =========================================================
     */
    public function show(
        ProfilKonseli $profilKonseli
    ) {

        /*
        |--------------------------------------------------------------------------
        | LOAD RELASI
        |--------------------------------------------------------------------------
        */

        $profilKonseli->load([
            'siswa',
            'tahunAjaran',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session(
            'tahun_ajaran_id'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK CONTEXT
        |--------------------------------------------------------------------------
        */

        if (
            $tahunAjaranId &&
            $profilKonseli->tahun_ajaran_id !=
                $tahunAjaranId
        ) {

            return redirect()
                ->route('profil-konseli.index')
                ->with(
                    'error',
                    'Profil konseli tersebut berada pada tahun ajaran lain.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'profil-konseli.show',
            compact(
                'profilKonseli'
            )
        );
    }


    /**
     * =========================================================
     * EDIT
     * =========================================================
     *
     * Profil tetap milik tahun ajaran ketika dibuat.
     *
     * =========================================================
     */
    public function edit(
        ProfilKonseli $profilKonseli
    ) {

        /*
        |--------------------------------------------------------------------------
        | LOAD PROFIL
        |--------------------------------------------------------------------------
        */

        $profilKonseli->load([
            'siswa',
            'tahunAjaran',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session(
            'tahun_ajaran_id'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK CONTEXT
        |--------------------------------------------------------------------------
        */

        if (
            $tahunAjaranId &&
            $profilKonseli->tahun_ajaran_id !=
                $tahunAjaranId
        ) {

            return redirect()
                ->route('profil-konseli.index')
                ->with(
                    'error',
                    'Profil konseli tersebut berada pada tahun ajaran '
                    . ($profilKonseli->tahunAjaran->nama ?? '-')
                    . '.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | SEMUA TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjaran = TahunAjaran::query()
            ->orderByDesc('tanggal_mulai')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | JURUSAN
        |--------------------------------------------------------------------------
        */

        $jurusans = Jurusan::query()
            ->where(
                'is_active',
                true
            )
            ->orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        */

        $kelasList = Kelas::query()
            ->with([
                'jurusan',
                'tahunAjaran',
            ])
            ->where(
                'tahun_ajaran_id',
                $profilKonseli->tahun_ajaran_id
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

        $siswa = Siswa::query()
            ->with([
                'riwayatKelas.kelas.jurusan',
            ])
            ->whereHas(
                'riwayatKelas.kelas',
                function ($query) use ($profilKonseli) {

                    $query->where(
                        'tahun_ajaran_id',
                        $profilKonseli->tahun_ajaran_id
                    );
                }
            )
            ->orderBy('nama_lengkap')
            ->get()
            ->map(
                function ($item) use ($profilKonseli) {

                    $riwayat = $item->riwayatKelas
                        ->first(
                            function ($riwayat) use ($profilKonseli) {

                                return $riwayat->kelas
                                    && (int) $riwayat->kelas->tahun_ajaran_id
                                    === (int) $profilKonseli->tahun_ajaran_id;
                            }
                        );

                    $item->kelas_id =
                        $riwayat?->kelas_id;

                    $item->tahun_ajaran_id =
                        $profilKonseli->tahun_ajaran_id;

                    return $item;
                }
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'profil-konseli.edit',
            compact(
                'profilKonseli',
                'tahunAjaran',
                'jurusans',
                'kelasList',
                'siswa'
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
        ProfilKonseli $profilKonseli
    ) {

        /*
        |--------------------------------------------------------------------------
        | TAHUN PROFIL
        |--------------------------------------------------------------------------
        |
        | JANGAN menggunakan tahun ajaran aktif untuk
        | mengganti kepemilikan data lama.
        |
        */

        $tahunAjaranId =
            $profilKonseli->tahun_ajaran_id;

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [

                'siswa_id' => [
                    'required',
                    'exists:siswa,id',

                    Rule::unique(
                        'profil_konseli',
                        'siswa_id'
                    )
                        ->ignore(
                            $profilKonseli->id
                        )
                        ->where(
                            function ($query) use ($tahunAjaranId) {

                                return $query->where(
                                    'tahun_ajaran_id',
                                    $tahunAjaranId
                                );
                            }
                        ),
                ],

                'kondisi_pribadi' =>
                    'nullable|string',

                'kondisi_sosial' =>
                    'nullable|string',

                'kondisi_belajar' =>
                    'nullable|string',

                'kondisi_karir' =>
                    'nullable|string',

                'kondisi_keluarga' =>
                    'nullable|string',

                'catatan' =>
                    'nullable|string',
            ],
            [

                'siswa_id.required' =>
                    'Siswa wajib dipilih.',

                'siswa_id.exists' =>
                    'Siswa tidak ditemukan.',

                'siswa_id.unique' =>
                    'Siswa tersebut sudah memiliki profil konseli pada tahun ajaran ini.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | CEK SISWA PADA TAHUN PROFIL
        |--------------------------------------------------------------------------
        */

        $siswaValid = Siswa::query()
            ->where(
                'id',
                $validated['siswa_id']
            )
            ->whereHas(
                'riwayatKelas.kelas',
                function ($query) use ($tahunAjaranId) {

                    $query->where(
                        'tahun_ajaran_id',
                        $tahunAjaranId
                    );
                }
            )
            ->exists();

        if (!$siswaValid) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Siswa tidak terdaftar pada tahun ajaran profil konseli tersebut.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN TIDAK BOLEH BERUBAH
        |--------------------------------------------------------------------------
        */

        $validated['tahun_ajaran_id'] =
            $tahunAjaranId;

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $profilKonseli->update(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('profil-konseli.index')
            ->with(
                'success',
                'Profil konseli berhasil diperbarui.'
            );
    }


    /**
     * =========================================================
     * DESTROY
     * =========================================================
     */
    public function destroy(
        ProfilKonseli $profilKonseli
    ) {

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session(
            'tahun_ajaran_id'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK CONTEXT
        |--------------------------------------------------------------------------
        */

        if (
            $tahunAjaranId &&
            $profilKonseli->tahun_ajaran_id !=
                $tahunAjaranId
        ) {

            return redirect()
                ->route('profil-konseli.index')
                ->with(
                    'error',
                    'Profil konseli tersebut bukan bagian dari tahun ajaran yang sedang dipilih.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS
        |--------------------------------------------------------------------------
        */

        $profilKonseli->delete();

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('profil-konseli.index')
            ->with(
                'success',
                'Profil konseli berhasil dihapus.'
            );
    }


    /**
     * =========================================================
     * DOWNLOAD / CETAK PDF
     * =========================================================
     */
    public function downloadPdf(
        ProfilKonseli $profilKonseli
    ) {

        /*
        |--------------------------------------------------------------------------
        | LOAD RELASI
        |--------------------------------------------------------------------------
        */

        $profilKonseli->load([
            'siswa',
            'tahunAjaran',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = session(
            'tahun_ajaran_id'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK CONTEXT
        |--------------------------------------------------------------------------
        */

        if (
            $tahunAjaranId &&
            $profilKonseli->tahun_ajaran_id !=
                $tahunAjaranId
        ) {

            return redirect()
                ->route('profil-konseli.index')
                ->with(
                    'error',
                    'Profil konseli tersebut berada pada tahun ajaran lain.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | GENERATE PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'profil-konseli.pdf',
            compact(
                'profilKonseli'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | NAMA SISWA
        |--------------------------------------------------------------------------
        */

        $namaSiswa =
            $profilKonseli
                ->siswa
                ->nama_lengkap
            ?? 'Siswa';

        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $namaFile =
            'Profil-Konseli-' .
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $namaSiswa
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