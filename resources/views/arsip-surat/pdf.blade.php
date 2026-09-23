<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Cetak Arsip Surat</title>

    <style>
        @page {
            margin: 25px 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #222;
            margin: 0;
            padding: 0;
        }

        /* =========================
           HEADER
        ========================= */
        .header {
            text-align: center;
            padding-bottom: 12px;
            border-bottom: 3px solid #222;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .header p {
            margin: 2px 0;
            font-size: 10px;
        }

        /* =========================
           TITLE
        ========================= */
        .title {
            margin-top: 22px;
            margin-bottom: 18px;
            text-align: center;
        }

        .title h3 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .title p {
            margin-top: 5px;
            font-size: 10px;
            color: #666;
        }

        /* =========================
           DATA TABLE
        ========================= */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table {
            width: 100%;
        }

        .data-table td {
            border: 1px solid #aaa;
            padding: 8px;
            vertical-align: top;
        }

        .label {
            width: 30%;
            font-weight: bold;
            background: #f3f4f6;
        }

        /* =========================
           SECTION
        ========================= */
        .section-title {
            margin-top: 20px;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: bold;
        }

        .content-box {
            min-height: 70px;
            border: 1px solid #aaa;
            padding: 10px;
            line-height: 1.6;
        }

        /* =========================
           FILE
        ========================= */
        .file-available {
            font-weight: bold;
        }

        .file-not-available {
            color: #666;
        }

        /* =========================
           FOOTER
        ========================= */
        .footer {
            margin-top: 25px;
            font-size: 9px;
            color: #666;
        }

        /* =========================
           SIGNATURE
        ========================= */
        .signature {
            width: 220px;
            margin-top: 45px;
            margin-left: auto;
            text-align: center;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
        }

        /* =========================
           PRINT INFO
        ========================= */
        .print-info {
            margin-top: 8px;
            font-size: 9px;
            color: #777;
            text-align: right;
        }
    </style>
</head>

<body>

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="header">

        <h1>
            SMKN 1 MAJALAYA
        </h1>

        <h2>
            BIMBINGAN DAN KONSELING
        </h2>

        <p>
            Sistem Informasi Bimbingan dan Konseling
        </p>

    </div>


    {{-- =====================================================
         TITLE
    ====================================================== --}}
    <div class="title">

        <h3>
            ARSIP SURAT
        </h3>

        <p>
            Detail Dokumen Surat
        </p>

    </div>


    {{-- =====================================================
         DATA SURAT
    ====================================================== --}}
    <table class="data-table">

        {{-- Jenis Surat --}}
        <tr>
            <td class="label">
                Jenis Surat
            </td>

            <td>
                {{ $arsipSurat->jenis_surat ?: '-' }}
            </td>
        </tr>


        {{-- Nomor Surat --}}
        <tr>
            <td class="label">
                Nomor Surat
            </td>

            <td>
                {{ $arsipSurat->nomor_surat ?: '-' }}
            </td>
        </tr>


        {{-- Tanggal Surat --}}
        <tr>
            <td class="label">
                Tanggal Surat
            </td>

            <td>

                @if ($arsipSurat->tanggal_surat)

                    {{ \Carbon\Carbon::parse($arsipSurat->tanggal_surat)->format('d-m-Y') }}

                @else

                    -

                @endif

            </td>
        </tr>


        {{-- Nama Siswa --}}
        <tr>
            <td class="label">
                Nama Siswa
            </td>

            <td>

                @if ($arsipSurat->siswa)
                    {{ $arsipSurat->siswa->nama_lengkap }}
                @else
                    -
                @endif

            </td>
        </tr>


        {{-- NIS --}}
        <tr>
            <td class="label">
                NIS
            </td>

            <td>

                @if ($arsipSurat->siswa)
                    {{ $arsipSurat->siswa->nis }}
                @else
                    -
                @endif

            </td>
        </tr>


        {{-- Guru BK --}}
        <tr>
            <td class="label">
                Guru BK
            </td>

            <td>

                @if ($arsipSurat->guruBK)
                    {{ $arsipSurat->guruBK->nama_lengkap }}
                @else
                    -
                @endif

            </td>
        </tr>


        {{-- Tahun Ajaran --}}
        <tr>
            <td class="label">
                Tahun Ajaran
            </td>

            <td>

                @if ($arsipSurat->tahunAjaran)

                    {{-- 
                        Sesuaikan dengan field yang digunakan
                        oleh tabel tahun_ajaran.
                    --}}

                    @if (isset($arsipSurat->tahunAjaran->nama))
                        {{ $arsipSurat->tahunAjaran->nama }}

                    @elseif (isset($arsipSurat->tahunAjaran->tahun))
                        {{ $arsipSurat->tahunAjaran->tahun }}

                    @elseif (isset($arsipSurat->tahunAjaran->tahun_ajaran))
                        {{ $arsipSurat->tahunAjaran->tahun_ajaran }}

                    @else
                        -
                    @endif

                @else

                    -

                @endif

            </td>
        </tr>


        {{-- Perihal --}}
        <tr>
            <td class="label">
                Perihal
            </td>

            <td>
                {{ $arsipSurat->perihal ?: '-' }}
            </td>
        </tr>


        {{-- File Surat --}}
        <tr>
            <td class="label">
                File Surat
            </td>

            <td>

                @if ($arsipSurat->file_path)

                    <span class="file-available">
                        Tersedia
                    </span>

                @else

                    <span class="file-not-available">
                        Tidak tersedia
                    </span>

                @endif

            </td>
        </tr>


        {{-- Keterangan --}}
        <tr>
            <td class="label">
                Keterangan
            </td>

            <td>
                {{ $arsipSurat->keterangan ?: '-' }}
            </td>
        </tr>

    </table>


    {{-- =====================================================
         ISI RINGKAS
    ====================================================== --}}
    <div class="section-title">
        Isi Ringkas
    </div>

    <div class="content-box">

        @if ($arsipSurat->isi_ringkas)

            {!! nl2br(e($arsipSurat->isi_ringkas)) !!}

        @else

            -

        @endif

    </div>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}
    <div class="footer">

        Dokumen ini dicetak dari Sistem Informasi
        Bimbingan dan Konseling SMKN 1 Majalaya.

    </div>


    {{-- =====================================================
         TANGGAL CETAK
    ====================================================== --}}
    <div class="print-info">

        Dicetak pada:
        {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}

    </div>


    {{-- =====================================================
         TANDA TANGAN
    ====================================================== --}}
    <div class="signature">

        <div>
            Majalaya,
            {{ \Carbon\Carbon::now()->format('d F Y') }}
        </div>

        <div style="margin-top: 5px;">
            Guru Bimbingan dan Konseling
        </div>

        <div class="signature-space"></div>

        <div class="signature-name">

            @if ($arsipSurat->guruBK)
                {{ $arsipSurat->guruBK->nama_lengkap }}
            @else
                -
            @endif

        </div>

    </div>


</body>

</html>