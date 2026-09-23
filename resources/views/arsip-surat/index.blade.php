@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-50 px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div class="mb-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Arsip Surat
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Kelola arsip surat Bimbingan dan Konseling.
                    </p>
                </div>

                <a
                    href="{{ route('arsip-surat.create') }}"
                    class="inline-flex items-center justify-center rounded-xl
                           bg-blue-600 px-5 py-3 text-sm font-semibold
                           text-white shadow-sm transition hover:bg-blue-700"
                >
                    + Tambah Arsip Surat
                </a>

            </div>
        </div>


        {{-- =========================================================
            SUCCESS MESSAGE
        ========================================================== --}}
        @if (session('success'))
            <div
                class="mb-5 rounded-2xl border border-green-200
                       bg-green-50 px-5 py-4 text-sm font-medium text-green-700"
            >
                {{ session('success') }}
            </div>
        @endif


        {{-- =========================================================
            ERROR MESSAGE
        ========================================================== --}}
        @if (session('error'))
            <div
                class="mb-5 rounded-2xl border border-red-200
                       bg-red-50 px-5 py-4 text-sm font-medium text-red-700"
            >
                {{ session('error') }}
            </div>
        @endif


        {{-- =========================================================
            FILTER DATA
        ========================================================== --}}
        @php

            $jenisSurat = [
                'SP 1',
                'SP 2',
                'SP 3',
                'Pemanggilan Orang Tua',
                'Home Visit',
                'Pengunduran Diri',
                'Peringatan',
            ];

            $jenisAktif = request('jenis');
            $keyword = request('search');

            $dataTampil = collect($arsipSurat);

            /*
            |--------------------------------------------------------------------------
            | FILTER JENIS SURAT
            |--------------------------------------------------------------------------
            */
            if ($jenisAktif) {

                $dataTampil = $dataTampil->filter(function ($item) use ($jenisAktif) {

                    return strtolower(trim($item->jenis_surat ?? ''))
                        === strtolower(trim($jenisAktif));

                });

            }

            /*
            |--------------------------------------------------------------------------
            | FILTER PENCARIAN
            |--------------------------------------------------------------------------
            */
            if ($keyword) {

                $keywordLower = strtolower(trim($keyword));

                $dataTampil = $dataTampil->filter(function ($item) use ($keywordLower) {

                    $jenis = strtolower($item->jenis_surat ?? '');

                    $nomor = strtolower($item->nomor_surat ?? '');

                    $perihal = strtolower($item->perihal ?? '');

                    $namaSiswa = strtolower(
                        optional($item->siswa)->nama_lengkap ?? ''
                    );

                    $nis = strtolower(
                        optional($item->siswa)->nis ?? ''
                    );

                    $tanggal = '';

                    if ($item->tanggal_surat) {

                        $tanggal = strtolower(
                            \Carbon\Carbon::parse(
                                $item->tanggal_surat
                            )->format('d/m/Y')
                        );

                    }

                    return str_contains($jenis, $keywordLower)
                        || str_contains($nomor, $keywordLower)
                        || str_contains($perihal, $keywordLower)
                        || str_contains($namaSiswa, $keywordLower)
                        || str_contains($nis, $keywordLower)
                        || str_contains($tanggal, $keywordLower);

                });

            }

        @endphp


        {{-- =========================================================
            SEARCH AND FILTER
        ========================================================== --}}
        <div
            class="mb-6 rounded-2xl border border-gray-200
                   bg-white p-5 shadow-sm"
        >

            <form
                method="GET"
                action="{{ route('arsip-surat.index') }}"
            >

                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">

                    {{-- SEARCH --}}
                    <div class="md:col-span-5">

                        <label
                            for="search"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Cari Arsip Surat
                        </label>

                        <div class="relative">

                            <span
                                class="absolute inset-y-0 left-0 flex items-center
                                       pl-4 text-gray-400"
                            >
                                🔎
                            </span>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ $keyword }}"
                                placeholder="Cari jenis surat, nomor, siswa, perihal..."
                                class="w-full rounded-xl border border-gray-300
                                       py-3 pl-11 pr-4 text-sm text-gray-700
                                       outline-none transition focus:border-blue-500
                                       focus:ring-2 focus:ring-blue-100"
                            >

                        </div>

                    </div>


                    {{-- JENIS SURAT --}}
                    <div class="md:col-span-5">

                        <label
                            for="jenis"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Jenis Surat
                        </label>

                        <select
                            name="jenis"
                            id="jenis"
                            class="w-full rounded-xl border border-gray-300
                                   px-4 py-3 text-sm text-gray-700
                                   outline-none transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100"
                        >

                            <option value="">
                                Semua Jenis Surat
                            </option>

                            @foreach ($jenisSurat as $jenis)

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
                    <div class="flex items-end gap-2 md:col-span-2">

                        <button
                            type="submit"
                            class="flex-1 rounded-xl bg-blue-600 px-4 py-3
                                   text-sm font-semibold text-white
                                   transition hover:bg-blue-700"
                        >
                            Cari
                        </button>

                        <a
                            href="{{ route('arsip-surat.index') }}"
                            class="rounded-xl bg-gray-100 px-4 py-3
                                   text-sm font-semibold text-gray-600
                                   transition hover:bg-gray-200"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>


            {{-- FILTER AKTIF --}}
            @if ($jenisAktif || $keyword)

                <div class="mt-4 border-t border-gray-100 pt-4">

                    <div class="flex flex-wrap items-center gap-2">

                        <span class="text-sm text-gray-500">
                            Filter aktif:
                        </span>

                        @if ($jenisAktif)

                            <span
                                class="inline-flex items-center gap-2 rounded-lg
                                       bg-blue-50 px-3 py-1.5 text-xs
                                       font-semibold text-blue-700"
                            >
                                Jenis: {{ $jenisAktif }}
                            </span>

                        @endif

                        @if ($keyword)

                            <span
                                class="inline-flex items-center gap-2 rounded-lg
                                       bg-gray-100 px-3 py-1.5 text-xs
                                       font-semibold text-gray-700"
                            >
                                Pencarian: "{{ $keyword }}"
                            </span>

                        @endif

                    </div>

                </div>

            @endif

        </div>


        {{-- =========================================================
            RESULT INFORMATION
        ========================================================== --}}
        <div
            class="mb-4 flex flex-col gap-2 sm:flex-row
                   sm:items-center sm:justify-between"
        >

            <div class="text-sm text-gray-500">

                Menampilkan
                <span class="font-semibold text-gray-700">
                    {{ $dataTampil->count() }}
                </span>
                data Arsip Surat

                @if ($jenisAktif)

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
        <div
            class="overflow-hidden rounded-2xl border border-gray-200
                   bg-white shadow-sm"
        >

            <div class="overflow-x-auto">

                <table class="min-w-full text-left text-sm">

                    <thead class="border-b border-gray-200 bg-gray-50">

                        <tr>

                            <th class="px-5 py-4 font-semibold text-gray-700">
                                No
                            </th>

                            <th class="px-5 py-4 font-semibold text-gray-700">
                                Tanggal
                            </th>

                            <th class="px-5 py-4 font-semibold text-gray-700">
                                Jenis Surat
                            </th>

                            <th class="px-5 py-4 font-semibold text-gray-700">
                                Nomor Surat
                            </th>

                            <th class="px-5 py-4 font-semibold text-gray-700">
                                Nama Siswa
                            </th>

                            <th class="px-5 py-4 font-semibold text-gray-700">
                                Perihal
                            </th>

                            <th class="px-5 py-4 text-center font-semibold text-gray-700">
                                File
                            </th>

                            <th class="px-5 py-4 text-center font-semibold text-gray-700">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse ($dataTampil as $item)

                            <tr class="transition hover:bg-gray-50">

                                {{-- NOMOR --}}
                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $loop->iteration }}
                                </td>


                                {{-- TANGGAL --}}
                                <td class="whitespace-nowrap px-5 py-4 text-gray-700">

                                    @if ($item->tanggal_surat)

                                        {{ \Carbon\Carbon::parse(
                                            $item->tanggal_surat
                                        )->format('d/m/Y') }}

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- JENIS SURAT --}}
                                <td class="px-5 py-4">

                                    <span
                                        class="inline-flex rounded-full bg-blue-50
                                               px-3 py-1 text-xs font-semibold
                                               text-blue-700"
                                    >
                                        {{ $item->jenis_surat }}
                                    </span>

                                </td>


                                {{-- NOMOR SURAT --}}
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->nomor_surat ?? '-' }}
                                </td>


                                {{-- NAMA SISWA --}}
                                <td class="px-5 py-4">

                                    <div class="font-semibold text-gray-800">
                                        {{ $item->siswa->nama_lengkap ?? '-' }}
                                    </div>

                                    @if ($item->siswa)

                                        <div class="mt-1 text-xs text-gray-500">
                                            NIS: {{ $item->siswa->nis }}
                                        </div>

                                    @endif

                                </td>


                                {{-- PERIHAL --}}
                                <td class="max-w-xs px-5 py-4 text-gray-700">

                                    <div class="truncate">
                                        {{ $item->perihal }}
                                    </div>

                                </td>


                                {{-- FILE --}}
                                <td class="px-5 py-4 text-center">

                                    @if ($item->file_path)

                                        <a
                                            href="{{ route(
                                                'arsip-surat.file',
                                                $item->id
                                            ) }}"
                                            target="_blank"
                                            class="inline-flex rounded-lg bg-blue-50
                                                   px-3 py-2 text-xs font-semibold
                                                   text-blue-700 transition
                                                   hover:bg-blue-100"
                                        >
                                            Lihat
                                        </a>

                                    @else

                                        <span class="text-xs text-gray-400">
                                            Tidak ada
                                        </span>

                                    @endif

                                </td>


                               {{-- =================================================
    AKSI DROPDOWN
================================================= --}}
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
            class="inline-flex h-10 w-10 items-center justify-center
                   rounded-lg border border-slate-200 bg-white
                   text-xl font-bold text-slate-600 transition
                   hover:border-indigo-300 hover:bg-indigo-50
                   hover:text-indigo-600 focus:outline-none
                   focus:ring-2 focus:ring-indigo-500
                   focus:ring-offset-2"
            aria-label="Buka menu aksi"
            aria-haspopup="true"
            :aria-expanded="open"
        >
            <span class="leading-none">⋮</span>
        </button>

        {{-- MENU DROPDOWN --}}
        <div
            x-show="open"
            x-cloak
            x-transition
            class="absolute right-0 z-50 mt-2 w-48 origin-top-right
                   rounded-xl border border-slate-200 bg-white
                   py-2 text-left shadow-xl"
        >

            {{-- LIHAT --}}
            <a
                href="{{ route('arsip-surat.show', $item->id) }}"
                class="flex items-center gap-3 px-4 py-2.5
                       text-sm font-medium text-slate-700
                       transition hover:bg-blue-50 hover:text-blue-600"
            >
                <i class="fas fa-eye w-4"></i>
                <span>Lihat</span>
            </a>

            {{-- EDIT --}}
            <a
                href="{{ route('arsip-surat.edit', $item->id) }}"
                class="flex items-center gap-3 px-4 py-2.5
                       text-sm font-medium text-slate-700
                       transition hover:bg-indigo-50 hover:text-indigo-600"
            >
                <i class="fas fa-edit w-4"></i>
                <span>Edit</span>
            </a>

            {{-- CETAK PDF --}}
            <a
                href="{{ route('arsip-surat.pdf', $item->id) }}"
                target="_blank"
                class="flex items-center gap-3 px-4 py-2.5
                       text-sm font-medium text-slate-700
                       transition hover:bg-red-50 hover:text-red-600"
            >
                <i class="fas fa-file-pdf w-4"></i>
                <span>Cetak PDF</span>
            </a>

            {{-- PEMBATAS --}}
            <div class="my-1 border-t border-slate-100"></div>

            {{-- HAPUS --}}
            <form
                action="{{ route('arsip-surat.destroy', $item->id) }}"
                method="POST"
                onsubmit="return confirm(
                    'Yakin ingin menghapus arsip surat ini?'
                )"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="flex w-full items-center gap-3 px-4 py-2.5
                           text-sm font-medium text-red-600
                           transition hover:bg-red-50"
                >
                    <i class="fas fa-trash w-4"></i>
                    <span>Hapus</span>
                </button>
            </form>

        </div>

    </div>

</td>

                            </tr>

                        @empty

                            {{-- DATA KOSONG --}}
                            <tr>

                                <td
                                    colspan="8"
                                    class="px-5 py-16 text-center"
                                >

                                    <div class="text-gray-400">

                                        <div class="text-4xl">
                                            📁
                                        </div>

                                        <div
                                            class="mt-3 text-sm font-semibold
                                                   text-gray-600"
                                        >
                                            Tidak ada arsip surat
                                        </div>

                                        <div
                                            class="mt-1 text-xs text-gray-400"
                                        >
                                            Silakan ubah pencarian atau filter
                                            jenis surat.
                                        </div>

                                        <div class="mt-4">

                                            <a
                                                href="{{ route(
                                                    'arsip-surat.index'
                                                ) }}"
                                                class="inline-flex rounded-lg
                                                       bg-gray-100 px-4 py-2
                                                       text-xs font-semibold
                                                       text-gray-600 transition
                                                       hover:bg-gray-200"
                                            >
                                                Reset Filter
                                            </a>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>

@endsection