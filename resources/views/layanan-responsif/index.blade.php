@extends('layouts.app')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | FILTER DATA
    |--------------------------------------------------------------------------
    */

    $jenisAktif = request('jenis');
    $keyword = request('search');

    $dataTampil = collect($layananResponsif);

    /*
    |--------------------------------------------------------------------------
    | FILTER JENIS LAYANAN
    |--------------------------------------------------------------------------
    */

    if ($jenisAktif) {
        $dataTampil = $dataTampil->filter(function ($item) use ($jenisAktif) {
            return strtolower(trim($item->jenis_layanan ?? ''))
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

            $namaSiswa = '';

            if ($item->peserta) {
                foreach ($item->peserta as $peserta) {
                    $namaSiswa .= ' ' . ($peserta->siswa->nama_lengkap ?? '');
                }
            }

            $kelas = $item->kelas->nama_kelas ?? '';
            $jenis = $item->jenis_layanan ?? '';
            $bidang = $item->bidangLayanan->nama ?? '';
            $status = $item->status_kasus ?? '';

            $teksPencarian = strtolower(
                $namaSiswa . ' ' .
                $kelas . ' ' .
                $jenis . ' ' .
                $bidang . ' ' .
                $status
            );

            return str_contains($teksPencarian, $keywordLower);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DAFTAR JENIS LAYANAN
    |--------------------------------------------------------------------------
    */

    $jenisLayanan = [
        'Konseling Individu',
        'Konseling Kelompok',
        'Konsultasi',
        'Mediasi',
        'Alih Tangan Kasus / Referal',
        'E-Konseling',
    ];
@endphp


<div class="mx-auto max-w-7xl">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Layanan Responsif
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Data pelaksanaan layanan responsif Bimbingan dan Konseling
            </p>


            {{-- FILTER SUBMENU AKTIF --}}
            @if($jenisAktif)

                <div class="mt-3 inline-flex items-center gap-2 rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-sm text-blue-700">

                    <i class="fas fa-filter"></i>

                    <span>
                        Menampilkan:
                        <strong>{{ $jenisAktif }}</strong>
                    </span>

                    <a
                        href="{{ route('layanan-responsif.index') }}"
                        class="ml-2 text-blue-600 hover:text-blue-800"
                        title="Tampilkan semua"
                    >

                        <i class="fas fa-times"></i>

                    </a>

                </div>

            @endif

        </div>


        {{-- TOMBOL TAMBAH --}}
        <a
            href="{{ route('layanan-responsif.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
        >

            <span class="text-lg">+</span>

            Tambah Layanan

        </a>

    </div>


    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700">

            <div class="flex items-center gap-2">

                <i class="fas fa-check-circle"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        </div>

    @endif


    {{-- ERROR --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4">

            <p class="mb-2 font-semibold text-red-700">
                Terjadi kesalahan:
            </p>

            <ul class="list-inside list-disc space-y-1 text-sm text-red-600">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- FILTER PENCARIAN --}}
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <form
            method="GET"
            action="{{ route('layanan-responsif.index') }}"
        >

            {{-- PERTAHANKAN FILTER SUBMENU --}}
            @if($jenisAktif)

                <input
                    type="hidden"
                    name="jenis"
                    value="{{ $jenisAktif }}"
                >

            @endif


            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

                {{-- JENIS LAYANAN --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Jenis Layanan

                    </label>

                    <select
                        name="jenis"
                        class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Semua Jenis Layanan
                        </option>

                        @foreach($jenisLayanan as $jenis)

                            <option
                                value="{{ $jenis }}"
                                {{ $jenisAktif === $jenis ? 'selected' : '' }}
                            >

                                {{ $jenis }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- CARI --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Cari

                    </label>

                    <div class="relative">

                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">

                            <i class="fas fa-search"></i>

                        </span>

                        <input
                            type="text"
                            name="search"
                            value="{{ $keyword }}"
                            placeholder="Cari siswa, kelas, bidang..."
                            class="w-full rounded-xl border-slate-300 py-2.5 pl-10 pr-4 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>

                </div>


                {{-- BUTTON --}}
                <div class="flex items-end gap-2">

                    <button
                        type="submit"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >

                        <i class="fas fa-search"></i>

                        Cari

                    </button>


                    @if($jenisAktif || $keyword)

                        <a
                            href="{{ route('layanan-responsif.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                        >

                            <i class="fas fa-rotate-left"></i>

                            Reset

                        </a>

                    @endif

                </div>

            </div>

        </form>

    </div>


    {{-- INFO HASIL --}}
    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

        <div class="text-sm text-slate-500">

            @if($jenisAktif)

                Data

                <span class="font-semibold text-slate-700">
                    {{ $jenisAktif }}
                </span>

            @else

                Semua Layanan Responsif

            @endif

        </div>


        <div class="text-sm text-slate-500">

            Menampilkan

            <span class="font-semibold text-slate-700">
                {{ $dataTampil->count() }}
            </span>

            data

        </div>

    </div>


    {{-- TABLE --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                {{-- HEADER TABLE --}}
                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            No
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            Tanggal
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            Nama Siswa
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            Kelas
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            Jenis Layanan
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            Bidang
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-slate-600">
                            Status
                        </th>

                        <th class="px-5 py-4 text-center font-semibold text-slate-600">
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- BODY --}}
                <tbody class="divide-y divide-slate-100">

                    @forelse($dataTampil as $item)

                        <tr class="transition hover:bg-slate-50">

                            {{-- NO --}}
                            <td class="px-5 py-4 text-slate-600">

                                {{ $loop->iteration }}

                            </td>


                            {{-- TANGGAL --}}
                            <td class="whitespace-nowrap px-5 py-4 text-slate-700">

                                {{ $item->tanggal
                                    ? $item->tanggal->format('d/m/Y')
                                    : '-' }}

                            </td>


                            {{-- SISWA --}}
                            <td class="px-5 py-4">

                                @forelse($item->peserta as $peserta)

                                    <div class="font-medium text-slate-800">

                                        {{ $peserta->siswa->nama_lengkap ?? '-' }}

                                    </div>

                                    @if(!$loop->last)

                                        <div class="h-1"></div>

                                    @endif

                                @empty

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                @endforelse

                            </td>


                            {{-- KELAS --}}
                            <td class="px-5 py-4 text-slate-700">

                                {{ $item->kelas->nama_kelas ?? '-' }}

                            </td>


                            {{-- JENIS LAYANAN --}}
                            <td class="px-5 py-4">

                                <span class="inline-flex rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700">

                                    {{ $item->jenis_layanan }}

                                </span>

                            </td>


                            {{-- BIDANG --}}
                            <td class="px-5 py-4 text-slate-700">

                                {{ $item->bidangLayanan->nama ?? '-' }}

                            </td>


                            {{-- STATUS --}}
                            <td class="px-5 py-4">

                                @if($item->status_kasus === 'Aktif')

                                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">

                                        Aktif

                                    </span>

                                @elseif($item->status_kasus === 'Selesai')

                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">

                                        Selesai

                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">

                                        {{ $item->status_kasus }}

                                    </span>

                                @endif

                            </td>


                            {{-- AKSI DROPDOWN --}}
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
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-xl font-bold text-slate-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        aria-label="Buka menu aksi"
                                        aria-haspopup="true"
                                        :aria-expanded="open"
                                    >

                                        <span class="leading-none">
                                            ⋮
                                        </span>

                                    </button>


                                    {{-- MENU --}}
                                    <div
                                        x-show="open"
                                        x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-xl border border-slate-200 bg-white py-2 text-left shadow-xl"
                                    >

                                        {{-- DETAIL --}}
                                        <a
                                            href="{{ route('layanan-responsif.show', $item->id) }}"
                                            class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-blue-50 hover:text-blue-600"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="h-4 w-4"
                                            >

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12 18 19.5 12 19.5 2.25 12 2.25 12Z"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                                />

                                            </svg>

                                            <span>
                                                Detail
                                            </span>

                                        </a>


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('layanan-responsif.edit', $item->id) }}"
                                            class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-indigo-50 hover:text-indigo-600"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="h-4 w-4"
                                            >

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.687a1.875 1.875 0 1 1 2.652 2.652l-9.193 9.193a4.5 4.5 0 0 1-1.897 1.13l-3.18 0.954.954-3.18a4.5 4.5 0 0 1 1.13-1.897l7.847-7.165Z"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19.5 7.5 16.5 4.5"
                                                />

                                            </svg>

                                            <span>
                                                Edit
                                            </span>

                                        </a>


                                        {{-- CETAK PDF --}}
                                        <a
                                            href="{{ route('layanan-responsif.pdf', $item->id) }}"
                                            target="_blank"
                                            class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-red-50 hover:text-red-600"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="h-4 w-4"
                                            >

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M6.75 3.75h7.5L18.75 8.25v12H6.75v-16.5Z"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M14.25 3.75v4.5h4.5M9 13.5h6M9 16.5h4.5"
                                                />

                                            </svg>

                                            <span>
                                                Cetak PDF
                                            </span>

                                        </a>


                                        {{-- PEMBATAS --}}
                                        <div class="my-1 border-t border-slate-100"></div>


                                        {{-- HAPUS --}}
                                        <form
                                            action="{{ route('layanan-responsif.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data layanan responsif ini?')"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="flex w-full items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50"
                                            >

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.8"
                                                    stroke="currentColor"
                                                    class="h-4 w-4"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.756 0c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0C8.91 1.958 8 2.942 8 4.122v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                                    />

                                                </svg>

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

                        {{-- DATA KOSONG --}}
                        <tr>

                            <td
                                colspan="8"
                                class="px-5 py-14 text-center"
                            >

                                <div class="mb-4 text-5xl text-slate-300">

                                    <i class="fas fa-folder-open"></i>

                                </div>


                                @if($jenisAktif)

                                    <p class="font-semibold text-slate-600">

                                        Belum ada data {{ $jenisAktif }}

                                    </p>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Belum terdapat data layanan untuk jenis layanan tersebut.

                                    </p>

                                @elseif($keyword)

                                    <p class="font-semibold text-slate-600">

                                        Data tidak ditemukan

                                    </p>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Tidak ada data yang sesuai dengan pencarian "{{ $keyword }}".

                                    </p>

                                @else

                                    <p class="font-semibold text-slate-600">

                                        Belum ada data Layanan Responsif

                                    </p>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Silakan tambahkan data layanan responsif.

                                    </p>

                                @endif

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection