<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Informasi BK') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media (max-width: 639px) {
            html, body { overflow-x: hidden; }

            /* Tables scroll inside the page instead of widening the whole viewport. */
            main table { min-width: 680px; }

            main input, main select, main textarea {
                max-width: 100%;
            }
        }

        aside, main {
            -webkit-overflow-scrolling: touch;
        }
    </style>

</head>

<body class="font-sans antialiased bg-slate-100 text-slate-800">

<div
    x-data="{ sidebarOpen: false }"
    class="min-h-screen overflow-x-hidden"
>

    {{-- OVERLAY MOBILE --}}
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        style="display: none;"
    ></div>


    {{-- SIDEBAR --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 w-[min(290px,calc(100vw-24px))] max-w-[290px] bg-[#0f172a] text-white transform transition-transform duration-300 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >

        {{-- LOGO --}}
        <div class="flex h-20 shrink-0 items-center gap-3 border-b border-white/10 px-4 sm:px-5">

            <img
                src="{{ asset('images/logo-smkn.png') }}"
                alt="Logo SMKN 1 Majalaya"
                class="h-12 w-12 shrink-0 object-contain sm:h-14 sm:w-14"
            >

            <div>
                <h1 class="text-sm font-bold leading-tight">
                    SISTEM INFORMASI BK
                </h1>

                <p class="mt-1 text-xs text-slate-400">
                    SMKN 1 Majalaya
                </p>
            </div>

        </div>


        {{-- NAVIGATION --}}
        <div class="h-[calc(100vh-80px)] overflow-y-auto overscroll-contain px-2.5 py-3 pb-6 sm:px-3 sm:py-4">

            <nav class="space-y-1.5 sm:space-y-2">


                {{-- ================================================= --}}
                {{-- DASHBOARD --}}
                {{-- ================================================= --}}

                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                    {{ request()->routeIs('dashboard')
                        ? 'bg-blue-600 text-white'
                        : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="h-5 w-5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"
                        />
                    </svg>

                    Dashboard

                </a>


                {{-- ================================================= --}}
                {{-- MANAJEMEN PENGGUNA -- ADMIN ONLY --}}
                {{-- ================================================= --}}
                @if(auth()->user()->role?->name === 'Admin')
                    <a
                        href="{{ route('users.index') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                        {{ request()->routeIs('users.*')
                            ? 'bg-blue-600 text-white'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        Manajemen Pengguna
                    </a>
                @endif


                {{-- ================================================= --}}
                {{-- 1. HIMPUNAN DATA --}}
                {{-- ================================================= --}}

                <div
                    x-data="{
                        open: {{ request()->routeIs(
                            'siswa.*',
                            'profil-konseli.*',
                            'data-bmw.*',
                            'data-snpmb.*',
                            'asesmen-awal.*'
                        ) ? 'true' : 'false' }}
                    }"
                >

                    <button
                        type="button"
                        @click="open = !open"
                        class="flex min-h-11 w-full items-center justify-between rounded-xl px-3.5 py-3 text-left text-sm font-semibold text-slate-200 hover:bg-white/5 sm:px-4"
                    >

                        <span class="flex items-center gap-3">

                            <svg
                                class="h-5 w-5 text-blue-400"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>

                            HIMPUNAN DATA

                        </span>

                        <svg
                            class="h-4 w-4 transition-transform"
                            :class="open ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>

                    </button>


                    <div
                        x-show="open"
                        x-collapse
                        class="mt-1 space-y-1 pl-2 sm:pl-3"
                    >

                        <a
                            href="{{ route('siswa.index') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request()->routeIs('siswa.*')
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Profil Siswa
                        </a>

                        <a
                            href="{{ route('profil-konseli.index') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request()->routeIs('profil-konseli.*')
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Profil Konseli
                        </a>

                        <a
                            href="{{ route('data-bmw.index') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request()->routeIs('data-bmw.*')
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Data Siswa Baru (BMW)
                        </a>

                        <a
                            href="{{ route('data-snpmb.index') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request()->routeIs('data-snpmb.*')
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Data Siswa SNPMB
                        </a>

                        <a
                            href="{{ route('asesmen-awal.index') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request()->routeIs('asesmen-awal.*')
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Data Hasil Asesmen Awal
                        </a>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- 2. LAYANAN DASAR --}}
                {{-- ================================================= --}}

                <div
                    x-data="{
                        open: {{ request()->routeIs('layanan-dasar.*') ? 'true' : 'false' }}
                    }"
                >

                    <button
                        type="button"
                        @click="open = !open"
                        class="flex min-h-11 w-full items-center justify-between rounded-xl px-3.5 py-3 text-left text-sm font-semibold text-slate-200 hover:bg-white/5 sm:px-4"
                    >

                        <span class="flex items-center gap-3">

                            <svg
                                class="h-5 w-5 text-emerald-400"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6.5c-2-2-5-2-7-.5V18c2-1.5 5-1.5 7 .5m0-12c2-2 5-2 7-.5V18c-2-1.5 5-1.5-7 .5m0-12v12"
                                />
                            </svg>

                            LAYANAN DASAR

                        </span>

                        <svg
                            class="h-4 w-4 transition-transform"
                            :class="open ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>

                    </button>


                    <div
                        x-show="open"
                        x-collapse
                        class="mt-1 space-y-1 pl-2 sm:pl-3"
                    >

                        <a
                            href="{{ route('layanan-dasar.index', ['jenis' => 'Bimbingan Klasikal']) }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request('jenis') === 'Bimbingan Klasikal'
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Bimbingan Klasikal
                        </a>

                        <a
                            href="{{ route('layanan-dasar.index', ['jenis' => 'Bimbingan Kelompok']) }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request('jenis') === 'Bimbingan Kelompok'
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Bimbingan Kelompok
                        </a>

                        <a
                            href="{{ route('layanan-dasar.index', ['jenis' => 'Bimbingan Kelas Besar / Lintas Kelas']) }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request('jenis') === 'Bimbingan Kelas Besar / Lintas Kelas'
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Bimbingan Kelas Besar / Lintas Kelas
                        </a>

                        <a
                            href="{{ route('layanan-dasar.index', ['jenis' => 'Pengembangan Media BK']) }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request('jenis') === 'Pengembangan Media BK'
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Pengembangan Media BK
                        </a>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- 3. PEMINATAN DAN PERENCANAAN INDIVIDU --}}
                {{-- ================================================= --}}

                <div
                    x-data="{
                        open: {{ request()->routeIs('peminatan-perencanaan.*') ? 'true' : 'false' }}
                    }"
                >

                    <button
                        type="button"
                        @click="open = !open"
                        class="flex min-h-11 w-full items-center justify-between rounded-xl px-3.5 py-3 text-left text-sm font-semibold text-slate-200 hover:bg-white/5 sm:px-4"
                    >

                        <span class="flex items-center gap-3">

                            <svg
                                class="h-5 w-5 text-violet-400"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 14a4 4 0 100-8 4 4 0 000 8zm-7 7a7 7 0 0114 0"
                                />
                            </svg>

                            PEMINATAN DAN PERENCANAAN INDIVIDU

                        </span>

                        <svg
                            class="h-4 w-4 transition-transform"
                            :class="open ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>

                    </button>


                    <div
                        x-show="open"
                        x-collapse
                        class="mt-1 space-y-1 pl-2 sm:pl-3"
                    >

                        @foreach([
                            'Bimbingan Klasikal',
                            'Bimbingan Kelas Besar',
                            'Bimbingan Kelompok',
                            'Konseling Individu',
                            'Konseling Kelompok',
                            'Konsultasi',
                            'Kolaborasi'
                        ] as $item)

                            <a
                                href="{{ route('peminatan-perencanaan.index', ['jenis' => $item]) }}"
                                class="block rounded-lg px-4 py-2.5 text-sm
                                {{ request('jenis') === $item
                                    ? 'bg-blue-600 text-white'
                                    : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                            >
                                {{ $item }}
                            </a>

                        @endforeach

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- 4. LAYANAN RESPONSIF --}}
                {{-- ================================================= --}}

                <div
                    x-data="{
                        open: {{ request()->routeIs('layanan-responsif.*') ? 'true' : 'false' }}
                    }"
                >

                    <button
                        type="button"
                        @click="open = !open"
                        class="flex min-h-11 w-full items-center justify-between rounded-xl px-3.5 py-3 text-left text-sm font-semibold text-slate-200 hover:bg-white/5 sm:px-4"
                    >

                        <span class="flex items-center gap-3">

                            <svg
                                class="h-5 w-5 text-amber-400"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v4m0 4h.01M10.3 3.5h3.4L21 17.5a2 2 0 01-1.7 3H4.7a2 2 0 01-1.7-3l7.3-14z"
                                />
                            </svg>

                            LAYANAN RESPONSIF

                        </span>

                        <svg
                            class="h-4 w-4 transition-transform"
                            :class="open ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>

                    </button>


                    <div
                        x-show="open"
                        x-collapse
                        class="mt-1 space-y-1 pl-2 sm:pl-3"
                    >

                        @foreach([
                            'Konseling Individu',
                            'Konseling Kelompok',
                            'Konsultasi',
                            'Mediasi',
                            'Alih Tangan Kasus / Referal',
                            'Konferensi Kasus',
                            'Advokasi',
                            'E-Konseling',
                            'Bimbingan Teman Sebaya'
                        ] as $item)

                            <a
                                href="{{ route('layanan-responsif.index', ['jenis' => $item]) }}"
                                class="block rounded-lg px-4 py-2.5 text-sm
                                {{ request('jenis') === $item
                                    ? 'bg-blue-600 text-white'
                                    : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                            >
                                {{ $item }}
                            </a>

                        @endforeach

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- 5. DUKUNGAN SISTEM --}}
                {{-- ================================================= --}}

                <div
                    x-data="{
                        open: {{ request()->routeIs('dukungan-sistem.*') ? 'true' : 'false' }}
                    }"
                >

                    <button
                        type="button"
                        @click="open = !open"
                        class="flex min-h-11 w-full items-center justify-between rounded-xl px-3.5 py-3 text-left text-sm font-semibold text-slate-200 hover:bg-white/5 sm:px-4"
                    >

                        <span class="flex items-center gap-3">

                            <svg
                                class="h-5 w-5 text-cyan-400"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 3v2m6-2v2M4 9h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z"
                                />
                            </svg>

                            DUKUNGAN SISTEM

                        </span>

                        <svg
                            class="h-4 w-4 transition-transform"
                            :class="open ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>

                    </button>


                    <div
                        x-show="open"
                        x-collapse
                        class="mt-1 space-y-1 pl-2 sm:pl-3"
                    >

                        @foreach([
                            'Kolaborasi',
                            'Home Visit',
                            'Pelaksanaan dan Tindak Lanjut Asesmen',
                            'Penyusunan dan Pelaporan Program BK',
                            'Evaluasi BK',
                            'Pelaksanaan Administrasi dan Mekanisme BK',
                            'Kegiatan Tambahan',
                            'Pengembangan Keprofesian Guru BK'
                        ] as $item)

                            <a
                                href="{{ route('dukungan-sistem.index', ['jenis' => $item]) }}"
                                class="block rounded-lg px-4 py-2.5 text-sm
                                {{ request('jenis') === $item
                                    ? 'bg-blue-600 text-white'
                                    : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                            >
                                {{ $item }}
                            </a>

                        @endforeach

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- 6. ARSIP SURAT --}}
                {{-- ================================================= --}}

                <div
                    x-data="{
                        open: {{ request()->routeIs('arsip-surat.*') ? 'true' : 'false' }}
                    }"
                >

                    <button
                        type="button"
                        @click="open = !open"
                        class="flex min-h-11 w-full items-center justify-between rounded-xl px-3.5 py-3 text-left text-sm font-semibold text-slate-200 hover:bg-white/5 sm:px-4"
                    >

                        <span class="flex items-center gap-3">

                            <svg
                                class="h-5 w-5 text-pink-400"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1zm3 4h8M8 12h8M8 16h5"
                                />
                            </svg>

                            ARSIP SURAT

                        </span>

                        <svg
                            class="h-4 w-4 transition-transform"
                            :class="open ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>

                    </button>


                    <div
                        x-show="open"
                        x-collapse
                        class="mt-1 space-y-1 pl-2 sm:pl-3"
                    >

                        @foreach([
                            'SP 1',
                            'SP 2',
                            'SP 3',
                            'Pemanggilan Orang Tua',
                            'Home Visit',
                            'Pengunduran Diri',
                            'Peringatan'
                        ] as $item)

                            <a
                                href="{{ route('arsip-surat.index', ['jenis' => $item]) }}"
                                class="block rounded-lg px-4 py-2.5 text-sm
                                {{ request('jenis') === $item && request()->routeIs('arsip-surat.*')
                                    ? 'bg-blue-600 text-white'
                                    : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                            >
                                {{ $item }}
                            </a>

                        @endforeach

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- 7. LAPORAN --}}
                {{-- ================================================= --}}

                <div
                    x-data="{
                        open: {{ request()->routeIs('laporan.*') ? 'true' : 'false' }}
                    }"
                >

                    <button
                        type="button"
                        @click="open = !open"
                        class="flex min-h-11 w-full items-center justify-between rounded-xl px-3.5 py-3 text-left text-sm font-semibold text-slate-200 hover:bg-white/5 sm:px-4"
                    >

                        <span class="flex items-center gap-3">

                            <svg
                                class="h-5 w-5 text-indigo-400"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 3h9l3 3v15H6V3zm9 0v4h4M9 12h6M9 16h6"
                                />
                            </svg>

                            LAPORAN

                        </span>

                        <svg
                            class="h-4 w-4 transition-transform"
                            :class="open ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>

                    </button>


                    <div
                        x-show="open"
                        x-collapse
                        class="mt-1 space-y-1 pl-2 sm:pl-3"
                    >

                        <a
                            href="{{ route('laporan.komponen') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request()->routeIs('laporan.komponen')
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Rekapan per Komponen
                        </a>


                        <a
                            href="{{ route('laporan.kegiatan') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request()->routeIs('laporan.kegiatan')
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Laporan Kegiatan BK
                        </a>


                        <a
                            href="{{ route('laporan.perkembangan') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm
                            {{ request()->routeIs('laporan.perkembangan')
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                        >
                            Laporan Perkembangan Siswa
                        </a>

                    </div>

                </div>


            </nav>


            {{-- USER --}}
            <div class="mt-4 border-t border-white/10 pt-4 sm:mt-6">

                <div class="rounded-xl bg-white/5 p-3">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-bold"
                        >
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="truncate text-sm font-semibold text-white">
                                {{ Auth::user()->name }}
                            </p>

                            <p class="truncate text-xs text-slate-400">
                                {{ Auth::user()->email }}
                            </p>

                        </div>

                    </div>


                    <div class="mt-3 grid grid-cols-2 gap-2">

                        <a
                            href="{{ route('profile.edit') }}"
                            class="flex-1 rounded-lg bg-white/5 px-3 py-2 text-center text-xs text-slate-300 hover:bg-white/10 hover:text-white"
                        >
                            Profil
                        </a>


                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                            class="flex-1"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-lg bg-red-500/10 px-3 py-2 text-xs text-red-300 hover:bg-red-500/20"
                            >
                                Keluar
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </aside>


    {{-- ================================================= --}}
    {{-- MAIN AREA --}}
    {{-- ================================================= --}}

    <div class="min-w-0 lg:pl-[290px]">


        {{-- TOPBAR --}}
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">

            <div class="flex min-h-16 items-center justify-between gap-2 px-3 py-2 sm:px-6 sm:py-0">

                <div class="flex items-center gap-3">

                    {{-- MOBILE MENU --}}
                    <button
                        type="button"
                        @click="sidebarOpen = !sidebarOpen"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 active:bg-slate-200 lg:hidden"
                    >

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>

                    </button>


                    <div>

                        <p class="text-xs font-medium text-slate-400">
                            Sistem Informasi
                        </p>

                        <h2 class="truncate text-base font-bold text-slate-800 sm:text-lg">
                            Bimbingan & Konseling
                        </h2>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TAHUN AJARAN -- ADMIN + GURU BK --}}
                {{-- ================================================= --}}
                @if(in_array(auth()->user()->role?->name, ['Admin', 'Guru BK'], true))
                    <div x-data="{ tahunAjaranOpen: false }" class="relative">
                        <button
                            type="button"
                            @click="tahunAjaranOpen = !tahunAjaranOpen"
                            @click.outside="tahunAjaranOpen = false"
                            class="flex max-w-[220px] items-center gap-2 rounded-xl bg-slate-100 px-3 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-200 sm:max-w-none"
                        >
                            <svg class="h-4 w-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4m8-4v4M4 9h16M5 5h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                            </svg>
                            <span class="max-w-[130px] truncate sm:max-w-[160px]">
                                {{ $tahunAjaranAktif?->nama ?? 'Tahun Ajaran -' }}
                            </span>
                            <svg class="h-4 w-4 shrink-0 transition-transform" :class="tahunAjaranOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="tahunAjaranOpen" x-transition style="display: none;" class="absolute right-0 top-full z-50 mt-2 w-64 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pilih Tahun Ajaran</p>
                            </div>

                            <div class="max-h-64 overflow-y-auto p-2">
                                @forelse($daftarTahunAjaran ?? [] as $ta)
                                    <form method="POST" action="{{ route('tahun-ajaran.pilih') }}">
                                        @csrf
                                        <input type="hidden" name="tahun_ajaran_id" value="{{ $ta->id }}">
                                        <button type="submit" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm transition {{ $tahunAjaranAktif?->id === $ta->id ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-600 hover:bg-slate-50' }}">
                                            <span>{{ $ta->nama }}</span>
                                            @if($tahunAjaranAktif?->id === $ta->id)
                                                <span class="text-xs">✓ Aktif</span>
                                            @endif
                                        </button>
                                    </form>
                                @empty
                                    <p class="px-3 py-3 text-sm text-slate-400">Belum ada tahun ajaran.</p>
                                @endforelse
                            </div>

                            <div class="border-t border-slate-100 p-2">
                                <a href="{{ route('tahun-ajaran.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold text-blue-600 hover:bg-blue-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                                    </svg>
                                    Kelola Tahun Ajaran
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex max-w-[180px] items-center gap-2 rounded-xl bg-slate-100 px-3 py-2.5 text-sm font-semibold text-slate-600">
                        <svg class="h-4 w-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4m8-4v4M4 9h16M5 5h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                        </svg>
                        <span class="truncate">{{ $tahunAjaranAktif?->nama ?? 'Tahun Ajaran -' }}</span>
                    </div>
                @endif

            </div>

        </header>


        {{-- PAGE CONTENT --}}
        <main class="min-h-[calc(100vh-64px)] min-w-0 overflow-x-auto p-3 sm:p-4 md:p-6">

            {{-- 
                Layout ini mendukung 2 cara pemanggilan:
                1. <x-app-layout> ... </x-app-layout>  -> menggunakan $slot
                2. @extends('layouts.app') + @section('content') -> menggunakan @yield('content')
            --}}
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset

        </main>

    </div>

</div>

</body>
</html>