@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-5xl">

    {{-- Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Detail Siswa
            </h1>

            <p class="mt-1 text-gray-500">
                Informasi lengkap data siswa.
            </p>
        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('siswa.edit', $siswa) }}"
                class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
            >
                Edit Siswa
            </a>

            <a
                href="{{ route('siswa.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Kembali
            </a>

        </div>

    </div>

    {{-- Data Utama --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm">

        {{-- Identitas --}}
        <div class="border-b px-6 py-5">
            <h2 class="text-lg font-semibold text-gray-900">
                Identitas Siswa
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-6 px-6 py-6 md:grid-cols-2">

            <div>
                <p class="text-sm text-gray-500">NIS</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $siswa->nis }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">NISN</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $siswa->nisn ?: '-' }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Nama Lengkap</p>
                <p class="mt-1 text-xl font-semibold text-gray-900">
                    {{ $siswa->nama_lengkap }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Jenis Kelamin</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tahun Masuk</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $siswa->tahun_masuk }}
                </p>
            </div>

        </div>

        {{-- Kelahiran --}}
        <div class="border-t border-b px-6 py-5">
            <h2 class="text-lg font-semibold text-gray-900">
                Data Kelahiran
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-6 px-6 py-6 md:grid-cols-2">

            <div>
                <p class="text-sm text-gray-500">Tempat Lahir</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $siswa->tempat_lahir ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Lahir</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d F Y') : '-' }}
                </p>
            </div>

        </div>

        {{-- Kontak --}}
        <div class="border-t border-b px-6 py-5">
            <h2 class="text-lg font-semibold text-gray-900">
                Kontak & Alamat
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-6 px-6 py-6">

            <div>
                <p class="text-sm text-gray-500">No. HP</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $siswa->no_hp ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Alamat</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $siswa->alamat ?: '-' }}
                </p>
            </div>

        </div>

        {{-- Status --}}
        <div class="border-t border-b px-6 py-5">
            <h2 class="text-lg font-semibold text-gray-900">
                Status Siswa
            </h2>
        </div>

        <div class="px-6 py-6">

            @php
                $statusClass = match($siswa->status) {
                    'aktif' => 'bg-green-100 text-green-700',
                    'lulus' => 'bg-blue-100 text-blue-700',
                    'pindah' => 'bg-yellow-100 text-yellow-700',
                    'keluar' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp

            <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $statusClass }}">
                {{ ucfirst($siswa->status) }}
            </span>

        </div>

        {{-- Footer --}}
        <div class="border-t bg-gray-50 px-6 py-4 text-sm text-gray-500">
            Data dibuat:
            {{ $siswa->created_at?->format('d F Y H:i') ?? '-' }}
        </div>

    </div>

</div>

@endsection