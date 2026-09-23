<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Layanan Dasar
    </title>

    <style>

        @page {
            margin: 30px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.5;
        }


        .header {
            text-align: center;
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }


        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }


        .header h2 {
            margin: 3px 0;
            font-size: 14px;
        }


        .header p {
            margin: 2px 0;
            font-size: 10px;
        }


        .title {
            text-align: center;
            margin-bottom: 20px;
        }


        .title h3 {
            margin: 0;
            font-size: 14px;
        }


        .title p {
            margin: 4px 0 0;
            font-size: 10px;
        }


        .section {
            margin-top: 16px;
        }


        .section-title {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 7px 9px;
            font-size: 11px;
            font-weight: bold;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }


        .data-table td {
            border: 1px solid #d1d5db;
            padding: 7px 8px;
            vertical-align: top;
        }


        .data-table .label {
            width: 27%;
            background: #f8fafc;
            font-weight: bold;
        }


        .peserta th,
        .peserta td {
            border: 1px solid #d1d5db;
            padding: 6px 7px;
            vertical-align: top;
        }


        .peserta th {
            background: #f1f5f9;
            font-weight: bold;
            text-align: left;
        }


        .text-box {
            border: 1px solid #d1d5db;
            padding: 9px;
            min-height: 45px;
            white-space: pre-line;
        }


        .signature {
            margin-top: 35px;
        }


        .signature td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }


        .signature-space {
            height: 60px;
        }


        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }

    </style>

</head>


<body>


{{-- ========================================================= --}}
{{-- HEADER SEKOLAH --}}
{{-- ========================================================= --}}

<div class="header">

    <h1>
        PEMERINTAH PROVINSI JAWA BARAT
    </h1>

    <h2>
        SMKN 1 MAJALAYA
    </h2>

    <p>
        SISTEM INFORMASI BIMBINGAN & KONSELING
    </p>

</div>


{{-- ========================================================= --}}
{{-- JUDUL --}}
{{-- ========================================================= --}}

<div class="title">

    <h3>
        LAPORAN LAYANAN DASAR
    </h3>

    <p>
        {{ $layananDasar->jenis_layanan ?? '-' }}
    </p>

</div>


{{-- ========================================================= --}}
{{-- INFORMASI --}}
{{-- ========================================================= --}}

<div class="section">

    <div class="section-title">
        Informasi Layanan
    </div>


    <table class="data-table">

        <tr>

            <td class="label">
                Jenis Layanan
            </td>

            <td>
                {{ $layananDasar->jenis_layanan ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Tanggal
            </td>

            <td>

                @if ($layananDasar->tanggal)

                    {{ \Carbon\Carbon::parse($layananDasar->tanggal)->format('d-m-Y') }}

                @else

                    -

                @endif

            </td>

        </tr>


        <tr>

            <td class="label">
                Tahun Ajaran
            </td>

            <td>
                {{ $layananDasar->tahunAjaran->nama ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Guru BK
            </td>

            <td>
                {{ $layananDasar->guruBK->nama_lengkap ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Kelas
            </td>

            <td>

                @if ($layananDasar->kelas)

                    {{ $layananDasar->kelas->tingkat ?? '' }}

                    {{ $layananDasar->kelas->jurusan->kode ?? '' }}

                    -

                    {{ $layananDasar->kelas->nama_kelas ?? '' }}

                @else

                    Semua Kelas

                @endif

            </td>

        </tr>


        <tr>

            <td class="label">
                Topik
            </td>

            <td>
                {{ $layananDasar->topik ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Sasaran
            </td>

            <td>
                {{ $layananDasar->sasaran ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                SKKPD
            </td>

            <td>

                @if ($layananDasar->skkpd)

                    {{ $layananDasar->skkpd->kode }}
                    —
                    {{ $layananDasar->skkpd->nama }}

                @else

                    -

                @endif

            </td>

        </tr>


        <tr>

            <td class="label">
                Metode BK
            </td>

            <td>
                {{ $layananDasar->metode->nama ?? '-' }}
            </td>

        </tr>

    </table>

</div>


{{-- ========================================================= --}}
{{-- PESERTA --}}
{{-- ========================================================= --}}

<div class="section">

    <div class="section-title">
        Peserta Layanan
    </div>


    @if ($layananDasar->peserta->count() > 0)

        <table class="peserta">

            <thead>

                <tr>

                    <th style="width: 7%;">
                        No
                    </th>

                    <th style="width: 20%;">
                        NIS
                    </th>

                    <th style="width: 22%;">
                        NISN
                    </th>

                    <th>
                        Nama Siswa
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach ($layananDasar->peserta as $index => $peserta)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $peserta->siswa->nis ?? '-' }}
                        </td>

                        <td>
                            {{ $peserta->siswa->nisn ?? '-' }}
                        </td>

                        <td>
                            {{ $peserta->siswa->nama_lengkap ?? '-' }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="text-box">
            Tidak ada peserta siswa yang dipilih.
        </div>

    @endif

</div>


{{-- ========================================================= --}}
{{-- URAIAN --}}
{{-- ========================================================= --}}

<div class="section">

    <div class="section-title">
        Uraian Kegiatan
    </div>

    <div class="text-box">
        {{ $layananDasar->uraian_kegiatan ?? '-' }}
    </div>

</div>


{{-- ========================================================= --}}
{{-- HASIL --}}
{{-- ========================================================= --}}

<div class="section">

    <div class="section-title">
        Hasil
    </div>

    <div class="text-box">
        {{ $layananDasar->hasil ?? '-' }}
    </div>

</div>


{{-- ========================================================= --}}
{{-- EVALUASI --}}
{{-- ========================================================= --}}

<div class="section">

    <div class="section-title">
        Evaluasi
    </div>

    <div class="text-box">
        {{ $layananDasar->evaluasi ?? '-' }}
    </div>

</div>


{{-- ========================================================= --}}
{{-- KETERANGAN --}}
{{-- ========================================================= --}}

<div class="section">

    <div class="section-title">
        Keterangan
    </div>

    <div class="text-box">
        {{ $layananDasar->keterangan ?? '-' }}
    </div>

</div>


{{-- ========================================================= --}}
{{-- TANDA TANGAN --}}
{{-- ========================================================= --}}

<table class="signature">

    <tr>

        <td>

            Mengetahui,<br>
            Kepala Sekolah

            <div class="signature-space"></div>

            <strong>
                ______________________________
            </strong>

        </td>


        <td>

            Majalaya,
            {{ \Carbon\Carbon::now()->format('d-m-Y') }}

            <br>

            Guru BK

            <div class="signature-space"></div>

            <strong>
                {{ $layananDasar->guruBK->nama_lengkap ?? '______________________________' }}
            </strong>

        </td>

    </tr>

</table>


{{-- ========================================================= --}}
{{-- FOOTER --}}
{{-- ========================================================= --}}

<div class="footer">

    Dokumen ini dibuat melalui Sistem Informasi
    Bimbingan & Konseling SMKN 1 Majalaya.

</div>


</body>

</html>