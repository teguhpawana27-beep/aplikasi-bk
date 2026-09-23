<?php

namespace App\Http\Controllers;

use App\Models\DataSNPMB;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DataSnpmBController extends Controller
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
        | Jika session belum ada
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
            | Jika tidak ada tahun aktif
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
        | Ambil berdasarkan session
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
    | Hanya menampilkan data SNPMB pada Tahun Ajaran yang sedang dipilih.
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
        | DATA SNPMB
        |--------------------------------------------------------------------------
        */

        $dataSNPMB = DataSNPMB::query()
            ->with([
                'siswa',
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
            ->latest('tanggal_pendataan')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'data-snpmb.index',
            compact(
                'dataSNPMB',
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
                ->route('data-snpmb.index')
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaranAktif->id;


        /*
        |--------------------------------------------------------------------------
        | DAFTAR TAHUN AJARAN
        |--------------------------------------------------------------------------
        |
        | Tetap dikirim ke Blade jika tampilan membutuhkan.
        |
        */

        $tahunAjaran = TahunAjaran::orderByDesc(
            'tanggal_mulai'
        )->get();


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
        |
        | Hanya kelas dari Tahun Ajaran yang sedang dipilih.
        |
        */

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


        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        |
        | Siswa merupakan master data.
        |
        | Untuk mendapatkan siswa pada Tahun Ajaran tertentu:
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

        $siswa = Siswa::query()
            ->select(
                'siswa.*',
                'riwayat_kelas_siswa.kelas_id',
                'kelas.tahun_ajaran_id',
                'kelas.tingkat',
                'kelas.jurusan_id'
            )
            ->join(
                'riwayat_kelas_siswa',
                'riwayat_kelas_siswa.siswa_id',
                '=',
                'siswa.id'
            )
            ->join(
                'kelas',
                'kelas.id',
                '=',
                'riwayat_kelas_siswa.kelas_id'
            )
            ->where(
                'riwayat_kelas_siswa.status',
                'aktif'
            )
            ->where(
                'kelas.tahun_ajaran_id',
                $tahunAjaranId
            )
            ->where(
                'kelas.is_active',
                true
            )
            ->orderBy(
                'siswa.nama_lengkap'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'data-snpmb.create',
            compact(
                'siswa',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'jurusans',
                'kelasList'
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
        | TAHUN AJARAN CONTEXT
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {

            return redirect()
                ->route('data-snpmb.index')
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

                'tanggal_pendataan' => [
                    'required',
                    'date',
                ],

                'jalur' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'perguruan_tinggi' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'program_studi' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'status_pendaftaran' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'hasil' => [
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
                    'Silakan pilih siswa.',

                'siswa_id.exists' =>
                    'Siswa yang dipilih tidak ditemukan.',

                'tanggal_pendataan.required' =>
                    'Tanggal pendataan wajib diisi.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN SISWA TERDAFTAR PADA TAHUN INI
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

        DataSNPMB::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('data-snpmb.index')
            ->with(
                'success',
                'Data SNPMB berhasil ditambahkan pada Tahun Ajaran ' .
                $tahunAjaran->nama .
                '.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN CONTEXT
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

        $dataSNPMB = DataSNPMB::query()
            ->with([
                'siswa',
                'tahunAjaran',
            ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'data-snpmb.show',
            compact(
                'dataSNPMB'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
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
                ->route('data-snpmb.index')
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaranAktif->id;


        /*
        |--------------------------------------------------------------------------
        | DATA SNPMB
        |--------------------------------------------------------------------------
        |
        | Tidak boleh mengedit data dari tahun lain.
        |
        */

        $dataSNPMB = DataSNPMB::query()
            ->with([
                'siswa',
                'tahunAjaran',
            ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->findOrFail($id);


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
                $tahunAjaranId
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
        |
        | Data siswa diambil dari riwayat kelas pada Tahun Ajaran aktif.
        | Field kelas_id, tahun_ajaran_id, tingkat, dan jurusan_id
        | ikut dikirim langsung ke Blade agar filter:
        | Tahun Ajaran -> Tingkat -> Jurusan -> Kelas -> Siswa
        | dapat bekerja tanpa bergantung pada relasi nested di JavaScript.
        |
        */

        $siswa = Siswa::query()
            ->select(
                'siswa.*',
                'riwayat_kelas_siswa.kelas_id',
                'kelas.tahun_ajaran_id',
                'kelas.tingkat',
                'kelas.jurusan_id'
            )
            ->join(
                'riwayat_kelas_siswa',
                'riwayat_kelas_siswa.siswa_id',
                '=',
                'siswa.id'
            )
            ->join(
                'kelas',
                'kelas.id',
                '=',
                'riwayat_kelas_siswa.kelas_id'
            )
            ->where(
                'riwayat_kelas_siswa.status',
                'aktif'
            )
            ->where(
                'kelas.tahun_ajaran_id',
                $tahunAjaranId
            )
            ->where(
                'kelas.is_active',
                true
            )
            ->orderBy(
                'siswa.nama_lengkap'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'data-snpmb.edit',
            compact(
                'dataSNPMB',
                'siswa',
                'tahunAjaran',
                'tahunAjaranAktif',
                'tahunAjaranId',
                'jurusans',
                'kelasList'
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
        $id
    ) {

        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN CONTEXT
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        if (!$tahunAjaran) {

            return redirect()
                ->route('data-snpmb.index')
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaran->id;


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA DARI TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $dataSNPMB = DataSNPMB::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->findOrFail($id);


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

                'tanggal_pendataan' => [
                    'required',
                    'date',
                ],

                'jalur' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'perguruan_tinggi' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'program_studi' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'status_pendaftaran' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'hasil' => [
                    'nullable',
                    'string',
                ],

                'keterangan' => [
                    'nullable',
                    'string',
                ],
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN SISWA ADA DI TAHUN INI
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

        $dataSNPMB->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('data-snpmb.index')
            ->with(
                'success',
                'Data SNPMB berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    |
    | Hanya data dari Tahun Ajaran yang sedang dipilih yang dapat dihapus.
    |
    */

    public function destroy($id)
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
        | DATA
        |--------------------------------------------------------------------------
        */

        $dataSNPMB = DataSNPMB::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | HAPUS
        |--------------------------------------------------------------------------
        */

        $dataSNPMB->delete();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('data-snpmb.index')
            ->with(
                'success',
                'Data SNPMB berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    |
    | PDF hanya dapat dibuat dari data Tahun Ajaran yang sedang dipilih.
    |
    */

    public function downloadPdf($id)
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
        | DATA
        |--------------------------------------------------------------------------
        */

        $dataSNPMB = DataSNPMB::query()
            ->with([
                'siswa',
                'tahunAjaran',
            ])
            ->where(
                'tahun_ajaran_id',
                $tahunAjaranId
            )
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'data-snpmb.pdf',
            compact(
                'dataSNPMB'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | NAMA SISWA
        |--------------------------------------------------------------------------
        */

        $namaSiswa =
            $dataSNPMB->siswa->nama_lengkap
            ?? 'Siswa';


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $namaFile =
            'Data-SNPMB-' .
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $namaSiswa
            ) .
            '-' .
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $dataSNPMB->tahunAjaran->nama
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