@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="text-sm font-medium text-gray-500">
                Layanan Dasar
            </div>

            <h1 class="mt-1 text-2xl font-bold text-gray-800">
                Layanan Dasar
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola kegiatan layanan dasar Bimbingan dan Konseling.
            </p>
        </div>

        <a
            href="{{ route('layanan-dasar.create') }}"
            class="inline-flex items-center justify-center
                   px-4 py-2.5
                   bg-blue-600 text-white
                   text-sm font-semibold rounded-lg
                   hover:bg-blue-700 transition"
        >
            + Tambah Layanan
        </a>

    </div>


    {{-- SUCCESS --}}
    @if (session('success'))

        <div class="p-4 bg-green-50 border border-green-200
                    text-green-700 rounded-lg">

            {{ session('success') }}

        </div>

    @endif


    {{-- ERROR --}}
    @if ($errors->any())

        <div class="p-4 bg-red-50 border border-red-200
                    text-red-700 rounded-lg">

            <div class="font-semibold mb-2">
                Terjadi kesalahan:
            </div>

            <ul class="list-disc list-inside text-sm space-y-1">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- FILTER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <form
            method="GET"
            action="{{ route('layanan-dasar.index') }}"
            class="grid gap-4 p-6 md:grid-cols-3"
        >

            {{-- SEARCH --}}
            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Cari
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari topik, sasaran, atau jenis..."
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >

            </div>


            {{-- JENIS --}}
            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jenis Layanan
                </label>

                <select
                    name="jenis"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        Semua Jenis Layanan
                    </option>

                    <option
                        value="Bimbingan Klasikal"
                        @selected(request('jenis') === 'Bimbingan Klasikal')
                    >
                        Bimbingan Klasikal
                    </option>

                    <option
                        value="Bimbingan Kelompok"
                        @selected(request('jenis') === 'Bimbingan Kelompok')
                    >
                        Bimbingan Kelompok
                    </option>

                    <option
                        value="Bimbingan Kelas Besar / Lintas Kelas"
                        @selected(request('jenis') === 'Bimbingan Kelas Besar / Lintas Kelas')
                    >
                        Bimbingan Kelas Besar / Lintas Kelas
                    </option>

                    <option
                        value="Pengembangan Media BK"
                        @selected(request('jenis') === 'Pengembangan Media BK')
                    >
                        Pengembangan Media BK
                    </option>

                </select>

            </div>


            {{-- BUTTON --}}
            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600
                           px-5 py-2.5
                           text-sm font-semibold text-white
                           hover:bg-blue-700 transition"
                >
                    Cari
                </button>

                <a
                    href="{{ route('layanan-dasar.index') }}"
                    class="rounded-lg border border-gray-300
                           bg-white px-5 py-2.5
                           text-sm font-semibold text-gray-700
                           hover:bg-gray-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                {{-- TABLE HEADER --}}
                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            NO
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            TANGGAL
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            JENIS LAYANAN
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            TOPIK
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            KELAS
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            PESERTA
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            GURU BK
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600 text-center">
                            AKSI
                        </th>

                    </tr>

                </thead>


                {{-- TABLE BODY --}}
                <tbody class="divide-y divide-gray-100">

                    @forelse ($layananDasar as $index => $layanan)

                        <tr class="hover:bg-gray-50 transition">

                            {{-- NO --}}
                            <td class="px-6 py-5 text-gray-600 whitespace-nowrap">

                                {{ $index + 1 }}

                            </td>


                            {{-- TANGGAL --}}
                            <td class="px-6 py-5 text-gray-600 whitespace-nowrap">

                                @if ($layanan->tanggal)

                                    {{ \Carbon\Carbon::parse($layanan->tanggal)->format('d-m-Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- JENIS --}}
                            <td class="px-6 py-5">

                                <div class="font-semibold text-gray-800">

                                    {{ $layanan->jenis_layanan }}

                                </div>

                            </td>


                            {{-- TOPIK --}}
                            <td class="px-6 py-5">

                                <div class="text-gray-700 max-w-xs">

                                    {{ $layanan->topik }}

                                </div>

                            </td>


                            {{-- KELAS --}}
                            <td class="px-6 py-5">

                                @if ($layanan->kelas)

                                    <div class="font-semibold text-gray-800">

                                        {{ $layanan->kelas->nama_kelas }}

                                    </div>

                                    <div class="text-xs text-gray-500 mt-1">

                                        {{ $layanan->kelas->jurusan->kode ?? '-' }}

                                    </div>

                                @else

                                    <span class="text-gray-500">
                                        Semua Kelas
                                    </span>

                                @endif

                            </td>


                            {{-- PESERTA --}}
                            <td class="px-6 py-5">

                                <span
                                    class="inline-flex rounded-full
                                           bg-gray-100
                                           px-3 py-1
                                           text-xs font-semibold
                                           text-gray-600"
                                >

                                    {{ $layanan->peserta->count() }} siswa

                                </span>

                            </td>


                            {{-- GURU BK --}}
                            <td class="px-6 py-5">

                                <div class="text-gray-700">

                                    {{ $layanan->guruBK->nama_lengkap ?? '-' }}

                                </div>

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
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-xl font-bold text-slate-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        aria-label="Buka menu aksi"
                                        aria-haspopup="true"
                                        :aria-expanded="open"
                                    >

                                        <span class="leading-none">
                                            ⋮
                                        </span>

                                    </button>


                                    {{-- MENU AKSI --}}
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

                                        {{-- LIHAT --}}
                                        <a
                                            href="{{ route('layanan-dasar.show', $layanan) }}"
                                            class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-blue-50 hover:text-blue-600"
                                        >

                                            <i class="fas fa-eye w-4"></i>

                                            <span>
                                                Lihat
                                            </span>

                                        </a>


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('layanan-dasar.edit', $layanan) }}"
                                            class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-indigo-50 hover:text-indigo-600"
                                        >

                                            <i class="fas fa-edit w-4"></i>

                                            <span>
                                                Edit
                                            </span>

                                        </a>


                                        {{-- CETAK PDF --}}
                                        <a
                                            href="{{ route('layanan-dasar.pdf', $layanan) }}"
                                            target="_blank"
                                            class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-red-50 hover:text-red-600"
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
                                            action="{{ route('layanan-dasar.destroy', $layanan) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data layanan ini?')"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="flex w-full items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50"
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
                                colspan="8"
                                class="px-6 py-16 text-center"
                            >

                                <div class="text-5xl mb-4">
                                    📋
                                </div>

                                <div class="text-sm font-semibold text-gray-600">
                                    Belum ada data layanan dasar.
                                </div>

                                <div class="mt-1 text-xs text-gray-400">
                                    Silakan tambahkan kegiatan layanan dasar.
                                </div>

                                <a
                                    href="{{ route('layanan-dasar.create') }}"
                                    class="mt-4 inline-flex
                                           rounded-lg bg-blue-600
                                           px-4 py-2
                                           text-sm font-semibold text-white
                                           hover:bg-blue-700"
                                >

                                    + Tambah Layanan

                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection