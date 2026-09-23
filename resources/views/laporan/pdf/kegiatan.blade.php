<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Laporan Kegiatan BK</title>

    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
        }

        .header h2 {
            margin: 4px 0;
            font-size: 13px;
        }

        .header p {
            margin: 2px 0;
            font-size: 9px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .periode {
            text-align: center;
            margin-bottom: 15px;
        }

        .section-title {
            background: #eeeeee;
            padding: 7px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #eeeeee;
            font-weight: bold;
        }

        th, td {
            border: 1px solid #555;
            padding: 5px;
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>

<body>

<div class="header">

    <h1>SMKN 1 MAJALAYA</h1>

    <h2>BIMBINGAN DAN KONSELING</h2>

    <p>
        Laporan Kegiatan Bimbingan dan Konseling
    </p>

</div>


<div class="judul">
    LAPORAN KEGIATAN BK
</div>


<div class="periode">

    Tahun Ajaran:
    <strong>
        {{ $tahunTerpilih->nama
            ?? $tahunTerpilih->tahun_ajaran
            ?? $tahunTerpilih->tahun
            ?? 'Semua Tahun Ajaran' }}
    </strong>

</div>


{{-- LAYANAN DASAR --}}
<div class="section-title">
    A. LAYANAN DASAR
</div>

<table>

    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="12%">Tanggal</th>
            <th>Kegiatan</th>
            <th width="15%">Kelas</th>
            <th width="20%">Guru BK</th>
        </tr>
    </thead>

    <tbody>

        @forelse($layananDasar as $item)

            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $item->tanggal
                        ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')
                        : '-' }}
                </td>

                <td>
                    {{ $item->nama_kegiatan
                        ?? $item->kegiatan
                        ?? $item->topik
                        ?? '-' }}
                </td>

                <td>
                    {{ $item->kelas->nama_kelas ?? '-' }}
                </td>

                <td>
                    {{ $item->guruBK->nama_lengkap ?? '-' }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="5" class="center">
                    Tidak ada data.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>


{{-- PEMINATAN --}}
<div class="section-title">
    B. PEMINATAN & PERENCANAAN
</div>

<table>

    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="12%">Tanggal</th>
            <th>Kegiatan</th>
            <th width="15%">Kelas</th>
            <th width="20%">Guru BK</th>
        </tr>
    </thead>

    <tbody>

        @forelse($peminatan as $item)

            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $item->tanggal
                        ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')
                        : '-' }}
                </td>

                <td>
                    {{ $item->nama_kegiatan
                        ?? $item->kegiatan
                        ?? $item->topik
                        ?? '-' }}
                </td>

                <td>
                    {{ $item->kelas->nama_kelas ?? '-' }}
                </td>

                <td>
                    {{ $item->guruBK->nama_lengkap ?? '-' }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="5" class="center">
                    Tidak ada data.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>


{{-- RESPONSIF --}}
<div class="section-title">
    C. LAYANAN RESPONSIF
</div>

<table>

    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="12%">Tanggal</th>
            <th>Kegiatan</th>
            <th width="15%">Kelas</th>
            <th width="20%">Guru BK</th>
        </tr>
    </thead>

    <tbody>

        @forelse($responsif as $item)

            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $item->tanggal
                        ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')
                        : '-' }}
                </td>

                <td>
                    {{ $item->nama_kegiatan
                        ?? $item->kegiatan
                        ?? $item->topik
                        ?? '-' }}
                </td>

                <td>
                    {{ $item->kelas->nama_kelas ?? '-' }}
                </td>

                <td>
                    {{ $item->guruBK->nama_lengkap ?? '-' }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="5" class="center">
                    Tidak ada data.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>


{{-- DUKUNGAN --}}
<div class="section-title">
    D. DUKUNGAN SISTEM
</div>

<table>

    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="12%">Tanggal</th>
            <th>Kegiatan</th>
            <th width="25%">Guru BK</th>
        </tr>
    </thead>

    <tbody>

        @forelse($dukungan as $item)

            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $item->tanggal
                        ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')
                        : '-' }}
                </td>

                <td>
                    {{ $item->nama_kegiatan
                        ?? $item->kegiatan
                        ?? $item->topik
                        ?? '-' }}
                </td>

                <td>
                    {{ $item->guruBK->nama_lengkap ?? '-' }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="4" class="center">
                    Tidak ada data.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>


<div class="footer">

    Majalaya, {{ now()->format('d F Y') }}

    <br><br><br><br>

    <strong>Guru Bimbingan dan Konseling</strong>

</div>

</body>
</html>