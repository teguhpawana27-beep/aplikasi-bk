<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errors = [];
        $dataSiswa = [];

        /*
        |--------------------------------------------------------------------------
        | AMBIL TAHUN AJARAN YANG SEDANG DIPILIH
        |--------------------------------------------------------------------------
        |
        | Tahun ajaran diambil dari session.
        | Session ini diatur oleh TahunAjaranContext.
        |
        */

        $tahunAjaranId = session('tahun_ajaran_id');

        if (!$tahunAjaranId) {
            session()->flash(
                'error',
                'Tahun ajaran belum dipilih. Silakan pilih tahun ajaran terlebih dahulu.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | LOOP DATA EXCEL
        |--------------------------------------------------------------------------
        */

        foreach ($rows as $index => $row) {

            // Nomor baris Excel = index + 2 karena baris pertama adalah header
            $rowNumber = $index + 2;

            /*
            |--------------------------------------------------------------------------
            | AMBIL DATA
            |--------------------------------------------------------------------------
            */

            $nis = trim((string) ($row['nis'] ?? ''));
            $nisn = trim((string) ($row['nisn'] ?? ''));
            $nama = trim((string) ($row['nama_lengkap'] ?? ''));

            $jenisKelamin = strtoupper(
                trim((string) ($row['jenis_kelamin'] ?? ''))
            );

            $tempatLahir = trim(
                (string) ($row['tempat_lahir'] ?? '')
            );

            $tanggalLahir = $row['tanggal_lahir'] ?? null;

            $alamat = trim(
                (string) ($row['alamat'] ?? '')
            );

            $noHp = trim(
                (string) ($row['no_hp'] ?? '')
            );

            $tahunMasuk = $row['tahun_masuk'] ?? null;

            $status = strtolower(
                trim((string) ($row['status'] ?? 'aktif'))
            );

            $tingkat = trim(
                (string) ($row['tingkat'] ?? '')
            );

            $kodeJurusan = strtoupper(
                trim((string) ($row['jurusan'] ?? ''))
            );

            $namaKelasExcel = trim(
                (string) ($row['kelas'] ?? '')
            );

            /*
            |--------------------------------------------------------------------------
            | LEWATI BARIS KOSONG
            |--------------------------------------------------------------------------
            */

            if (
                $nis === '' &&
                $nama === '' &&
                $tingkat === '' &&
                $kodeJurusan === '' &&
                $namaKelasExcel === ''
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | KONVERSI TANGGAL EXCEL
            |--------------------------------------------------------------------------
            */

            if ($tanggalLahir !== null && $tanggalLahir !== '') {

                try {

                    // Jika Excel memberikan angka serial tanggal
                    if (is_numeric($tanggalLahir)) {

                        $tanggalLahir = ExcelDate::excelToDateTimeObject(
                            (float) $tanggalLahir
                        )->format('Y-m-d');

                    } else {

                        // Jika berupa string tanggal
                        $timestamp = strtotime(
                            (string) $tanggalLahir
                        );

                        if ($timestamp !== false) {

                            $tanggalLahir = date(
                                'Y-m-d',
                                $timestamp
                            );

                        } else {

                            $tanggalLahir = null;
                        }
                    }

                } catch (\Throwable $e) {

                    $tanggalLahir = null;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDASI DATA
            |--------------------------------------------------------------------------
            */

            $validator = Validator::make(
                [
                    'nis' => $nis,

                    'nisn' => $nisn !== ''
                        ? $nisn
                        : null,

                    'nama_lengkap' => $nama,

                    'jenis_kelamin' => $jenisKelamin,

                    'tempat_lahir' => $tempatLahir !== ''
                        ? $tempatLahir
                        : null,

                    'tanggal_lahir' => $tanggalLahir,

                    'alamat' => $alamat !== ''
                        ? $alamat
                        : null,

                    'no_hp' => $noHp !== ''
                        ? $noHp
                        : null,

                    'tahun_masuk' => $tahunMasuk,

                    'status' => $status,

                    'tingkat' => $tingkat,

                    'jurusan' => $kodeJurusan,

                    'kelas' => $namaKelasExcel,
                ],
                [
                    'nis' => 'required|string|max:50',

                    'nisn' => 'nullable|string|max:50',

                    'nama_lengkap' => 'required|string|max:255',

                    'jenis_kelamin' => 'required|in:L,P',

                    'tempat_lahir' => 'nullable|string|max:100',

                    'tanggal_lahir' => 'nullable|date',

                    'alamat' => 'nullable|string',

                    'no_hp' => 'nullable|string|max:20',

                    'tahun_masuk' => 'required|integer|min:2000|max:2100',

                    'status' => 'required|in:aktif,lulus,pindah,keluar',

                    'tingkat' => 'required|in:10,11,12',

                    'jurusan' => 'required|string|max:20',

                    'kelas' => 'required|string|max:100',
                ]
            );

            if ($validator->fails()) {

                foreach ($validator->errors()->all() as $message) {

                    $errors[] =
                        "Baris Excel {$rowNumber}: {$message}";
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CEK DUPLIKAT DALAM FILE EXCEL
            |--------------------------------------------------------------------------
            |
            | NIS yang sama dalam satu file tetap tidak boleh.
            |
            */

            foreach ($dataSiswa as $existing) {

                if ($existing['nis'] === $nis) {

                    $errors[] =
                        "Baris Excel {$rowNumber}: NIS {$nis} duplikat dengan data lain di file Excel.";

                    continue 2;
                }

                if (
                    $nisn !== '' &&
                    !empty($existing['nisn']) &&
                    $existing['nisn'] === $nisn
                ) {

                    $errors[] =
                        "Baris Excel {$rowNumber}: NISN {$nisn} duplikat dengan data lain di file Excel.";

                    continue 2;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CARI JURUSAN
            |--------------------------------------------------------------------------
            */

            $jurusan = Jurusan::where(
                'kode',
                $kodeJurusan
            )->first();

            if (!$jurusan) {

                $errors[] =
                    "Baris Excel {$rowNumber}: Jurusan {$kodeJurusan} tidak ditemukan di database.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CARI KELAS
            |--------------------------------------------------------------------------
            |
            | PENTING:
            | Kelas HARUS berasal dari tahun ajaran yang sedang dipilih.
            |
            */

            preg_match(
                '/(\d+)$/',
                $namaKelasExcel,
                $match
            );

            $nomorKelas = $match[1] ?? null;

            $kelas = null;

            if ($nomorKelas) {

                $kelas = Kelas::where(
                    'tahun_ajaran_id',
                    $tahunAjaranId
                )
                    ->where(
                        'tingkat',
                        (int) $tingkat
                    )
                    ->where(
                        'jurusan_id',
                        $jurusan->id
                    )
                    ->where(function ($query) use ($nomorKelas) {

                        $query
                            ->where(
                                'nama_kelas',
                                'LIKE',
                                "% {$nomorKelas}"
                            )
                            ->orWhere(
                                'nama_kelas',
                                'LIKE',
                                "%{$nomorKelas}"
                            );
                    })
                    ->first();
            }

            if (!$kelas) {

                $errors[] =
                    "Baris Excel {$rowNumber}: Kelas {$namaKelasExcel} tidak ditemukan pada tahun ajaran yang sedang dipilih.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CEK APAKAH SISWA SUDAH ADA DI DATABASE
            |--------------------------------------------------------------------------
            |
            | SEKARANG:
            |
            | - Jika siswa belum ada -> buat siswa baru.
            | - Jika siswa sudah ada -> gunakan siswa lama.
            |
            | Jadi siswa boleh masuk ke tahun ajaran baru.
            |
            */

            $siswaLama = Siswa::where(
                'nis',
                $nis
            )->first();

            /*
            |--------------------------------------------------------------------------
            | JIKA NISN ADA, CARI BERDASARKAN NISN
            |--------------------------------------------------------------------------
            */

            if (!$siswaLama && $nisn !== '') {

                $siswaLama = Siswa::where(
                    'nisn',
                    $nisn
                )->first();
            }

            /*
            |--------------------------------------------------------------------------
            | CEK APAKAH SISWA SUDAH ADA DI TAHUN AJARAN INI
            |--------------------------------------------------------------------------
            |
            | Kita cek melalui:
            |
            | riwayat_kelas_siswa
            |       ↓
            | kelas
            |       ↓
            | tahun_ajaran_id
            |
            */

            if ($siswaLama) {

                $sudahAdaDiTahunIni = DB::table(
                    'riwayat_kelas_siswa'
                )
                    ->join(
                        'kelas',
                        'kelas.id',
                        '=',
                        'riwayat_kelas_siswa.kelas_id'
                    )
                    ->where(
                        'riwayat_kelas_siswa.siswa_id',
                        $siswaLama->id
                    )
                    ->where(
                        'kelas.tahun_ajaran_id',
                        $tahunAjaranId
                    )
                    ->exists();

                if ($sudahAdaDiTahunIni) {

                    $errors[] =
                        "Baris Excel {$rowNumber}: Siswa dengan NIS {$nis} sudah terdaftar pada tahun ajaran yang sedang dipilih.";

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN DATA SEMENTARA
            |--------------------------------------------------------------------------
            */

            $dataSiswa[] = [

                'siswa_id' => $siswaLama?->id,

                'nis' => $nis,

                'nisn' => $nisn !== ''
                    ? $nisn
                    : null,

                'nama_lengkap' => $nama,

                'jenis_kelamin' => $jenisKelamin,

                'tempat_lahir' => $tempatLahir !== ''
                    ? $tempatLahir
                    : null,

                'tanggal_lahir' => $tanggalLahir,

                'alamat' => $alamat !== ''
                    ? $alamat
                    : null,

                'no_hp' => $noHp !== ''
                    ? $noHp
                    : null,

                'tahun_masuk' => (int) $tahunMasuk,

                'status' => $status,

                'kelas_id' => $kelas->id,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | JIKA ADA ERROR
        |--------------------------------------------------------------------------
        |
        | Tidak ada data yang dimasukkan jika ada error.
        |
        */

        if (count($errors) > 0) {

            session()->flash(
                'error',
                'Import gagal: ' . implode(' ', $errors)
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($dataSiswa) {

            foreach ($dataSiswa as $data) {

                $kelasId = $data['kelas_id'];

                $siswaId = $data['siswa_id'];

                /*
                |--------------------------------------------------------------------------
                | JIKA SISWA BELUM ADA
                |--------------------------------------------------------------------------
                */

                if (!$siswaId) {

                    $siswa = Siswa::create([

                        'nis' => $data['nis'],

                        'nisn' => $data['nisn'],

                        'nama_lengkap' => $data['nama_lengkap'],

                        'jenis_kelamin' => $data['jenis_kelamin'],

                        'tempat_lahir' => $data['tempat_lahir'],

                        'tanggal_lahir' => $data['tanggal_lahir'],

                        'alamat' => $data['alamat'],

                        'no_hp' => $data['no_hp'],

                        'tahun_masuk' => $data['tahun_masuk'],

                        'status' => $data['status'],
                    ]);

                    $siswaId = $siswa->id;
                }

                /*
                |--------------------------------------------------------------------------
                | INSERT RIWAYAT KELAS
                |--------------------------------------------------------------------------
                |
                | Inilah yang membuat siswa masuk ke tahun ajaran baru
                | tanpa membuat data siswa baru.
                |
                */

                DB::table(
                    'riwayat_kelas_siswa'
                )->insert([

                    'siswa_id' => $siswaId,

                    'kelas_id' => $kelasId,

                    'tanggal_mulai' => now()->toDateString(),

                    'tanggal_selesai' => null,

                    'status' => 'aktif',

                    'keterangan' => 'Import Excel',

                    'created_at' => now(),

                    'updated_at' => now(),
                ]);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | PESAN BERHASIL
        |--------------------------------------------------------------------------
        */

        session()->flash(
            'success',
            count($dataSiswa) .
            ' data siswa berhasil diimport ke tahun ajaran yang sedang dipilih.'
        );
    }
}