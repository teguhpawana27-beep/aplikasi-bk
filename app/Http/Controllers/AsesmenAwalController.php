<?php

namespace App\Http\Controllers;

use App\Models\AsesmenAwal;
use App\Models\GuruBK;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AsesmenAwalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAHUN AJARAN CONTEXT
    |--------------------------------------------------------------------------
    |
    | Mengambil Tahun Ajaran yang sedang dipilih dari Dashboard.
    |
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

            /*
            |--------------------------------------------------------------------------
            | Jika tidak ada Tahun Ajaran aktif
            |--------------------------------------------------------------------------
            */

            if (!$tahunAjaran) {

                $tahunAjaran = TahunAjaran::orderByDesc(
                    'tanggal_mulai'
                )->first();
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan ke session
            |--------------------------------------------------------------------------
            */

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
    |
    | Hanya menampilkan data Asesmen Awal dari Tahun Ajaran yang sedang
    | dipilih pada Dashboard.
    |
    */

    public function index()
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
        | DATA ASESMEN AWAL
        |--------------------------------------------------------------------------
        */

        $asesmenAwal = AsesmenAwal::query()
            ->with([
                'siswa',
                'guruBK',
                'tahunAjaran',
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
            ->latest('tanggal_asesmen')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'asesmen-awal.index',
            compact(
                'asesmenAwal',
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
        | TAHUN AJARAN CONTEXT
        |--------------------------------------------------------------------------
        */

        $tahunAjaranAktif =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {

            return redirect()
                ->route('asesmen-awal.index')
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
        | DAFTAR TAHUN AJARAN
        |--------------------------------------------------------------------------
        |
        | Tetap dikirim ke view untuk kompatibilitas dengan Blade lama.
        |
        */

        $tahunAjaran = TahunAjaran::orderByDesc(
            'tanggal_mulai'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        |
        | Hanya kelas dari Tahun Ajaran yang sedang dipilih.
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
        | SISWA BERDASARKAN TAHUN AJARAN
        |--------------------------------------------------------------------------
        |
        | siswa
        |   ↓
        | riwayat_kelas_siswa
        |   ↓
        | kelas
        |   ↓
        | tahun_ajaran
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
            ->where(function ($query) {

                $query->whereNull(
                    'riwayat_kelas_siswa.status'
                )
                ->orWhereIn(
                    'riwayat_kelas_siswa.status',
                    [
                        'aktif',
                        'Aktif',
                        'active',
                    ]
                );
            })
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
            ->orderBy(
                'siswa.nama_lengkap'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SEMUA SISWA
        |--------------------------------------------------------------------------
        |
        | Variabel tetap disediakan agar Blade lama tidak error.
        |
        | Isinya sekarang hanya siswa yang memiliki riwayat kelas
        | pada Tahun Ajaran aktif.
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
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'asesmen-awal.create',
            compact(
                'guruBK',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'kelasList',
                'siswaKelas',
                'semuaSiswa'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Tahun Ajaran otomatis mengikuti Dashboard.
    |
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
                ->route('asesmen-awal.index')
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

                'siswa_id' => [
                    'required',
                    'exists:siswa,id',
                ],

                'guru_bk_id' => [
                    'required',
                    'exists:guru_bk,id',
                ],

                'tanggal_asesmen' => [
                    'required',
                    'date',
                ],

                'instrumen' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'hasil' => [
                    'nullable',
                    'string',
                ],

                'rekomendasi' => [
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
            ],
            [

                'siswa_id.required' =>
                    'Siswa wajib dipilih.',

                'siswa_id.exists' =>
                    'Siswa tidak ditemukan.',

                'guru_bk_id.required' =>
                    'Guru BK wajib dipilih.',

                'guru_bk_id.exists' =>
                    'Guru BK tidak ditemukan.',

                'tanggal_asesmen.required' =>
                    'Tanggal asesmen wajib diisi.',

                'instrumen.required' =>
                    'Instrumen asesmen wajib diisi.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SISWA PADA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $siswaValid = Siswa::query()
            ->where(
                'id',
                $validated['siswa_id']
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
            ->exists();


        if (!$siswaValid) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Siswa tersebut tidak terdaftar pada Tahun Ajaran ' .
                    $tahunAjaran->nama .
                    '.'
                );
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
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        AsesmenAwal::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('asesmen-awal.index')
            ->with(
                'success',
                'Data hasil asesmen awal berhasil ditambahkan pada Tahun Ajaran ' .
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
        AsesmenAwal $asesmenAwal
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
        | BATASI DATA KE TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (
            !$tahunAjaranId ||
            $asesmenAwal->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | RELASI
        |--------------------------------------------------------------------------
        */

        $asesmenAwal->load([
            'siswa',
            'guruBK',
            'tahunAjaran',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'asesmen-awal.show',
            compact(
                'asesmenAwal'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        AsesmenAwal $asesmenAwal
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
                ->route('asesmen-awal.index')
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaranAktif->id;


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN DATA BERASAL DARI TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (
            $asesmenAwal->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


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
            ->where(function ($query) {

                $query->whereNull(
                    'riwayat_kelas_siswa.status'
                )
                ->orWhereIn(
                    'riwayat_kelas_siswa.status',
                    [
                        'aktif',
                        'Aktif',
                        'active',
                    ]
                );
            })
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
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'asesmen-awal.edit',
            compact(
                'asesmenAwal',
                'guruBK',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'kelasList',
                'siswaKelas',
                'semuaSiswa'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Tahun Ajaran tidak dapat dipindahkan melalui form.
    |
    */

    public function update(
        Request $request,
        AsesmenAwal $asesmenAwal
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
                ->route('asesmen-awal.index')
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
            $asesmenAwal->tahun_ajaran_id !=
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

                'siswa_id' => [
                    'required',
                    'exists:siswa,id',
                ],

                'guru_bk_id' => [
                    'required',
                    'exists:guru_bk,id',
                ],

                'tanggal_asesmen' => [
                    'required',
                    'date',
                ],

                'instrumen' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'hasil' => [
                    'nullable',
                    'string',
                ],

                'rekomendasi' => [
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
            ],
            [

                'siswa_id.required' =>
                    'Siswa wajib dipilih.',

                'siswa_id.exists' =>
                    'Siswa tidak ditemukan.',

                'guru_bk_id.required' =>
                    'Guru BK wajib dipilih.',

                'guru_bk_id.exists' =>
                    'Guru BK tidak ditemukan.',

                'tanggal_asesmen.required' =>
                    'Tanggal asesmen wajib diisi.',

                'instrumen.required' =>
                    'Instrumen asesmen wajib diisi.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SISWA
        |--------------------------------------------------------------------------
        */

        $siswaValid = Siswa::query()
            ->where(
                'id',
                $validated['siswa_id']
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
            ->exists();


        if (!$siswaValid) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Siswa tersebut tidak terdaftar pada Tahun Ajaran ' .
                    $tahunAjaran->nama .
                    '.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | JANGAN IZINKAN TAHUN AJARAN DIUBAH
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['tahun_ajaran_id']
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $asesmenAwal->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('asesmen-awal.index')
            ->with(
                'success',
                'Data hasil asesmen awal berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        AsesmenAwal $asesmenAwal
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
        | HANYA BOLEH HAPUS DATA TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (
            !$tahunAjaranId ||
            $asesmenAwal->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS
        |--------------------------------------------------------------------------
        */

        $asesmenAwal->delete();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('asesmen-awal.index')
            ->with(
                'success',
                'Data hasil asesmen awal berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CETAK PDF
    |--------------------------------------------------------------------------
    */

    public function downloadPdf(
        AsesmenAwal $asesmenAwal
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
            $asesmenAwal->tahun_ajaran_id !=
            $tahunAjaranId
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD RELASI
        |--------------------------------------------------------------------------
        */

        $asesmenAwal->load([
            'siswa',
            'guruBK',
            'tahunAjaran',
        ]);


        /*
        |--------------------------------------------------------------------------
        | GENERATE PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'asesmen-awal.pdf',
            compact(
                'asesmenAwal'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | NAMA SISWA
        |--------------------------------------------------------------------------
        */

        $namaSiswa =
            $asesmenAwal->siswa->nama_lengkap
            ?? 'Siswa';


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $namaFile =
            'Asesmen-Awal-' .
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $namaSiswa
            ) .
            '-' .
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $asesmenAwal->tahunAjaran->nama
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