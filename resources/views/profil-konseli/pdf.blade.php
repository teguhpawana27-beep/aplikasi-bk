<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Profil Konseli - {{ $profilKonseli->siswa->nama_lengkap ?? 'Siswa' }}
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
            font-weight: bold;
        }

        .header p {
            margin: 3px 0 0;
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
            text-transform: uppercase;
        }

        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .student-table td {
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
        }

        .student-table .label {
            width: 150px;
            font-weight: bold;
            background-color: #f1f5f9;
        }

        .section-title {
            background-color: #e2e8f0;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            font-weight: bold;
            margin-top: 12px;
        }

        .content-box {
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
            width: 100%;
        }

        .footer-table {
            width: 100%;
        }

        .signature {
            width: 45%;
            text-align: center;
        }

        .signature-space {
            height: 65px;
        }

        .small {
            font-size: 9px;
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
            Dokumen Profil Konseli
        </p>

    </div>


    {{-- JUDUL --}}
    <div class="title">

        <h3>
            PROFIL KONSELI
        </h3>

    </div>


    {{-- DATA SISWA --}}
    <table class="student-table">

        <tr>

            <td class="label">
                NIS
            </td>

            <td>
                {{ $profilKonseli->siswa->nis ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                NISN
            </td>

            <td>
                {{ $profilKonseli->siswa->nisn ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Nama Lengkap
            </td>

            <td>
                {{ $profilKonseli->siswa->nama_lengkap ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Jenis Kelamin
            </td>

            <td>
                {{ $profilKonseli->siswa->jenis_kelamin ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Tempat, Tanggal Lahir
            </td>

            <td>

                @if($profilKonseli->siswa)

                    {{ $profilKonseli->siswa->tempat_lahir ?? '-' }}

                    @if($profilKonseli->siswa->tanggal_lahir)

                        ,
                        {{ \Carbon\Carbon::parse($profilKonseli->siswa->tanggal_lahir)->format('d-m-Y') }}

                    @endif

                @else

                    -

                @endif

            </td>

        </tr>


        <tr>

            <td class="label">
                Alamat
            </td>

            <td>
                {{ $profilKonseli->siswa->alamat ?? '-' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                No. HP
            </td>

            <td>
                {{ $profilKonseli->siswa->no_hp ?? '-' }}
            </td>

        </tr>

    </table>


    {{-- KONDISI PRIBADI --}}
    <div class="section-title">
        1. Kondisi Pribadi
    </div>

    <div class="content-box">

        @if($profilKonseli->kondisi_pribadi)

            {!! nl2br(e($profilKonseli->kondisi_pribadi)) !!}

        @else

            <span class="empty">
                Belum ada data.
            </span>

        @endif

    </div>


    {{-- KONDISI SOSIAL --}}
    <div class="section-title">
        2. Kondisi Sosial
    </div>

    <div class="content-box">

        @if($profilKonseli->kondisi_sosial)

            {!! nl2br(e($profilKonseli->kondisi_sosial)) !!}

        @else

            <span class="empty">
                Belum ada data.
            </span>

        @endif

    </div>


    {{-- KONDISI BELAJAR --}}
    <div class="section-title">
        3. Kondisi Belajar
    </div>

    <div class="content-box">

        @if($profilKonseli->kondisi_belajar)

            {!! nl2br(e($profilKonseli->kondisi_belajar)) !!}

        @else

            <span class="empty">
                Belum ada data.
            </span>

        @endif

    </div>


    {{-- KONDISI KARIR --}}
    <div class="section-title">
        4. Kondisi Karir
    </div>

    <div class="content-box">

        @if($profilKonseli->kondisi_karir)

            {!! nl2br(e($profilKonseli->kondisi_karir)) !!}

        @else

            <span class="empty">
                Belum ada data.
            </span>

        @endif

    </div>


    {{-- KONDISI KELUARGA --}}
    <div class="section-title">
        5. Kondisi Keluarga
    </div>

    <div class="content-box">

        @if($profilKonseli->kondisi_keluarga)

            {!! nl2br(e($profilKonseli->kondisi_keluarga)) !!}

        @else

            <span class="empty">
                Belum ada data.
            </span>

        @endif

    </div>


    {{-- CATATAN --}}
    <div class="section-title">
        6. Catatan
    </div>

    <div class="content-box">

        @if($profilKonseli->catatan)

            {!! nl2br(e($profilKonseli->catatan)) !!}

        @else

            <span class="empty">
                Tidak ada catatan.
            </span>

        @endif

    </div>


    {{-- FOOTER / TANDA TANGAN --}}
    <div class="footer">

        <table class="footer-table">

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
                        Majalaya, {{ date('d-m-Y') }}
                    </p>

                    <p>
                        Dokumen Profil Konseli
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