<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Data Siswa Baru BMW
    </title>

    <style>

        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header h2 {
            margin: 4px 0 0;
            font-size: 14px;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 10px;
            color: #475569;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h3 {
            margin: 0;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: top;
        }

        .label {
            width: 180px;
            background: #f1f5f9;
            font-weight: bold;
        }

        .section {
            margin-top: 18px;
        }

        .section-title {
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            font-weight: bold;
        }

        .section-content {
            border: 1px solid #cbd5e1;
            border-top: none;
            padding: 10px;
            min-height: 50px;
        }

        .empty {
            color: #64748b;
            font-style: italic;
        }

        .footer {
            margin-top: 35px;
        }

        .signature {
            width: 45%;
            text-align: center;
        }

        .signature-space {
            height: 60px;
        }

        .small {
            font-size: 9px;
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
            Dokumen Administrasi Bimbingan dan Konseling
        </p>

    </div>


    {{-- JUDUL --}}
    <div class="title">

        <h3>
            DATA SISWA BARU (BMW)
        </h3>

    </div>


    {{-- DATA UTAMA --}}
    <table class="data-table">

        <tr>

            <td class="label">
                Nama Siswa
            </td>

            <td>
                {{ $dataBMW->siswa->nama_lengkap ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                NIS
            </td>

            <td>
                {{ $dataBMW->siswa->nis ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                NISN
            </td>

            <td>
                {{ $dataBMW->siswa->nisn ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Tahun Ajaran
            </td>

            <td>
                {{ $dataBMW->tahunAjaran->nama ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Tanggal Pendataan
            </td>

            <td>

                @if($dataBMW->tanggal_pendataan)

                    {{ \Carbon\Carbon::parse($dataBMW->tanggal_pendataan)->format('d-m-Y') }}

                @else

                    -

                @endif

            </td>

        </tr>


        <tr>

            <td class="label">
                Asal Sekolah
            </td>

            <td>
                {{ $dataBMW->asal_sekolah ?? '-' }}
            </td>

        </tr>

    </table>


    {{-- DATA MASUK --}}
    <div class="section">

        <div class="section-title">
            Data Masuk
        </div>

        <div class="section-content">

            @if($dataBMW->data_masuk)

                {!! nl2br(e($dataBMW->data_masuk)) !!}

            @else

                <span class="empty">
                    Belum ada data.
                </span>

            @endif

        </div>

    </div>


    {{-- DATA ORANG TUA --}}
    <div class="section">

        <div class="section-title">
            Data Orang Tua
        </div>

        <div class="section-content">

            @if($dataBMW->data_orang_tua)

                {!! nl2br(e($dataBMW->data_orang_tua)) !!}

            @else

                <span class="empty">
                    Belum ada data.
                </span>

            @endif

        </div>

    </div>


    {{-- KETERANGAN --}}
    <div class="section">

        <div class="section-title">
            Keterangan
        </div>

        <div class="section-content">

            @if($dataBMW->keterangan)

                {!! nl2br(e($dataBMW->keterangan)) !!}

            @else

                <span class="empty">
                    Tidak ada keterangan.
                </span>

            @endif

        </div>

    </div>


    {{-- TANDA TANGAN --}}
    <div class="footer">

        <table>

            <tr>

                <td class="signature">

                    <p>
                        Mengetahui,
                    </p>

                    <p>
                        Guru BK
                    </p>

                    <div class="signature-space"></div>

                    <strong>
                        __________________________
                    </strong>

                    <br>

                    <span class="small">
                        Guru Bimbingan dan Konseling
                    </span>

                </td>


                <td class="signature">

                    <p>
                        Majalaya,
                        {{ date('d-m-Y') }}
                    </p>

                    <p>
                        Administrasi BK
                    </p>

                    <div class="signature-space"></div>

                    <strong>
                        __________________________
                    </strong>

                    <br>

                    <span class="small">
                        Administrasi BK
                    </span>

                </td>

            </tr>

        </table>

    </div>


</body>

</html>