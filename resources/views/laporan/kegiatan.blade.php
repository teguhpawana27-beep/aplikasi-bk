@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Laporan Kegiatan BK
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Rekap seluruh kegiatan Bimbingan dan Konseling
                berdasarkan Tahun Ajaran yang sedang dipilih.
            </p>
        </div>

        <a href="{{ route('laporan.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2
                  rounded-xl border border-gray-200 bg-white
                  text-gray-600 hover:bg-gray-50 transition">

            <i class="fas fa-arrow-left"></i>
            Kembali

        </a>

    </div>


    {{-- TAHUN AJARAN AKTIF --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-6">

        <div class="flex items-center gap-4">

            <div class="w-12 h-12 rounded-xl bg-blue-100
                        flex items-center justify-center shrink-0">

                <i class="fas fa-calendar-alt text-blue-600 text-lg"></i>

            </div>

            <div>

                <p class="text-sm text-blue-600 font-medium">
                    Tahun Ajaran Aktif
                </p>

                <h2 class="text-lg font-bold text-gray-800 mt-1">
                    {{ $tahunAjaran?->nama ?? '-' }}
                </h2>

                @if($tahunAjaran)

                    <p class="text-xs text-gray-500 mt-1">

                        {{ \Carbon\Carbon::parse($tahunAjaran->tanggal_mulai)->format('d M Y') }}

                        -

                        {{ \Carbon\Carbon::parse($tahunAjaran->tanggal_selesai)->format('d M Y') }}

                    </p>

                @endif

            </div>

        </div>

    </div>


    {{-- DOWNLOAD PDF --}}
    <div class="flex justify-end mb-5">

        <a href="{{ route('laporan.kegiatan.download') }}"
           target="_blank"
           class="inline-flex items-center gap-2
                  px-5 py-2.5 rounded-xl bg-red-600 text-white
                  hover:bg-red-700 transition">

            <i class="fas fa-file-pdf"></i>

            Cetak PDF

        </a>

    </div>


    {{-- LAYANAN DASAR --}}
    <div class="bg-white rounded-2xl border border-gray-100
                shadow-sm overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-gray-100">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="font-semibold text-gray-800">
                        Layanan Dasar
                    </h2>

                    <p class="text-xs text-gray-400 mt-1">
                        Kegiatan layanan dasar pada Tahun Ajaran
                        {{ $tahunAjaran?->nama ?? '-' }}
                    </p>

                </div>

                <div class="w-10 h-10 rounded-xl bg-blue-50
                            flex items-center justify-center">

                    <i class="fas fa-users text-blue-600"></i>

                </div>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-5 py-3 text-left">
                            No
                        </th>

                        <th class="px-5 py-3 text-left">
                            Tanggal
                        </th>

                        <th class="px-5 py-3 text-left">
                            Kegiatan
                        </th>

                        <th class="px-5 py-3 text-left">
                            Kelas
                        </th>

                        <th class="px-5 py-3 text-left">
                            Guru BK
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($layananDasar as $item)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-3">
                                {{ $loop->iteration }}
                            </td>


                            <td class="px-5 py-3">

                                {{ $item->tanggal
                                    ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')
                                    : '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->nama_kegiatan
                                    ?? $item->kegiatan
                                    ?? $item->topik
                                    ?? '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->kelas->nama_kelas ?? '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->guruBK->nama_lengkap ?? '-' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="px-5 py-8 text-center text-gray-400">

                                <div class="flex flex-col items-center gap-2">

                                    <i class="fas fa-inbox text-2xl"></i>

                                    <span>
                                        Belum ada kegiatan layanan dasar
                                        pada Tahun Ajaran ini.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- PEMINATAN & PERENCANAAN --}}
    <div class="bg-white rounded-2xl border border-gray-100
                shadow-sm overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-gray-100">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="font-semibold text-gray-800">
                        Peminatan & Perencanaan
                    </h2>

                    <p class="text-xs text-gray-400 mt-1">
                        Kegiatan peminatan dan perencanaan pada Tahun Ajaran
                        {{ $tahunAjaran?->nama ?? '-' }}
                    </p>

                </div>

                <div class="w-10 h-10 rounded-xl bg-purple-50
                            flex items-center justify-center">

                    <i class="fas fa-compass text-purple-600"></i>

                </div>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-5 py-3 text-left">
                            No
                        </th>

                        <th class="px-5 py-3 text-left">
                            Tanggal
                        </th>

                        <th class="px-5 py-3 text-left">
                            Kegiatan
                        </th>

                        <th class="px-5 py-3 text-left">
                            Kelas
                        </th>

                        <th class="px-5 py-3 text-left">
                            Guru BK
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($peminatan as $item)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-3">
                                {{ $loop->iteration }}
                            </td>


                            <td class="px-5 py-3">

                                {{ $item->tanggal
                                    ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')
                                    : '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->nama_kegiatan
                                    ?? $item->kegiatan
                                    ?? $item->topik
                                    ?? '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->kelas->nama_kelas ?? '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->guruBK->nama_lengkap ?? '-' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="px-5 py-8 text-center text-gray-400">

                                <div class="flex flex-col items-center gap-2">

                                    <i class="fas fa-inbox text-2xl"></i>

                                    <span>
                                        Belum ada kegiatan peminatan dan
                                        perencanaan pada Tahun Ajaran ini.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- LAYANAN RESPONSIF --}}
    <div class="bg-white rounded-2xl border border-gray-100
                shadow-sm overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-gray-100">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="font-semibold text-gray-800">
                        Layanan Responsif
                    </h2>

                    <p class="text-xs text-gray-400 mt-1">
                        Kegiatan layanan responsif pada Tahun Ajaran
                        {{ $tahunAjaran?->nama ?? '-' }}
                    </p>

                </div>

                <div class="w-10 h-10 rounded-xl bg-orange-50
                            flex items-center justify-center">

                    <i class="fas fa-hand-holding-heart text-orange-600"></i>

                </div>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-5 py-3 text-left">
                            No
                        </th>

                        <th class="px-5 py-3 text-left">
                            Tanggal
                        </th>

                        <th class="px-5 py-3 text-left">
                            Kegiatan
                        </th>

                        <th class="px-5 py-3 text-left">
                            Kelas
                        </th>

                        <th class="px-5 py-3 text-left">
                            Guru BK
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($responsif as $item)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-3">
                                {{ $loop->iteration }}
                            </td>


                            <td class="px-5 py-3">

                                {{ $item->tanggal
                                    ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')
                                    : '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->nama_kegiatan
                                    ?? $item->kegiatan
                                    ?? $item->topik
                                    ?? '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->kelas->nama_kelas ?? '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->guruBK->nama_lengkap ?? '-' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="px-5 py-8 text-center text-gray-400">

                                <div class="flex flex-col items-center gap-2">

                                    <i class="fas fa-inbox text-2xl"></i>

                                    <span>
                                        Belum ada kegiatan layanan responsif
                                        pada Tahun Ajaran ini.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- DUKUNGAN SISTEM --}}
    <div class="bg-white rounded-2xl border border-gray-100
                shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="font-semibold text-gray-800">
                        Dukungan Sistem
                    </h2>

                    <p class="text-xs text-gray-400 mt-1">
                        Kegiatan dukungan sistem pada Tahun Ajaran
                        {{ $tahunAjaran?->nama ?? '-' }}
                    </p>

                </div>

                <div class="w-10 h-10 rounded-xl bg-emerald-50
                            flex items-center justify-center">

                    <i class="fas fa-cogs text-emerald-600"></i>

                </div>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-5 py-3 text-left">
                            No
                        </th>

                        <th class="px-5 py-3 text-left">
                            Tanggal
                        </th>

                        <th class="px-5 py-3 text-left">
                            Kegiatan
                        </th>

                        <th class="px-5 py-3 text-left">
                            Guru BK
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($dukungan as $item)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-3">
                                {{ $loop->iteration }}
                            </td>


                            <td class="px-5 py-3">

                                {{ $item->tanggal
                                    ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')
                                    : '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->nama_kegiatan
                                    ?? $item->kegiatan
                                    ?? $item->topik
                                    ?? '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $item->guruBK->nama_lengkap ?? '-' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4"
                                class="px-5 py-8 text-center text-gray-400">

                                <div class="flex flex-col items-center gap-2">

                                    <i class="fas fa-inbox text-2xl"></i>

                                    <span>
                                        Belum ada kegiatan dukungan sistem
                                        pada Tahun Ajaran ini.
                                    </span>

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