@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Laporan Bimbingan dan Konseling
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Kelola dan cetak berbagai laporan kegiatan Bimbingan dan Konseling.
        </p>
    </div>

    {{-- CARD LAPORAN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        {{-- REKAP KOMPONEN --}}
        <a href="{{ route('laporan.komponen') }}"
           class="group bg-white rounded-2xl border border-gray-100 shadow-sm
                  hover:shadow-md hover:-translate-y-1 transition p-6">

            <div class="flex items-center justify-between mb-5">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600
                            flex items-center justify-center">
                    <i class="fas fa-chart-pie text-xl"></i>
                </div>

                <i class="fas fa-arrow-right text-gray-300
                          group-hover:text-blue-600 transition"></i>
            </div>

            <h2 class="text-lg font-semibold text-gray-800">
                Rekap Per Komponen
            </h2>

            <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                Menampilkan jumlah kegiatan berdasarkan komponen layanan BK.
            </p>
        </a>


        {{-- LAPORAN KEGIATAN --}}
        <a href="{{ route('laporan.kegiatan') }}"
           class="group bg-white rounded-2xl border border-gray-100 shadow-sm
                  hover:shadow-md hover:-translate-y-1 transition p-6">

            <div class="flex items-center justify-between mb-5">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600
                            flex items-center justify-center">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>

                <i class="fas fa-arrow-right text-gray-300
                          group-hover:text-emerald-600 transition"></i>
            </div>

            <h2 class="text-lg font-semibold text-gray-800">
                Laporan Kegiatan BK
            </h2>

            <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                Menampilkan seluruh kegiatan layanan Bimbingan dan Konseling.
            </p>
        </a>


        {{-- PERKEMBANGAN SISWA --}}
        <a href="{{ route('laporan.perkembangan') }}"
           class="group bg-white rounded-2xl border border-gray-100 shadow-sm
                  hover:shadow-md hover:-translate-y-1 transition p-6">

            <div class="flex items-center justify-between mb-5">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600
                            flex items-center justify-center">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>

                <i class="fas fa-arrow-right text-gray-300
                          group-hover:text-purple-600 transition"></i>
            </div>

            <h2 class="text-lg font-semibold text-gray-800">
                Perkembangan Siswa
            </h2>

            <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                Melihat riwayat layanan dan perkembangan konseli secara
                individual.
            </p>
        </a>

    </div>

</div>
@endsection