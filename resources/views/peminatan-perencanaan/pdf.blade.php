<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>
        Laporan Peminatan dan Perencanaan Individu
    </title>

    <style>
        @page {
            margin: 35px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header h2 {
            margin: 3px 0 0;
            font-size: 15px;
            font-weight: bold;
        }

        .header p {
            margin: 3px 0 0;
            font-size: 11px;
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

        .identity {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .identity td {
            padding: 5px 3px;
            vertical-align: top;
        }

        .identity .label {
            width: 160px;
            font-weight: bold;
        }

        .identity .separator {
            width: 10px;
        }

        .section-title {
            background: #f1f5f9;
            border: 1px solid #d1d5db;
            padding: 7px 9px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        .data-table th {
            width: 180px;
            background: #f8fafc;
            text-align: left;
            font-weight: bold;
        }

        .peserta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .peserta-table th,
        .peserta-table td {
            border: 1px solid #d1d5db;
            padding: 7px;
        }

        .peserta-table th {
            background: #f8fafc;
            text-align: left;
        }

        .footer {
            margin-top: 45px;
        }

        .signature {
            width: 45%;
            text-align: center;
            float: right;
        }

        .signature-space {
            height: 65px;
        }

        .clear {
            clear: both;
        }

        .text-muted {
            color: #666;
        }
    </style>
</head>

<body>

    {{-- HEADER SEKOLAH --}}
    <div class="header">

        <h1>
            SMKN 1 MAJALAYA
        </h1>

        <h2>
            SISTEM INFORMASI BIMBINGAN & KONSELING
        </h2>

        <p>
            Laporan Peminatan dan Perencanaan Individu
        </p>

    </div>


    {{-- JUDUL --}}
    <div class="title">

        <h3>
            Data Peminatan & Perencanaan Individu
        </h3>

    </div>


    {{-- IDENTITAS --}}
    <table class="identity">

        <tr>
            <td class="label">
                Guru BK
            </td>

            <td class="separator">
                :
            </td>

            <td>
                {{ $peminatan->guruBK->nama_lengkap ?? '-' }}
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
                {{ $peminatan->tahunAjaran->nama ?? '-' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                Kelas
            </td>

            <td class="separator">
                :
            </td>

            <td>
                @if($peminatan->kelas)
                    {{ $peminatan->kelas->nama_kelas ?? '-' }}
                @else
                    Semua Kelas
                @endif
            </td>
        </tr>


        <tr>
            <td class="label">
                Bidang Layanan
            </td>

            <td class="separator">
                :
            </td>

            <td>
                {{ $peminatan->bidangLayanan->nama ?? '-' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                Jenis Layanan
            </td>

            <td class="separator">
                :
            </td>

            <td>
                {{ $peminatan->jenis_layanan ?? '-' }}
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
                {{ $peminatan->tanggal?->format('d-m-Y') ?? '-' }}
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
                {{ $peminatan->sasaran ?? '-' }}
            </td>
        </tr>

    </table>


    {{-- PESERTA --}}
    <div class="section-title">
        Peserta / Siswa
    </div>

    @if($peminatan->peserta && $peminatan->peserta->count())

        <table class="peserta-table">

            <thead>
                <tr>
                    <th style="width: 40px;">
                        No
                    </th>

                    <th>
                        Nama Siswa
                    </th>

                    <th style="width: 130px;">
                        NIS
                    </th>
                </tr>
            </thead>

            <tbody>

                @foreach($peminatan->peserta as $peserta)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $peserta->siswa->nama_lengkap ?? '-' }}
                        </td>

                        <td>
                            {{ $peserta->siswa->nis ?? '-' }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <table class="peserta-table">

            <tr>
                <td class="text-muted">
                    Tidak ada siswa yang dipilih.
                </td>
            </tr>

        </table>

    @endif


    {{-- DETAIL KEGIATAN --}}
    <div class="section-title">
        Detail Kegiatan
    </div>

    <table class="data-table">

        <tr>

            <th>
                Uraian Kegiatan
            </th>

            <td>
                {!! nl2br(e($peminatan->uraian_kegiatan ?? '-')) !!}
            </td>

        </tr>


        <tr>

            <th>
                Tindak Lanjut
            </th>

            <td>
                {!! nl2br(e($peminatan->tindak_lanjut ?? '-')) !!}
            </td>

        </tr>


        <tr>

            <th>
                Keterangan
            </th>

            <td>
                {!! nl2br(e($peminatan->keterangan ?? '-')) !!}
            </td>

        </tr>

    </table>


    {{-- FOOTER / TANDA TANGAN --}}
    <div class="footer">

        <div class="signature">

            <div>
                Majalaya,
                {{ now()->translatedFormat('d F Y') }}
            </div>

            <div>
                Guru BK
            </div>

            <div class="signature-space"></div>

            <strong>
                {{ $peminatan->guruBK->nama_lengkap ?? '________________________' }}
            </strong>

        </div>

        <div class="clear"></div>

    </div>

</body>
</html>