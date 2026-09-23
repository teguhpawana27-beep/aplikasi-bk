<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Laporan Perkembangan Siswa</title>

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

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .identitas {
            width: 100%;
            margin-bottom: 15px;
        }

        .identitas td {
            padding: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #eeeeee;
        }

        th, td {
            border: 1px solid #555;
            padding: 5px;
            vertical-align: top;
        }

        .section-title {
            background: #eeeeee;
            padding: 7px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
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

    <div>
        Laporan Perkembangan Siswa
    </div>

</div>


<div class="judul">
    LAPORAN PERKEMBANGAN SISWA
</div>


<table class="identitas">

    <tr>
        <td width="20%">
            <strong>Nama Siswa</strong>
        </td>

        <td>
            {{ $siswa->nama_lengkap }}
        </td>
    </tr>

    <tr>
        <td>
            <strong>NIS</strong>
        </td>

        <td>
            {{ $siswa->nis ?? '-' }}
        </td>
    </tr>

    <tr>
        <td>
            <strong>Tahun Ajaran</strong>
        </td>

        <td>

            {{ $tahunTerpilih->nama
                ?? $tahunTerpilih->tahun_ajaran
                ?? $tahunTerpilih->tahun
                ?? 'Semua Tahun Ajaran' }}

        </td>
    </tr>

</table>


{{-- LAYANAN DASAR --}}
<div class="section-title">
    A. LAYANAN DASAR
</div>

<table>

    <thead>

        <tr>

            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th>Kegiatan</th>
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


{{-- PEMINATAN --}}
<div class="section-title">
    B. PEMINATAN & PERENCANAAN
</div>

<table>

    <thead>

        <tr>

            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th>Kegiatan</th>
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


{{-- RESPONSIF --}}
<div class="section-title">
    C. LAYANAN RESPONSIF
</div>

<table>

    <thead>

        <tr>

            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th>Kegiatan</th>
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


{{-- TINDAK LANJUT --}}
<div class="section-title">
    D. TINDAK LANJUT
</div>

<table>

    <thead>

        <tr>

            <th width="5%">No</th>
            <th width="20%">Tanggal Rencana</th>
            <th>Rencana / Keterangan</th>
            <th width="20%">Guru BK</th>

        </tr>

    </thead>

    <tbody>

        @forelse($tindakLanjut as $item)

            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>

                <td>

                    {{ $item->tanggal_rencana
                        ? \Carbon\Carbon::parse($item->tanggal_rencana)->format('d/m/Y')
                        : '-' }}

                </td>

                <td>
                    {{ $item->rencana
                        ?? $item->keterangan
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


{{-- ASESMEN --}}
<div class="section-title">
    E. ASESMEN AWAL
</div>

<table>

    <thead>

        <tr>

            <th width="5%">No</th>
            <th width="20%">Tanggal Asesmen</th>
            <th width="25%">Guru BK</th>
        </tr>

    </thead>

    <tbody>

        @forelse($asesmenAwal as $item)

            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>

                <td>

                    {{ $item->tanggal_asesmen
                        ? \Carbon\Carbon::parse($item->tanggal_asesmen)->format('d/m/Y')
                        : '-' }}

                </td>

                <td>
                    {{ $item->guruBK->nama_lengkap ?? '-' }}
                </td>

            </tr>

        @empty

            <tr>

                <td colspan="3" class="center">
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