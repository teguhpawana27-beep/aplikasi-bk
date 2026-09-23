@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Detail Data Siswa Baru (BMW)
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Informasi lengkap data siswa baru.
            </p>
        </div>

        <div class="flex gap-2">

            {{-- EDIT --}}
            <a
                href="{{ url('/data-bmw/' . $dataBMW->getKey() . '/edit') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold"
            >
                Edit
            </a>

            {{-- KEMBALI --}}
            <a
                href="{{ url('/data-bmw') }}"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-semibold"
            >
                Kembali
            </a>

        </div>
    </div>


    {{-- Informasi Siswa --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">
                Informasi Siswa
            </h2>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-xs text-gray-500 mb-1">
                    Nama Siswa
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $dataBMW->siswa->nama_lengkap ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">
                    NIS
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $dataBMW->siswa->nis ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">
                    NISN
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $dataBMW->siswa->nisn ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">
                    Tahun Ajaran
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $dataBMW->tahunAjaran->nama ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">
                    Tanggal Pendataan
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $dataBMW->tanggal_pendataan?->format('d-m-Y') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">
                    Asal Sekolah
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $dataBMW->asal_sekolah ?? '-' }}
                </p>
            </div>

        </div>
    </div>


    {{-- Data Masuk --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">
                Data Masuk
            </h2>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-700 whitespace-pre-line">
                {{ $dataBMW->data_masuk ?: '-' }}
            </p>
        </div>

    </div>


    {{-- Data Orang Tua --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">
                Data Orang Tua
            </h2>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-700 whitespace-pre-line">
                {{ $dataBMW->data_orang_tua ?: '-' }}
            </p>
        </div>

    </div>


    {{-- Keterangan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">
                Keterangan
            </h2>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-700 whitespace-pre-line">
                {{ $dataBMW->keterangan ?: '-' }}
            </p>
        </div>

    </div>

</div>
@endsection