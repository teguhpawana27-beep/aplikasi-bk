<?php

namespace App\Http\Controllers;

use App\Models\DataBMW;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DataBMWController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAHUN AJARAN CONTEXT
    |--------------------------------------------------------------------------
    |
    | Mengambil Tahun Ajaran yang sedang dipilih dari Dashboard.
    |
    | Jika session belum tersedia, gunakan Tahun Ajaran aktif.
    |
    */

    private function getTahunAjaranAktif()
    {
        $tahunAjaranId = session('tahun_ajaran_id');

        /*
        |--------------------------------------------------------------------------
        | SESSION BELUM ADA
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
            | Jika tidak ada yang aktif, gunakan tahun terbaru
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
        | AMBIL TAHUN BERDASARKAN SESSION
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
    |
    | Hanya menampilkan Data BMW dari Tahun Ajaran yang sedang dipilih.
    |
    */

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN CONTEXT
        |--------------------------------------------------------------------------
        */

        $tahunAjaran = $this->getTahunAjaranAktif();

        $tahunAjaranId = $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | DATA BMW
        |--------------------------------------------------------------------------
        */

        $dataBMW = DataBMW::query()
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
            'data-bmw.index',
            compact(
                'dataBMW',
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
                ->route('data-bmw.index')
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
        | Tetap dikirim ke Blade untuk menjaga kompatibilitas
        | dengan tampilan yang sudah ada.
        |
        | Tetapi penyimpanan tetap mengikuti context Dashboard.
        |
        */

        $tahunAjaran = TahunAjaran::orderByDesc(
            'tanggal_mulai'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | JURUSAN
        |--------------------------------------------------------------------------
        |
        | Jurusan adalah master data sehingga tidak dibatasi
        | berdasarkan Tahun Ajaran.
        |
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
        | Kelas HARUS berasal dari Tahun Ajaran yang sedang dipilih.
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
        | Untuk menentukan siswa pada Tahun Ajaran tertentu,
        | digunakan:
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
                'siswa.*'
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
            ->with([
                'riwayatKelas' => function ($query) use (
                    $tahunAjaranId
                ) {

                    $query->whereHas(
                        'kelas',
                        function ($kelasQuery) use (
                            $tahunAjaranId
                        ) {

                            $kelasQuery->where(
                                'tahun_ajaran_id',
                                $tahunAjaranId
                            );
                        }
                    )
                    ->with([
                        'kelas.jurusan',
                    ]);
                },
            ])
            ->orderBy(
                'nama_lengkap'
            )
            ->get();

        // Tambahkan atribut kelas/tahun/jurusan sementara untuk kebutuhan form.
        $siswa->each(function ($item) {
            $riwayat = collect($item->riwayatKelas ?? [])
                ->filter(fn ($riwayat) => $riwayat->kelas)
                ->sortByDesc(fn ($riwayat) => $riwayat->tanggal_mulai)
                ->first();

            $item->kelas_id = $riwayat?->kelas_id;
            $item->tahun_ajaran_id = $riwayat?->kelas?->tahun_ajaran_id;
            $item->tingkat = $riwayat?->kelas?->tingkat;
            $item->jurusan_id = $riwayat?->kelas?->jurusan_id;
        });


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'data-bmw.create',
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
    | Tahun Ajaran TIDAK diambil dari input form.
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
                ->route('data-bmw.index')
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

                'asal_sekolah' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'data_masuk' => [
                    'nullable',
                    'string',
                ],

                'data_orang_tua' => [
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

        DataBMW::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('data-bmw.index')
            ->with(
                'success',
                'Data Siswa Baru berhasil ditambahkan pada Tahun Ajaran ' .
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
        | AMBIL DATA HANYA DARI TAHUN AKTIF
        |--------------------------------------------------------------------------
        */

        $dataBMW = DataBMW::query()
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
            'data-bmw.show',
            compact(
                'dataBMW'
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
                ->route('data-bmw.index')
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaranAktif->id;


        /*
        |--------------------------------------------------------------------------
        | DATA BMW
        |--------------------------------------------------------------------------
        |
        | Hanya boleh edit data dari Tahun Ajaran yang sedang dipilih.
        |
        */

        $dataBMW = DataBMW::query()
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
        |
        | Hanya kelas Tahun Ajaran sekarang.
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
        */

        $siswa = Siswa::query()
            ->select(
                'siswa.*'
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
            ->with([
                'riwayatKelas' => function ($query) use (
                    $tahunAjaranId
                ) {

                    $query->whereHas(
                        'kelas',
                        function ($kelasQuery) use (
                            $tahunAjaranId
                        ) {

                            $kelasQuery->where(
                                'tahun_ajaran_id',
                                $tahunAjaranId
                            );
                        }
                    )
                    ->with([
                        'kelas.jurusan',
                    ]);
                },
            ])
            ->orderBy(
                'nama_lengkap'
            )
            ->get();

        // Tambahkan atribut kelas/tahun/jurusan sementara untuk kebutuhan form.
        $siswa->each(function ($item) {
            $riwayat = collect($item->riwayatKelas ?? [])
                ->filter(fn ($riwayat) => $riwayat->kelas)
                ->sortByDesc(fn ($riwayat) => $riwayat->tanggal_mulai)
                ->first();

            $item->kelas_id = $riwayat?->kelas_id;
            $item->tahun_ajaran_id = $riwayat?->kelas?->tahun_ajaran_id;
            $item->tingkat = $riwayat?->kelas?->tingkat;
            $item->jurusan_id = $riwayat?->kelas?->jurusan_id;
        });


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'data-bmw.edit',
            compact(
                'dataBMW',
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
    | Tahun Ajaran tidak boleh diganti melalui form.
    | Data tetap berada pada Tahun Ajaran asal/context.
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
                ->route('data-bmw.index')
                ->with(
                    'error',
                    'Belum ada Tahun Ajaran yang tersedia.'
                );
        }

        $tahunAjaranId =
            $tahunAjaran->id;


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $dataBMW = DataBMW::query()
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

                'asal_sekolah' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'data_masuk' => [
                    'nullable',
                    'string',
                ],

                'data_orang_tua' => [
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
        | JANGAN UBAH TAHUN AJARAN
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

        $dataBMW->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('data-bmw.index')
            ->with(
                'success',
                'Data Siswa Baru berhasil diperbarui.'
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
        | TAHUN AJARAN CONTEXT
        |--------------------------------------------------------------------------
        */

        $tahunAjaran =
            $this->getTahunAjaranAktif();

        $tahunAjaranId =
            $tahunAjaran?->id;


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $dataBMW = DataBMW::query()
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

        $dataBMW->delete();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('data-bmw.index')
            ->with(
                'success',
                'Data Siswa Baru berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    |
    | PDF hanya bisa dibuat untuk data dari Tahun Ajaran yang sedang dipilih.
    |
    */

    public function downloadPdf($id)
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
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $dataBMW = DataBMW::query()
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
        | LOAD PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'data-bmw.pdf',
            compact(
                'dataBMW'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | NAMA SISWA
        |--------------------------------------------------------------------------
        */

        $namaSiswa =
            $dataBMW->siswa->nama_lengkap
            ?? 'Siswa';


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $namaFile =
            'Data-BMW-' .
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $namaSiswa
            ) .
            '-' .
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $dataBMW->tahunAjaran->nama ?? 'Tahun-Ajaran'
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