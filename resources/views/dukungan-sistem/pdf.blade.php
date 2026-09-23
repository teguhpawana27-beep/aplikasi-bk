<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Dukungan Sistem
    </title>

    <style>

        @page {
            size: A4;
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 17px;
            font-weight: bold;
        }

        .header h2 {
            margin: 4px 0 0;
            font-size: 13px;
            font-weight: normal;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 10px;
            color: #64748b;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
        }

        table.info {
            width: 100%;
            border-collapse: collapse;
        }

        table.info td {
            padding: 6px 7px;
            vertical-align: top;
        }

        table.info td.label {
            width: 28%;
            font-weight: bold;
            color: #475569;
        }

        table.info td.separator {
            width: 2%;
            text-align: center;
        }

        .content-box {
            border: 1px solid #cbd5e1;
            padding: 10px;
            min-height: 45px;
            white-space: pre-line;
        }

        .signature {
            margin-top: 45px;
            width: 100%;
        }

        .signature td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 65px;
        }

        .footer {
            position: fixed;
            bottom: -5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #64748b;
        }

    </style>

</head>


<body>


    {{-- HEADER --}}

    <div class="header">

        <h1>
            SMK NEGERI 1 MAJALAYA
        </h1>

        <h2>
            BIMBINGAN DAN KONSELING
        </h2>

        <p>
            Laporan Dukungan Sistem
        </p>

    </div>



    {{-- DATA KEGIATAN --}}

    <div class="section">

        <div class="section-title">
            DATA KEGIATAN
        </div>


        <table class="info">

            <tr>

                <td class="label">
                    Jenis Kegiatan
                </td>

                <td class="separator">
                    :
                </td>

                <td>
                    {{ $dukunganSistem->jenis_kegiatan ?: '-' }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Tanggal
                </td>

                <td class="separator">
                    :
                </td>

                <td>

                    {{ $dukunganSistem->tanggal
                        ? $dukunganSistem->tanggal->format('d/m/Y')
                        : '-' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Guru BK
                </td>

                <td class="separator">
                    :
                </td>

                <td>
                    {{ $dukunganSistem->guruBK->nama_lengkap ?? '-' }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Tahun Ajaran
                </td>

                <td class="separator">
                    :
                </td>

                <td>
                    {{ $dukunganSistem->tahunAjaran->nama ?? '-' }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Siswa
                </td>

                <td class="separator">
                    :
                </td>

                <td>

                    @if($dukunganSistem->siswa)

                        {{ $dukunganSistem->siswa->nama_lengkap }}

                        @if($dukunganSistem->siswa->nis)
                            (NIS: {{ $dukunganSistem->siswa->nis }})
                        @endif

                    @else

                        Tidak terkait siswa tertentu

                    @endif

                </td>

            </tr>


            <tr>

                <td class="label">
                    Sasaran
                </td>

                <td class="separator">
                    :
                </td>

                <td>
                    {{ $dukunganSistem->sasaran ?: '-' }}
                </td>

            </tr>

        </table>

    </div>



    {{-- URAIAN --}}

    <div class="section">

        <div class="section-title">
            URAIAN KEGIATAN
        </div>

        <div class="content-box">

            {{ $dukunganSistem->uraian_kegiatan ?: '-' }}

        </div>

    </div>



    {{-- HASIL --}}

    <div class="section">

        <div class="section-title">
            HASIL
        </div>

        <div class="content-box">

            {{ $dukunganSistem->hasil ?: '-' }}

        </div>

    </div>



    {{-- EVALUASI --}}

    <div class="section">

        <div class="section-title">
            EVALUASI
        </div>

        <div class="content-box">

            {{ $dukunganSistem->evaluasi ?: '-' }}

        </div>

    </div>



    {{-- TINDAK LANJUT --}}

    <div class="section">

        <div class="section-title">
            TINDAK LANJUT
        </div>

        <div class="content-box">

            {{ $dukunganSistem->tindak_lanjut ?: '-' }}

        </div>

    </div>



    {{-- KETERANGAN --}}

    <div class="section">

        <div class="section-title">
            KETERANGAN
        </div>

        <div class="content-box">

            {{ $dukunganSistem->keterangan ?: '-' }}

        </div>

    </div>



    {{-- TANDA TANGAN --}}

    <table class="signature">

        <tr>

            <td>

                Mengetahui,<br>
                Kepala Sekolah

                <div class="signature-space"></div>

                <strong>
                    __________________________
                </strong>

            </td>


            <td>

                Guru BK,<br>

                <div class="signature-space"></div>

                <strong>
                    {{ $dukunganSistem->guruBK->nama_lengkap ?? '__________________________' }}
                </strong>

            </td>

        </tr>

    </table>



    {{-- FOOTER --}}

    <div class="footer">

        Sistem Informasi Bimbingan dan Konseling
        — Dukungan Sistem

    </div>


</body>

</html>