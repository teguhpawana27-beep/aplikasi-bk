@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Rekap Per Komponen
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Rekapitulasi jumlah layanan Bimbingan dan Konseling
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


    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

        {{-- LAYANAN DASAR --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Layanan Dasar
                    </p>

                    <h2 class="text-3xl font-bold text-blue-600 mt-2">
                        {{ $data['layananDasar'] ?? 0 }}
                    </h2>
                </div>

                <div class="w-12 h-12 rounded-xl bg-blue-50
                            flex items-center justify-center">

                    <i class="fas fa-users text-blue-600"></i>

                </div>

            </div>

        </div>


        {{-- PEMINATAN --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Peminatan & Perencanaan
                    </p>

                    <h2 class="text-3xl font-bold text-purple-600 mt-2">
                        {{ $data['peminatan'] ?? 0 }}
                    </h2>
                </div>

                <div class="w-12 h-12 rounded-xl bg-purple-50
                            flex items-center justify-center">

                    <i class="fas fa-compass text-purple-600"></i>

                </div>

            </div>

        </div>


        {{-- RESPONSIF --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Layanan Responsif
                    </p>

                    <h2 class="text-3xl font-bold text-orange-600 mt-2">
                        {{ $data['responsif'] ?? 0 }}
                    </h2>
                </div>

                <div class="w-12 h-12 rounded-xl bg-orange-50
                            flex items-center justify-center">

                    <i class="fas fa-hand-holding-heart text-orange-600"></i>

                </div>

            </div>

        </div>


        {{-- DUKUNGAN SISTEM --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Dukungan Sistem
                    </p>

                    <h2 class="text-3xl font-bold text-emerald-600 mt-2">
                        {{ $data['dukungan'] ?? 0 }}
                    </h2>
                </div>

                <div class="w-12 h-12 rounded-xl bg-emerald-50
                            flex items-center justify-center">

                    <i class="fas fa-cogs text-emerald-600"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- TOTAL --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Total Kegiatan
                </p>

                <h2 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $total ?? 0 }}
                </h2>

                <p class="text-sm text-gray-400 mt-2">
                    Total seluruh kegiatan layanan BK pada
                    Tahun Ajaran {{ $tahunAjaran?->nama ?? '-' }}.
                </p>

            </div>

            <div class="w-14 h-14 rounded-xl bg-gray-100
                        flex items-center justify-center">

                <i class="fas fa-chart-bar text-xl text-gray-600"></i>

            </div>

        </div>

    </div>

</div>
@endsection