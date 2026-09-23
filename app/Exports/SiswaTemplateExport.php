<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class SiswaTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            [
                'NIS',
                'NISN',
                'Nama Lengkap',
                'Jenis Kelamin',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Alamat',
                'No HP',
                'Tahun Masuk',
                'Status',
                'Tingkat',
                'Jurusan',
                'Kelas',
            ],
        ];
    }
}