@extends('layouts.app')

@section('content')

<div class="p-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">

        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                Detail Profil Konseli
            </h1>

            <p class="mt-2 text-base text-slate-500">
                Informasi lengkap profil konseli / siswa SMKN 1 Majalaya.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">

            <a
                href="{{ route('profil-konseli.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                ← Kembali
            </a>

            <a
                href="{{ route('profil-konseli.edit', $profilKonseli) }}"
                class="rounded-xl bg-orange-500 px-5 py-3 text-sm font-semibold text-white hover:bg-orange-600"
            >
                Edit
            </a>

            <a
                href="{{ route('profil-konseli.pdf', $profilKonseli) }}"
                class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
            >
                Cetak PDF
            </a>

        </div>

    </div>


    {{-- IDENTITAS SISWA --}}
    <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-7 py-5">
            <h2 class="text-xl font-semibold text-slate-900">
                Identitas Siswa
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Data siswa yang memiliki profil konseli.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 px-7 py-7 md:grid-cols-2">

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Nama Lengkap
                </p>

                <p class="mt-2 text-base font-semibold text-slate-900">
                    {{ $profilKonseli->siswa->nama_lengkap ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm font-medium text-slate-500">
                    NIS
                </p>

                <p class="mt-2 text-base font-semibold text-slate-900">
                    {{ $profilKonseli->siswa->nis ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm font-medium text-slate-500">
                    NISN
                </p>

                <p class="mt-2 text-base font-semibold text-slate-900">
                    {{ $profilKonseli->siswa->nisn ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Jenis Kelamin
                </p>

                <p class="mt-2 text-base font-semibold text-slate-900">
                    {{ $profilKonseli->siswa->jenis_kelamin ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Tempat Lahir
                </p>

                <p class="mt-2 text-base font-semibold text-slate-900">
                    {{ $profilKonseli->siswa->tempat_lahir ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Tanggal Lahir
                </p>

                <p class="mt-2 text-base font-semibold text-slate-900">
                    @if($profilKonseli->siswa?->tanggal_lahir)
                        {{ \Carbon\Carbon::parse($profilKonseli->siswa->tanggal_lahir)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm font-medium text-slate-500">
                    Alamat
                </p>

                <p class="mt-2 text-base font-semibold text-slate-900">
                    {{ $profilKonseli->siswa->alamat ?? '-' }}
                </p>
            </div>

        </div>

    </div>


    {{-- KONDISI KONSELI --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-7 py-5">
            <h2 class="text-xl font-semibold text-slate-900">
                Kondisi Konseli
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Informasi kondisi siswa berdasarkan profil konseli.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 px-7 py-7 md:grid-cols-2">

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="font-semibold text-slate-900">
                    Kondisi Pribadi
                </h3>

                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">
                    {{ $profilKonseli->kondisi_pribadi ?: '-' }}
                </p>
            </div>


            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="font-semibold text-slate-900">
                    Kondisi Sosial
                </h3>

                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">
                    {{ $profilKonseli->kondisi_sosial ?: '-' }}
                </p>
            </div>


            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="font-semibold text-slate-900">
                    Kondisi Belajar
                </h3>

                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">
                    {{ $profilKonseli->kondisi_belajar ?: '-' }}
                </p>
            </div>


            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="font-semibold text-slate-900">
                    Kondisi Karir
                </h3>

                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">
                    {{ $profilKonseli->kondisi_karir ?: '-' }}
                </p>
            </div>


            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 md:col-span-2">
                <h3 class="font-semibold text-slate-900">
                    Kondisi Keluarga
                </h3>

                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">
                    {{ $profilKonseli->kondisi_keluarga ?: '-' }}
                </p>
            </div>

        </div>

    </div>


    {{-- CATATAN --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-7 py-5">
            <h2 class="text-xl font-semibold text-slate-900">
                Catatan
            </h2>
        </div>

        <div class="px-7 py-6">
            <p class="whitespace-pre-line text-sm leading-7 text-slate-600">
                {{ $profilKonseli->catatan ?: 'Tidak ada catatan.' }}
            </p>
        </div>

    </div>


    {{-- TOMBOL BAWAH --}}
    <div class="mt-6 flex flex-wrap justify-end gap-3">

        <a
            href="{{ route('profil-konseli.index') }}"
            class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            ← Kembali
        </a>

        <a
            href="{{ route('profil-konseli.edit', $profilKonseli) }}"
            class="rounded-xl bg-orange-500 px-5 py-3 text-sm font-semibold text-white hover:bg-orange-600"
        >
            Edit Profil
        </a>

        <a
            href="{{ route('profil-konseli.pdf', $profilKonseli) }}"
            class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
        >
            Cetak PDF
        </a>

    </div>

</div>

@endsection