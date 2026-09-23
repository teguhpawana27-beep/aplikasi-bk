@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Peminatan & Perencanaan Individu
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Kelola data layanan peminatan dan perencanaan individu siswa.
            </p>

        </div>


        <a
            href="{{ route('peminatan-perencanaan.create') }}"
            class="inline-flex items-center justify-center
                   px-4 py-2.5
                   bg-blue-600 text-white
                   text-sm font-semibold rounded-lg
                   hover:bg-blue-700 transition"
        >
            + Tambah Data
        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="p-4 bg-green-50 border border-green-200
                    text-green-700 rounded-lg">

            {{ session('success') }}

        </div>

    @endif


    {{-- ERROR --}}
    @if($errors->any())

        <div class="p-4 bg-red-50 border border-red-200
                    text-red-700 rounded-lg">

            <div class="font-semibold mb-2">
                Terjadi kesalahan:
            </div>

            <ul class="list-disc list-inside text-sm space-y-1">

                @foreach($errors->all() as $error)

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
            action="{{ route('peminatan-perencanaan.index') }}"
            class="grid gap-4 p-6 md:grid-cols-3"
        >

            {{-- JENIS LAYANAN --}}
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

                    @foreach([
                        'Bimbingan Klasikal',
                        'Bimbingan Kelas Besar',
                        'Bimbingan Kelompok',
                        'Konseling Individu',
                        'Konseling Kelompok',
                        'Konsultasi',
                        'Kolaborasi'
                    ] as $jenis)

                        <option
                            value="{{ $jenis }}"
                            @selected(request('jenis') === $jenis)
                        >
                            {{ $jenis }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- PENCARIAN --}}
            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Cari
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari sasaran atau kegiatan..."
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >

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
                    href="{{ route('peminatan-perencanaan.index') }}"
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
                            SISWA
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            BIDANG
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            KELAS
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600">
                            SASARAN
                        </th>

                        <th class="px-6 py-4 font-semibold text-gray-600 text-center">
                            AKSI
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($peminatan as $item)

                        <tr class="hover:bg-gray-50 transition">

                            {{-- NO --}}
                            <td class="px-6 py-5 text-gray-600 whitespace-nowrap">

                                {{ $loop->iteration }}

                            </td>


                            {{-- TANGGAL --}}
                            <td class="px-6 py-5 text-gray-600 whitespace-nowrap">

                                {{ $item->tanggal
                                    ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            {{-- JENIS LAYANAN --}}
                            <td class="px-6 py-5">

                                <div class="font-semibold text-gray-800">

                                    {{ $item->jenis_layanan }}

                                </div>

                            </td>


                            {{-- SISWA --}}
                            <td class="px-6 py-5">

                                @if($item->peserta->count())

                                    <div class="space-y-1">

                                        @foreach($item->peserta->take(2) as $peserta)

                                            <div class="font-semibold text-gray-800">

                                                {{ $peserta->siswa->nama_lengkap ?? '-' }}

                                            </div>

                                        @endforeach


                                        @if($item->peserta->count() > 2)

                                            <div class="text-xs text-gray-500 mt-1">

                                                + {{ $item->peserta->count() - 2 }}
                                                siswa lainnya

                                            </div>

                                        @endif

                                    </div>

                                @else

                                    <span class="text-gray-400">
                                        Tidak ada siswa
                                    </span>

                                @endif

                            </td>


                            {{-- BIDANG --}}
                            <td class="px-6 py-5 text-gray-600">

                                {{ $item->bidangLayanan->nama ?? '-' }}

                            </td>


                            {{-- KELAS --}}
                            <td class="px-6 py-5">

                                @if($item->kelas)

                                    <div class="font-semibold text-gray-800">

                                        {{ $item->kelas->nama_kelas }}

                                    </div>

                                    <div class="text-xs text-gray-500 mt-1">

                                        {{ $item->kelas->jurusan->kode ?? '-' }}

                                    </div>

                                @else

                                    <span class="text-gray-500">
                                        Semua Kelas
                                    </span>

                                @endif

                            </td>


                            {{-- SASARAN --}}
                            <td class="px-6 py-5 text-gray-600">

                                {{ $item->sasaran ?: '-' }}

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
                                        class="inline-flex h-10 w-10 items-center justify-center
                                               rounded-lg border border-slate-200
                                               bg-white text-xl font-bold text-slate-600
                                               transition hover:border-indigo-300
                                               hover:bg-indigo-50 hover:text-indigo-600
                                               focus:outline-none focus:ring-2
                                               focus:ring-indigo-500 focus:ring-offset-2"
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
                                        x-transition
                                        class="absolute right-0 z-50 mt-2 w-48
                                               origin-top-right rounded-xl
                                               border border-slate-200 bg-white
                                               py-2 text-left shadow-xl"
                                    >

                                        {{-- LIHAT --}}
                                        <a
                                            href="{{ route('peminatan-perencanaan.show', $item) }}"
                                            class="flex items-center gap-3
                                                   px-4 py-2.5
                                                   text-sm font-medium text-slate-700
                                                   transition hover:bg-blue-50
                                                   hover:text-blue-600"
                                        >

                                            <i class="fas fa-eye w-4"></i>

                                            <span>
                                                Lihat
                                            </span>

                                        </a>


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('peminatan-perencanaan.edit', $item) }}"
                                            class="flex items-center gap-3
                                                   px-4 py-2.5
                                                   text-sm font-medium text-slate-700
                                                   transition hover:bg-indigo-50
                                                   hover:text-indigo-600"
                                        >

                                            <i class="fas fa-edit w-4"></i>

                                            <span>
                                                Edit
                                            </span>

                                        </a>


                                        {{-- CETAK PDF --}}
                                        <a
                                            href="{{ route('peminatan-perencanaan.pdf', $item) }}"
                                            target="_blank"
                                            class="flex items-center gap-3
                                                   px-4 py-2.5
                                                   text-sm font-medium text-slate-700
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
                                            action="{{ route('peminatan-perencanaan.destroy', $item) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="flex w-full items-center gap-3
                                                       px-4 py-2.5
                                                       text-sm font-medium text-red-600
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
                                colspan="8"
                                class="px-6 py-16 text-center"
                            >

                                <div class="text-5xl mb-4">
                                    📋
                                </div>


                                <h3 class="text-lg font-semibold text-gray-800">
                                    Belum Ada Data Peminatan
                                </h3>


                                <p class="text-sm text-gray-500 mt-2 mb-6">
                                    Data peminatan dan perencanaan individu belum tersedia.
                                </p>


                                <a
                                    href="{{ route('peminatan-perencanaan.create') }}"
                                    class="inline-flex items-center
                                           px-4 py-2.5
                                           bg-blue-600 text-white
                                           text-sm font-semibold
                                           rounded-lg
                                           hover:bg-blue-700 transition"
                                >

                                    + Tambah Data

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