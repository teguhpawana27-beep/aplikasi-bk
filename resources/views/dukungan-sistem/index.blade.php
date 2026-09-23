
@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Dukungan Sistem
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Pengelolaan kegiatan dukungan sistem Bimbingan dan Konseling
            </p>

        </div>


        <a
            href="{{ route('dukungan-sistem.create') }}"
            class="inline-flex items-center justify-center gap-2
                   rounded-xl bg-blue-600 px-5 py-3
                   text-sm font-semibold text-white
                   hover:bg-blue-700 transition"
        >

            <span class="text-lg">
                +
            </span>

            Tambah Kegiatan

        </a>

    </div>


    {{-- =========================================================
        FLASH SUCCESS
    ========================================================== --}}
    @if(session('success'))

        <div class="mb-6 rounded-xl border border-emerald-200
                    bg-emerald-50 px-5 py-4
                    text-sm text-emerald-700">

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
        ERROR
    ========================================================== --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-200
                    bg-red-50 px-5 py-4">

            <p class="font-semibold text-red-700 mb-2">
                Terjadi kesalahan:
            </p>

            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        FILTER & SEARCH
    ========================================================== --}}
    @php

        $jenisKegiatan = [

            'Pengembangan Profesional Guru BK',
            'Kegiatan Kolaborasi',
            'Kegiatan Administrasi',
            'Kegiatan Pengembangan Sistem',
            'Kegiatan Evaluasi',
            'Kegiatan Lainnya',

        ];

        $jenisAktif = request('jenis');

        $keyword = request('search');


        /*
        |--------------------------------------------------------------------------
        | Data awal
        |--------------------------------------------------------------------------
        */

        $dataTampil = collect($dukunganSistem);


        /*
        |--------------------------------------------------------------------------
        | Filter berdasarkan jenis kegiatan
        |--------------------------------------------------------------------------
        */

        if ($jenisAktif) {

            $dataTampil = $dataTampil->filter(function ($item) use ($jenisAktif) {

                return strtolower(trim($item->jenis_kegiatan ?? ''))
                    === strtolower(trim($jenisAktif));

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Filter berdasarkan pencarian
        |--------------------------------------------------------------------------
        */

        if ($keyword) {

            $keywordLower = strtolower(trim($keyword));

            $dataTampil = $dataTampil->filter(function ($item) use ($keywordLower) {

                $jenis = strtolower($item->jenis_kegiatan ?? '');

                $sasaran = strtolower($item->sasaran ?? '');

                $siswa = strtolower(
                    optional($item->siswa)->nama_lengkap ?? ''
                );

                $nis = strtolower(
                    optional($item->siswa)->nis ?? ''
                );

                $tanggal = '';

                if ($item->tanggal) {

                    $tanggal = strtolower(
                        $item->tanggal->format('d/m/Y')
                    );

                }

                return str_contains($jenis, $keywordLower)

                    || str_contains($sasaran, $keywordLower)

                    || str_contains($siswa, $keywordLower)

                    || str_contains($nis, $keywordLower)

                    || str_contains($tanggal, $keywordLower);

            });

        }

    @endphp


    {{-- =========================================================
        SEARCH & FILTER CARD
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm p-5 mb-6">

        <form
            method="GET"
            action="{{ route('dukungan-sistem.index') }}"
        >

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                {{-- SEARCH --}}
                <div class="md:col-span-5">

                    <label
                        for="search"
                        class="block text-sm font-semibold text-slate-700 mb-2"
                    >
                        Cari Kegiatan
                    </label>

                    <div class="relative">

                        <span
                            class="absolute inset-y-0 left-0
                                   flex items-center pl-4
                                   text-slate-400"
                        >
                            🔎
                        </span>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ $keyword }}"
                            placeholder="Cari jenis kegiatan, sasaran, siswa..."
                            class="w-full rounded-xl border border-slate-300
                                   py-3 pl-11 pr-4
                                   text-sm text-slate-700
                                   focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100
                                   outline-none transition"
                        >

                    </div>

                </div>


                {{-- JENIS --}}
                <div class="md:col-span-5">

                    <label
                        for="jenis"
                        class="block text-sm font-semibold text-slate-700 mb-2"
                    >
                        Jenis Kegiatan
                    </label>

                    <select
                        name="jenis"
                        id="jenis"
                        class="w-full rounded-xl border border-slate-300
                               px-4 py-3
                               text-sm text-slate-700
                               focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100
                               outline-none transition"
                    >

                        <option value="">
                            Semua Jenis Kegiatan
                        </option>

                        @foreach($jenisKegiatan as $jenis)

                            <option
                                value="{{ $jenis }}"
                                {{ $jenisAktif === $jenis ? 'selected' : '' }}
                            >

                                {{ $jenis }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- BUTTON --}}
                <div class="md:col-span-2 flex items-end gap-2">

                    <button
                        type="submit"
                        class="flex-1 rounded-xl
                               bg-blue-600 px-4 py-3
                               text-sm font-semibold text-white
                               hover:bg-blue-700 transition"
                    >
                        Cari
                    </button>


                    <a
                        href="{{ route('dukungan-sistem.index') }}"
                        class="rounded-xl
                               bg-slate-100 px-4 py-3
                               text-sm font-semibold text-slate-600
                               hover:bg-slate-200 transition"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>


        {{-- FILTER AKTIF --}}
        @if($jenisAktif || $keyword)

            <div class="mt-4 pt-4 border-t border-slate-100">

                <div class="flex flex-wrap items-center gap-2">

                    <span class="text-sm text-slate-500">
                        Filter aktif:
                    </span>


                    @if($jenisAktif)

                        <span
                            class="inline-flex items-center gap-2
                                   rounded-lg bg-blue-50
                                   px-3 py-1.5
                                   text-xs font-semibold
                                   text-blue-700"
                        >

                            Jenis:
                            {{ $jenisAktif }}

                        </span>

                    @endif


                    @if($keyword)

                        <span
                            class="inline-flex items-center gap-2
                                   rounded-lg bg-slate-100
                                   px-3 py-1.5
                                   text-xs font-semibold
                                   text-slate-700"
                        >

                            Pencarian:
                            "{{ $keyword }}"

                        </span>

                    @endif

                </div>

            </div>

        @endif

    </div>


    {{-- =========================================================
        RESULT INFO
    ========================================================== --}}
    <div class="flex flex-col sm:flex-row
                sm:items-center sm:justify-between
                gap-2 mb-4">

        <div class="text-sm text-slate-500">

            Menampilkan

            <span class="font-semibold text-slate-700">
                {{ $dataTampil->count() }}
            </span>

            data Dukungan Sistem

            @if($jenisAktif)

                untuk jenis

                <span class="font-semibold text-blue-600">
                    {{ $jenisAktif }}
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        TABLE
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            No
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            Tanggal
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            Jenis Kegiatan
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            Sasaran
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            Siswa
                        </th>

                        <th class="px-5 py-4 text-center font-semibold text-slate-600">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($dataTampil as $item)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- NO --}}
                            <td class="px-5 py-4 text-slate-600">

                                {{ $loop->iteration }}

                            </td>


                            {{-- TANGGAL --}}
                            <td class="px-5 py-4 text-slate-700 whitespace-nowrap">

                                {{ $item->tanggal
                                    ? $item->tanggal->format('d/m/Y')
                                    : '-'
                                }}

                            </td>


                            {{-- JENIS --}}
                            <td class="px-5 py-4">

                                <span
                                    class="inline-flex rounded-lg
                                           bg-blue-50 px-3 py-1.5
                                           text-xs font-medium
                                           text-blue-700"
                                >

                                    {{ $item->jenis_kegiatan }}

                                </span>

                            </td>


                            {{-- SASARAN --}}
                            <td class="px-5 py-4 text-slate-700">

                                {{ $item->sasaran ?: '-' }}

                            </td>


                            {{-- SISWA --}}
                            <td class="px-5 py-4">

                                @if($item->siswa)

                                    <div class="font-medium text-slate-800">

                                        {{ $item->siswa->nama_lengkap }}

                                    </div>


                                    @if($item->siswa->nis)

                                        <div class="text-xs text-slate-400 mt-1">

                                            NIS:
                                            {{ $item->siswa->nis }}

                                        </div>

                                    @endif

                                @else

                                    <span class="text-slate-400">

                                        Tidak terkait siswa tertentu

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                AKSI DROPDOWN
                            ================================================== --}}
                            <td class="px-5 py-4 text-center">

                                <div
                                    x-data="{ open: false }"
                                    class="relative inline-block text-left"
                                >

                                    {{-- TOMBOL TITIK TIGA --}}
                                    <button
                                        type="button"
                                        @click="open = !open"
                                        @click.outside="open = false"
                                        class="inline-flex h-10 w-10
                                               items-center justify-center
                                               rounded-lg border border-slate-200
                                               bg-white text-xl font-bold
                                               text-slate-600 transition
                                               hover:border-indigo-300
                                               hover:bg-indigo-50
                                               hover:text-indigo-600
                                               focus:outline-none
                                               focus:ring-2
                                               focus:ring-indigo-500
                                               focus:ring-offset-2"
                                        aria-label="Buka menu aksi"
                                        aria-haspopup="true"
                                        :aria-expanded="open"
                                    >

                                        <span class="leading-none">
                                            ⋮
                                        </span>

                                    </button>


                                    {{-- MENU DROPDOWN --}}
                                    <div
                                        x-show="open"
                                        x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 z-50 mt-2 w-48
                                               origin-top-right rounded-xl
                                               border border-slate-200
                                               bg-white py-2 text-left
                                               shadow-xl"
                                    >

                                        {{-- DETAIL --}}
                                        <a
                                            href="{{ route('dukungan-sistem.show', $item->id) }}"
                                            class="flex items-center gap-3
                                                   px-4 py-2.5
                                                   text-sm font-medium
                                                   text-slate-700
                                                   transition hover:bg-blue-50
                                                   hover:text-blue-600"
                                        >

                                            <i class="fas fa-eye w-4"></i>

                                            <span>
                                                Detail
                                            </span>

                                        </a>


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('dukungan-sistem.edit', $item->id) }}"
                                            class="flex items-center gap-3
                                                   px-4 py-2.5
                                                   text-sm font-medium
                                                   text-slate-700
                                                   transition hover:bg-indigo-50
                                                   hover:text-indigo-600"
                                        >

                                            <i class="fas fa-edit w-4"></i>

                                            <span>
                                                Edit
                                            </span>

                                        </a>


                                        {{-- PDF --}}
                                        <a
                                            href="{{ route('dukungan-sistem.pdf', $item->id) }}"
                                            target="_blank"
                                            class="flex items-center gap-3
                                                   px-4 py-2.5
                                                   text-sm font-medium
                                                   text-slate-700
                                                   transition hover:bg-red-50
                                                   hover:text-red-600"
                                        >

                                            <i class="fas fa-file-pdf w-4"></i>

                                            <span>
                                                Cetak PDF
                                            </span>

                                        </a>


                                        {{-- PEMBATAS --}}
                                        <div class="my-1 border-t border-slate-100"></div>


                                        {{-- HAPUS --}}
                                        <form
                                            action="{{ route('dukungan-sistem.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data Dukungan Sistem ini?')"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="flex w-full items-center gap-3
                                                       px-4 py-2.5
                                                       text-sm font-medium
                                                       text-red-600
                                                       transition hover:bg-red-50"
                                            >

                                                <i class="fas fa-trash w-4"></i>

                                                <span>
                                                    Hapus
                                                </span>

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-5 py-12 text-center"
                            >

                                <div class="text-slate-400 text-4xl mb-3">
                                    ◇
                                </div>


                                <p class="font-medium text-slate-600">
                                    Tidak ada data ditemukan
                                </p>


                                <p class="text-sm text-slate-400 mt-1">

                                    Silakan ubah kata pencarian atau filter
                                    jenis kegiatan.

                                </p>


                                <div class="mt-4">

                                    <a
                                        href="{{ route('dukungan-sistem.index') }}"
                                        class="inline-flex items-center
                                               rounded-lg bg-slate-100
                                               px-4 py-2
                                               text-xs font-semibold
                                               text-slate-600
                                               hover:bg-slate-200 transition"
                                    >

                                        Reset Filter

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection