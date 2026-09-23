<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Data SNPMB
    </title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 35px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }

        .header h2 {
            font-size: 15px;
            margin: 5px 0;
            font-weight: bold;
        }

        .header p {
            margin: 3px 0;
            font-size: 10px;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .section-title {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 9px 10px;
            font-weight: bold;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            border: 1px solid #d1d5db;
            padding: 9px;
            vertical-align: top;
        }

        .label {
            width: 30%;
            background: #f9fafb;
            font-weight: bold;
        }

        .footer {
            margin-top: 45px;
        }

        .signature {
            width: 100%;
            text-align: right;
        }

        .signature-space {
            height: 70px;
        }

    </style>

</head>

<body>


    {{-- HEADER SEKOLAH --}}

    <div class="header">

        <h1>
            PEMERINTAH PROVINSI JAWA BARAT
        </h1>

        <h2>
            SMK NEGERI 1 MAJALAYA
        </h2>

        <p>
            Data Siswa SNPMB
        </p>

        <p>
            Dokumentasi Bimbingan dan Konseling
        </p>

    </div>


    {{-- JUDUL --}}

    <div class="title">
        DATA SISWA SNPMB
    </div>


    {{-- IDENTITAS SISWA --}}

    <div class="section-title">
        IDENTITAS SISWA
    </div>

    <table class="data-table">

        <tr>

            <td class="label">
                Nama Lengkap
            </td>

            <td>
                {{ $dataSNPMB->siswa->nama_lengkap ?? '-' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                NIS
            </td>

            <td>
                {{ $dataSNPMB->siswa->nis ?? '-' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                NISN
            </td>

            <td>
                {{ $dataSNPMB->siswa->nisn ?? '-' }}
            </td>

        </tr>

    </table>


    {{-- DATA SNPMB --}}

    <div class="section-title">
        DATA PENDAFTARAN SNPMB
    </div>

    <table class="data-table">

        <tr>

            <td class="label">
                Tahun Ajaran
            </td>

            <td>
                {{ $dataSNPMB->tahunAjaran->nama ?? '-' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Tanggal Pendataan
            </td>

            <td>
                {{ $dataSNPMB->tanggal_pendataan?->format('d-m-Y') ?? '-' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Jalur
            </td>

            <td>
                {{ $dataSNPMB->jalur ?? '-' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Perguruan Tinggi
            </td>

            <td>
                {{ $dataSNPMB->perguruan_tinggi ?? '-' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Program Studi
            </td>

            <td>
                {{ $dataSNPMB->program_studi ?? '-' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Status Pendaftaran
            </td>

            <td>
                {{ $dataSNPMB->status_pendaftaran ?? '-' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Hasil
            </td>

            <td>
                {!! nl2br(e($dataSNPMB->hasil ?? '-')) !!}
            </td>

        </tr>

        <tr>

            <td class="label">
                Keterangan
            </td>

            <td>
                {!! nl2br(e($dataSNPMB->keterangan ?? '-')) !!}
            </td>

        </tr>

    </table>


    {{-- FOOTER --}}

    <div class="footer">

        <table>

            <tr>

                <td style="width: 55%; border: none;"></td>

                <td
                    style="
                        width: 45%;
                        border: none;
                        text-align: center;
                    "
                >

                    Majalaya,
                    {{ now()->format('d-m-Y') }}

                    <br>

                    Guru Bimbingan dan Konseling

                    <div class="signature-space"></div>

                    <strong>
                        ______________________________
                    </strong>

                </td>

            </tr>

        </table>

    </div>


</body>

</html>