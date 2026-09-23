@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Detail Dukungan Sistem
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Informasi lengkap kegiatan dukungan sistem Bimbingan dan Konseling
            </p>
        </div>


        <div class="flex flex-wrap gap-2">

            {{-- PDF --}}
            <a
                href="{{ route('dukungan-sistem.pdf', $dukunganSistem->id) }}"
                target="_blank"
                class="inline-flex items-center gap-2
                       rounded-xl bg-red-600
                       px-5 py-3
                       text-sm font-semibold text-white
                       hover:bg-red-700 transition"
            >
                Cetak PDF
            </a>


            {{-- EDIT --}}
            <a
                href="{{ route('dukungan-sistem.edit', $dukunganSistem->id) }}"
                class="inline-flex items-center gap-2
                       rounded-xl bg-blue-600
                       px-5 py-3
                       text-sm font-semibold text-white
                       hover:bg-blue-700 transition"
            >
                Edit
            </a>


            {{-- KEMBALI --}}
            <a
                href="{{ route('dukungan-sistem.index') }}"
                class="inline-flex items-center gap-2
                       rounded-xl border border-slate-300
                       px-5 py-3
                       text-sm font-semibold text-slate-600
                       hover:bg-slate-50 transition"
            >
                Kembali
            </a>

        </div>

    </div>



    {{-- ========================================= --}}
    {{-- DATA UTAMA --}}
    {{-- ========================================= --}}

    <div class="rounded-2xl border border-slate-200
                bg-white p-6 shadow-sm">

        <div class="flex items-center justify-between mb-6">

            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Data Kegiatan
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Informasi dasar kegiatan dukungan sistem
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">


            {{-- JENIS KEGIATAN --}}
            <div>

                <p class="text-xs font-medium uppercase
                          tracking-wide text-slate-400 mb-1">
                    Jenis Kegiatan
                </p>

                <p class="text-sm font-semibold text-slate-800">

                    {{ $dukunganSistem->jenis_kegiatan ?: '-' }}

                </p>

            </div>


            {{-- TANGGAL --}}
            <div>

                <p class="text-xs font-medium uppercase
                          tracking-wide text-slate-400 mb-1">
                    Tanggal
                </p>

                <p class="text-sm font-semibold text-slate-800">

                    {{ $dukunganSistem->tanggal
                        ? $dukunganSistem->tanggal->format('d/m/Y')
                        : '-' }}

                </p>

            </div>


            {{-- GURU BK --}}
            <div>

                <p class="text-xs font-medium uppercase
                          tracking-wide text-slate-400 mb-1">
                    Guru BK
                </p>

                <p class="text-sm font-semibold text-slate-800">

                    {{ $dukunganSistem->guruBK->nama_lengkap ?? '-' }}

                </p>

            </div>


            {{-- TAHUN AJARAN --}}
            <div>

                <p class="text-xs font-medium uppercase
                          tracking-wide text-slate-400 mb-1">
                    Tahun Ajaran
                </p>

                <p class="text-sm font-semibold text-slate-800">

                    {{ $dukunganSistem->tahunAjaran->nama ?? '-' }}

                </p>

            </div>


            {{-- SISWA --}}
            <div>

                <p class="text-xs font-medium uppercase
                          tracking-wide text-slate-400 mb-1">
                    Siswa
                </p>

                @if($dukunganSistem->siswa)

                    <p class="text-sm font-semibold text-slate-800">
                        {{ $dukunganSistem->siswa->nama_lengkap }}
                    </p>

                    @if($dukunganSistem->siswa->nis)

                        <p class="text-xs text-slate-400 mt-1">
                            NIS: {{ $dukunganSistem->siswa->nis }}
                        </p>

                    @endif

                @else

                    <p class="text-sm text-slate-400">
                        Tidak terkait siswa tertentu
                    </p>

                @endif

            </div>


            {{-- SASARAN --}}
            <div>

                <p class="text-xs font-medium uppercase
                          tracking-wide text-slate-400 mb-1">
                    Sasaran
                </p>

                <p class="text-sm font-semibold text-slate-800">

                    {{ $dukunganSistem->sasaran ?: '-' }}

                </p>

            </div>

        </div>

    </div>



    {{-- ========================================= --}}
    {{-- URAIAN --}}
    {{-- ========================================= --}}

    <div class="mt-6 rounded-2xl border border-slate-200
                bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-800 mb-4">
            Uraian Kegiatan
        </h2>

        <div class="rounded-xl bg-slate-50
                    border border-slate-100
                    p-5">

            <p class="text-sm leading-7
                      text-slate-700 whitespace-pre-line">

                {{ $dukunganSistem->uraian_kegiatan ?: '-' }}

            </p>

        </div>

    </div>



    {{-- ========================================= --}}
    {{-- HASIL --}}
    {{-- ========================================= --}}

    <div class="mt-6 rounded-2xl border border-slate-200
                bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-800 mb-4">
            Hasil
        </h2>

        <div class="rounded-xl bg-slate-50
                    border border-slate-100
                    p-5">

            <p class="text-sm leading-7
                      text-slate-700 whitespace-pre-line">

                {{ $dukunganSistem->hasil ?: '-' }}

            </p>

        </div>

    </div>



    {{-- ========================================= --}}
    {{-- EVALUASI --}}
    {{-- ========================================= --}}

    <div class="mt-6 rounded-2xl border border-slate-200
                bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-800 mb-4">
            Evaluasi
        </h2>

        <div class="rounded-xl bg-slate-50
                    border border-slate-100
                    p-5">

            <p class="text-sm leading-7
                      text-slate-700 whitespace-pre-line">

                {{ $dukunganSistem->evaluasi ?: '-' }}

            </p>

        </div>

    </div>



    {{-- ========================================= --}}
    {{-- TINDAK LANJUT --}}
    {{-- ========================================= --}}

    <div class="mt-6 rounded-2xl border border-slate-200
                bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-800 mb-4">
            Tindak Lanjut
        </h2>

        <div class="rounded-xl bg-slate-50
                    border border-slate-100
                    p-5">

            <p class="text-sm leading-7
                      text-slate-700 whitespace-pre-line">

                {{ $dukunganSistem->tindak_lanjut ?: '-' }}

            </p>

        </div>

    </div>



    {{-- ========================================= --}}
    {{-- KETERANGAN --}}
    {{-- ========================================= --}}

    <div class="mt-6 rounded-2xl border border-slate-200
                bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-800 mb-4">
            Keterangan
        </h2>

        <div class="rounded-xl bg-slate-50
                    border border-slate-100
                    p-5">

            <p class="text-sm leading-7
                      text-slate-700 whitespace-pre-line">

                {{ $dukunganSistem->keterangan ?: '-' }}

            </p>

        </div>

    </div>

</div>

@endsection