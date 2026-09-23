<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Layanan Responsif
    </title>

    <style>

        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 4px 0 0 0;
            font-size: 14px;
            font-weight: bold;
        }

        .header p {
            margin: 4px 0 0 0;
            font-size: 10px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h3 {
            margin: 0;
            font-size: 15px;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .info-table td {
            padding: 5px 4px;
            vertical-align: top;
        }

        .info-label {
            width: 145px;
            font-weight: bold;
        }

        .info-separator {
            width: 10px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background: #f1f5f9;
            border: 1px solid #94a3b8;
            padding: 7px;
            text-align: left;
            font-size: 10px;
        }

        .data-table td {
            border: 1px solid #94a3b8;
            padding: 7px;
            vertical-align: top;
            font-size: 10px;
        }

        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-top: 18px;
            margin-bottom: 7px;
        }

        .status {
            font-weight: bold;
        }

        .signature {
            width: 100%;
            margin-top: 45px;
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


    {{-- HEADER SEKOLAH --}}
    <div class="header">

        <h1>
            SMK NEGERI 1 MAJALAYA
        </h1>

        <h2>
            BIMBINGAN DAN KONSELING
        </h2>

        <p>
            Laporan Pelaksanaan Layanan Responsif
        </p>

    </div>


    {{-- JUDUL --}}
    <div class="title">

        <h3>
            Data Layanan Responsif
        </h3>

    </div>


    {{-- INFORMASI UTAMA --}}
    <table class="info-table">

        <tr>

            <td class="info-label">
                Tanggal
            </td>

            <td class="info-separator">
                :
            </td>

            <td>
                {{ $layananResponsif->tanggal
                    ? $layananResponsif->tanggal->format('d F Y')
                    : '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Guru BK
            </td>

            <td class="info-separator">
                :
            </td>

            <td>
                {{ $layananResponsif->guruBK->nama_lengkap ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Tahun Ajaran
            </td>

            <td class="info-separator">
                :
            </td>

            <td>
                {{ $layananResponsif->tahunAjaran->nama ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Tingkat
            </td>

            <td class="info-separator">
                :
            </td>

            <td>
                {{ $layananResponsif->tingkat ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Kelas
            </td>

            <td class="info-separator">
                :
            </td>

            <td>
                {{ $layananResponsif->kelas->nama_kelas ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Jenis Layanan
            </td>

            <td class="info-separator">
                :
            </td>

            <td>
                {{ $layananResponsif->jenis_layanan ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Bidang Layanan
            </td>

            <td class="info-separator">
                :
            </td>

            <td>
                {{ $layananResponsif->bidangLayanan->nama ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Pendekatan BK
            </td>

            <td class="info-separator">
                :
            </td>

            <td>
                {{ $layananResponsif->pendekatan->nama ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="info-label">
                Status Kasus
            </td>

            <td class="info-separator">
                :
            </td>

            <td class="status">
                {{ $layananResponsif->status_kasus ?? '-' }}
            </td>

        </tr>

    </table>


    {{-- DATA KONSELI --}}
    <div class="section-title">
        Data Konseli / Peserta Layanan
    </div>


    <table class="data-table">

        <thead>

            <tr>

                <th style="width: 40px; text-align: center;">
                    No
                </th>

                <th>
                    NIS
                </th>

                <th>
                    NISN
                </th>

                <th>
                    Nama Siswa
                </th>

                <th>
                    Jenis Kelamin
                </th>

                <th>
                    Peran
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($layananResponsif->peserta as $peserta)

                <tr>

                    <td style="text-align: center;">
                        {{ $loop->iteration }}
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

                    <td>
                        {{ $peserta->siswa->jenis_kelamin ?? '-' }}
                    </td>

                    <td>
                        {{ $peserta->peran ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="6"
                        style="text-align: center;"
                    >
                        Tidak ada data peserta layanan.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- URAIAN MASALAH --}}
    <div class="section-title">
        Uraian Masalah
    </div>

    <table class="data-table">

        <tr>

            <td>
                {{ $layananResponsif->uraian_masalah ?? '-' }}
            </td>

        </tr>

    </table>


    {{-- TINDAK LANJUT --}}
    <div class="section-title">
        Tindak Lanjut
    </div>

    <table class="data-table">

        <tr>

            <td>
                {{ $layananResponsif->tindak_lanjut ?? '-' }}
            </td>

        </tr>

    </table>


    {{-- KETERANGAN --}}
    <div class="section-title">
        Keterangan
    </div>

    <table class="data-table">

        <tr>

            <td>
                {{ $layananResponsif->keterangan ?? '-' }}
            </td>

        </tr>

    </table>


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

                <br>

                NIP. ________________________

            </td>


            <td>
                Guru Bimbingan dan Konseling

                <div class="signature-space"></div>

                <strong>
                    {{ $layananResponsif->guruBK->nama_lengkap ?? '__________________________' }}
                </strong>

                <br>

                NIP. ________________________

            </td>

        </tr>

    </table>


    {{-- FOOTER --}}
    <div class="footer">

        Dokumen Laporan Layanan Responsif —
        Sistem Informasi Bimbingan dan Konseling

    </div>


</body>

</html>