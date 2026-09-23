@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Data Hasil Asesmen Awal
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola hasil asesmen awal siswa sebagai dasar layanan BK.
            </p>
        </div>

        <a
            href="{{ route('asesmen-awal.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
        >

            + Tambah Data

        </a>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">

            {{ session('success') }}

        </div>

    @endif


    {{-- ERROR MESSAGE --}}
    @if($errors->any())

        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">

            <div class="mb-2 font-semibold">
                Terjadi kesalahan:
            </div>

            <ul class="list-inside list-disc space-y-1 text-sm">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- CARD DATA --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- CARD TITLE --}}
        <div class="border-b border-gray-200 px-8 py-6">

            <h2 class="text-xl font-semibold text-gray-800">
                Daftar Data Hasil Asesmen Awal
            </h2>

        </div>


        @if($asesmenAwal->count())

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    {{-- TABLE HEADER --}}
                    <thead class="border-b border-gray-200 bg-gray-50">

                        <tr>

                            <th class="px-6 py-4 font-semibold text-gray-600">
                                NO
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-600">
                                NAMA SISWA
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-600">
                                GURU BK
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-600">
                                TAHUN AJARAN
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-600">
                                TANGGAL ASESMEN
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-600">
                                INSTRUMEN
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                AKSI
                            </th>

                        </tr>

                    </thead>


                    {{-- TABLE BODY --}}
                    <tbody class="divide-y divide-gray-100">

                        @foreach($asesmenAwal as $data)

                            <tr class="transition hover:bg-gray-50">

                                {{-- NO --}}
                                <td class="px-6 py-5 text-gray-600">

                                    {{ $loop->iteration }}

                                </td>


                                {{-- SISWA --}}
                                <td class="px-6 py-5">

                                    <div class="font-semibold text-gray-800">

                                        {{ $data->siswa->nama_lengkap ?? '-' }}

                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">

                                        NIS:
                                        {{ $data->siswa->nis ?? '-' }}

                                    </div>

                                </td>


                                {{-- GURU BK --}}
                                <td class="px-6 py-5 text-gray-600">

                                    {{ $data->guruBK->nama_lengkap ?? '-' }}

                                </td>


                                {{-- TAHUN AJARAN --}}
                                <td class="px-6 py-5 text-gray-600">

                                    {{ $data->tahunAjaran->nama ?? '-' }}

                                </td>


                                {{-- TANGGAL --}}
                                <td class="px-6 py-5 text-gray-600">

                                    {{ $data->tanggal_asesmen?->format('d-m-Y') ?? '-' }}

                                </td>


                                {{-- INSTRUMEN --}}
                                <td class="px-6 py-5 text-gray-600">

                                    {{ $data->instrumen ?? '-' }}

                                </td>


                                {{-- AKSI DROPDOWN --}}
                                <td class="px-6 py-5 text-center">

                                    <div
                                        x-data="{ open: false }"
                                        class="relative inline-block text-left"
                                    >

                                        {{-- TOMBOL TITIK TIGA --}}
                                        <button
                                            type="button"
                                            @click="open = !open"
                                            @click.outside="open = false"
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-xl font-bold text-gray-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
                                            class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-xl border border-gray-200 bg-white py-2 text-left shadow-xl"
                                        >

                                            {{-- LIHAT --}}
                                            <a
                                                href="{{ route('asesmen-awal.show', $data) }}"
                                                class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-blue-50 hover:text-blue-600"
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
                                                    Lihat
                                                </span>

                                            </a>


                                            {{-- EDIT --}}
                                            <a
                                                href="{{ route('asesmen-awal.edit', $data) }}"
                                                class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-indigo-50 hover:text-indigo-600"
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
                                                        d="m16.862 4.487 1.687-1.687a1.875 1.875 0 1 1 2.652 2.652l-9.193 9.193a4.5 4.5 0 0 1-1.897 1.13l-3.18.954.954-3.18a4.5 4.5 0 0 1 1.13-1.897l7.847-7.165Z"
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
                                                href="{{ route('asesmen-awal.pdf', $data->id) }}"
                                                target="_blank"
                                                class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-red-50 hover:text-red-600"
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
                                            <div class="my-1 border-t border-gray-100"></div>


                                            {{-- HAPUS --}}
                                            <form
                                                action="{{ route('asesmen-awal.destroy', $data) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus data asesmen ini?')"
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

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- EMPTY STATE --}}
            <div class="px-6 py-16 text-center">

                <div class="mb-4 text-5xl">
                    📝
                </div>

                <h3 class="text-lg font-semibold text-gray-800">
                    Belum Ada Data Asesmen Awal
                </h3>

                <p class="mt-2 mb-6 text-sm text-gray-500">
                    Data hasil asesmen awal siswa belum tersedia.
                </p>

                <a
                    href="{{ route('asesmen-awal.create') }}"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                >

                    + Tambah Data Asesmen

                </a>

            </div>

        @endif

    </div>

</div>

@endsection