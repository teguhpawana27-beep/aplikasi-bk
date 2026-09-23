@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Detail Hasil Asesmen Awal
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Informasi lengkap hasil asesmen awal siswa.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('asesmen-awal.edit', $asesmenAwal) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold">
                Edit
            </a>

            <a href="{{ route('asesmen-awal.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-semibold">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">
                Informasi Asesmen
            </h2>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-xs text-gray-500 mb-1">Nama Siswa</p>
                <p class="font-semibold text-gray-800">
                    {{ $asesmenAwal->siswa->nama_lengkap ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">NIS</p>
                <p class="font-semibold text-gray-800">
                    {{ $asesmenAwal->siswa->nis ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Guru BK</p>
                <p class="font-semibold text-gray-800">
                    {{ $asesmenAwal->guruBK->nama_lengkap ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Tahun Ajaran</p>
                <p class="font-semibold text-gray-800">
                    {{ $asesmenAwal->tahunAjaran->nama ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Asesmen</p>
                <p class="font-semibold text-gray-800">
                    {{ $asesmenAwal->tanggal_asesmen?->format('d-m-Y') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Instrumen</p>
                <p class="font-semibold text-gray-800">
                    {{ $asesmenAwal->instrumen ?? '-' }}
                </p>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">Hasil Asesmen</h2>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-700 whitespace-pre-line">
                {{ $asesmenAwal->hasil ?: '-' }}
            </p>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">Rekomendasi</h2>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-700 whitespace-pre-line">
                {{ $asesmenAwal->rekomendasi ?: '-' }}
            </p>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">Tindak Lanjut</h2>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-700 whitespace-pre-line">
                {{ $asesmenAwal->tindak_lanjut ?: '-' }}
            </p>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">Keterangan</h2>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-700 whitespace-pre-line">
                {{ $asesmenAwal->keterangan ?: '-' }}
            </p>
        </div>

    </div>

</div>
@endsection