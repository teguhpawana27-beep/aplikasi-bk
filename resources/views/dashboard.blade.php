@extends('layouts.app')

@section('content')

{{-- =========================
     HEADER DASHBOARD
========================= --}}
<div class="mb-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <p class="text-sm font-medium text-indigo-600">
                Sistem Informasi BK
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                Selamat Datang, {{ auth()->user()->name ?? 'Administrator' }} 👋
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                Kelola data siswa, layanan bimbingan dan konseling,
                serta administrasi BK SMKN 1 Majalaya dalam satu sistem.
            </p>
        </div>


        {{-- =========================
             PILIH TAHUN AJARAN
        ========================= --}}
        <div class="w-full sm:w-auto">

            <details class="relative">

                {{-- TOMBOL PILIH TAHUN AJARAN --}}
                <summary
                    class="flex w-full min-w-[285px] cursor-pointer list-none
                           items-center justify-between rounded-2xl
                           border border-slate-200 bg-white px-5 py-3.5
                           text-left shadow-sm transition
                           hover:border-indigo-300 hover:shadow-md
                           sm:w-[285px]"
                >

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                   rounded-xl bg-indigo-50 text-indigo-600"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"
                                />
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-slate-400">
                                Tahun Ajaran
                            </p>

                            <p class="text-sm font-semibold text-slate-700">
                                {{ $tahunAjaranAktif?->nama ?? 'Belum Dipilih' }}
                            </p>
                        </div>

                    </div>

                    <svg
                        class="h-5 w-5 text-slate-400"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m6 9 6 6 6-6"
                        />
                    </svg>

                </summary>


                {{-- =========================
                     DROPDOWN
                ========================= --}}
                <div
                    class="absolute right-0 z-50 mt-2 w-full min-w-[285px]
                           overflow-hidden rounded-2xl border border-slate-200
                           bg-white shadow-xl"
                >

                    {{-- =========================
                         DAFTAR TAHUN AJARAN
                    ========================= --}}
                    <div class="max-h-64 overflow-y-auto py-2">

                        @forelse ($daftarTahunAjaran as $ta)

                            <form
                                method="POST"
                                action="{{ route('tahun-ajaran.pilih') }}"
                            >

                                @csrf

                                <input
                                    type="hidden"
                                    name="tahun_ajaran_id"
                                    value="{{ $ta->id }}"
                                >

                                <button
                                    type="submit"
                                    class="flex w-full items-center justify-between
                                           px-5 py-3 text-left transition
                                           hover:bg-indigo-50"
                                >

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 items-center justify-center
                                                   rounded-lg
                                                   {{ $tahunAjaranAktif?->id == $ta->id
                                                        ? 'bg-indigo-100 text-indigo-600'
                                                        : 'bg-slate-100 text-slate-500' }}"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"
                                                />
                                            </svg>
                                        </div>

                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">
                                                {{ $ta->nama }}
                                            </p>

                                            <p class="text-xs text-slate-400">
                                                {{ $ta->tanggal_mulai?->format('d M Y') }}
                                                -
                                                {{ $ta->tanggal_selesai?->format('d M Y') }}
                                            </p>
                                        </div>

                                    </div>

                                    @if($tahunAjaranAktif?->id == $ta->id)

                                        <svg
                                            class="h-5 w-5 text-indigo-600"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="m5 12 4 4L19 6"
                                            />
                                        </svg>

                                    @endif

                                </button>

                            </form>

                        @empty

                            <div class="px-5 py-4 text-sm text-slate-500">
                                Belum ada tahun ajaran.
                            </div>

                        @endforelse

                    </div>


                    {{-- =========================
                         PEMBATAS
                    ========================= --}}
                    <div class="border-t border-slate-100"></div>


                    {{-- =========================
                         AKSI TAHUN AJARAN
                    ========================= --}}
                    <div class="p-2">

                        {{-- TAMBAH TAHUN AJARAN BARU --}}
                        <a
                            href="{{ route('tahun-ajaran.create') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3
                                   text-sm font-semibold text-indigo-600
                                   transition hover:bg-indigo-50"
                        >

                            <div
                                class="flex h-9 w-9 items-center justify-center
                                       rounded-lg bg-indigo-100 text-indigo-600"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 5v14M5 12h14"
                                    />
                                </svg>
                            </div>

                            <div>
                                <p>
                                    Tambah Tahun Ajaran Baru
                                </p>

                                <p class="text-xs font-normal text-slate-400">
                                    Buat periode tahun ajaran berikutnya
                                </p>
                            </div>

                        </a>


                        {{-- KELOLA TAHUN AJARAN --}}
                        <a
                            href="{{ route('tahun-ajaran.index') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3
                                   text-sm font-semibold text-slate-600
                                   transition hover:bg-slate-50"
                        >

                            <div
                                class="flex h-9 w-9 items-center justify-center
                                       rounded-lg bg-slate-100 text-slate-600"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-1.9 1.9-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1.02 1.55V22h-2.68v-.01a1.7 1.7 0 0 0-1.02-1.55 1.7 1.7 0 0 0-1.88.34l-.06.06-1.9-1.9.06-.06A1.7 1.7 0 0 0 7.8 15a1.7 1.7 0 0 0-1.55-1.02H6v-2.68h.25A1.7 1.7 0 0 0 7.8 10a1.7 1.7 0 0 0-.34-1.88L7.4 8.06l1.9-1.9.06.06A1.7 1.7 0 0 0 11.24 6.56 1.7 1.7 0 0 0 12.26 5V5h2.68v.01a1.7 1.7 0 0 0 1.02 1.55 1.7 1.7 0 0 0 1.88-.34l.06-.06 1.9 1.9-.06.06A1.7 1.7 0 0 0 19.4 10a1.7 1.7 0 0 0 1.55 1.02H21v2.68h-.05A1.7 1.7 0 0 0 19.4 15Z"
                                    />
                                </svg>
                            </div>

                            <div>
                                <p>
                                    Kelola Tahun Ajaran
                                </p>

                                <p class="text-xs font-normal text-slate-400">
                                    Edit atau hapus tahun ajaran
                                </p>
                            </div>

                        </a>

                    </div>

                </div>

            </details>

            <p class="mt-1.5 text-right text-[11px] text-slate-400">
                Klik untuk memilih atau mengelola tahun ajaran
            </p>

        </div>

    </div>
</div>



{{-- =========================
     STATISTIK
========================= --}}
<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

    {{-- Total Siswa --}}
    <div class="dashboard-card group">

        <div class="flex items-start justify-between">

            <div>
                <p class="dashboard-card-label">
                    Total Siswa
                </p>

                <h3 class="dashboard-card-number">
                    {{ number_format($totalSiswa) }}
                </h3>
            </div>

            <div class="dashboard-icon bg-indigo-50 text-indigo-600">
                <span>👥</span>
            </div>

        </div>

        <div class="mt-4 flex items-center gap-2">
            <span class="dashboard-status-dot bg-emerald-500"></span>

            <span class="text-xs text-slate-400">
                Data siswa terdaftar
            </span>
        </div>

    </div>


    {{-- Layanan BK --}}
    <div class="dashboard-card group">

        <div class="flex items-start justify-between">

            <div>
                <p class="dashboard-card-label">
                    Layanan BK
                </p>

                <h3 class="dashboard-card-number">
                    {{ number_format($layananBK) }}
                </h3>
            </div>

            <div class="dashboard-icon bg-sky-50 text-sky-600">
                <span>📋</span>
            </div>

        </div>

        <div class="mt-4 flex items-center gap-2">
            <span class="dashboard-status-dot bg-sky-500"></span>

            <span class="text-xs text-slate-400">
                Kegiatan layanan
            </span>
        </div>

    </div>


    {{-- Kasus Responsif --}}
    <div class="dashboard-card group">

        <div class="flex items-start justify-between">

            <div>
                <p class="dashboard-card-label">
                    Kasus Responsif
                </p>

                <h3 class="dashboard-card-number">
                    {{ number_format($kasusResponsif) }}
                </h3>
            </div>

            <div class="dashboard-icon bg-amber-50 text-amber-600">
                <span>⚠</span>
            </div>

        </div>

        <div class="mt-4 flex items-center gap-2">
            <span class="dashboard-status-dot bg-amber-500"></span>

            <span class="text-xs text-slate-400">
                Kasus yang tercatat
            </span>
        </div>

    </div>


    {{-- Program BK --}}
    <div class="dashboard-card group">

        <div class="flex items-start justify-between">

            <div>
                <p class="dashboard-card-label">
                    Program BK
                </p>

                <h3 class="dashboard-card-number">
                    {{ number_format($programBK) }}
                </h3>
            </div>

            <div class="dashboard-icon bg-emerald-50 text-emerald-600">
                <span>✓</span>
            </div>

        </div>

        <div class="mt-4 flex items-center gap-2">
            <span class="dashboard-status-dot bg-emerald-500"></span>

            <span class="text-xs text-slate-400">
                Program yang berjalan
            </span>
        </div>

    </div>

</div>



{{-- =========================
     AKSES CEPAT
========================= --}}
<div class="mt-7">

    <div class="mb-4">

        <h2 class="text-lg font-bold text-slate-800">
            Akses Cepat
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Akses beberapa bagian utama sistem BK.
        </p>

    </div>


    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Data Siswa --}}
        <a
            href="{{ route('siswa.index') }}"
            class="quick-action group"
        >

            <div class="quick-action-icon bg-indigo-50 text-indigo-600">
                👥
            </div>

            <div class="min-w-0">

                <h3 class="font-semibold text-slate-700 group-hover:text-indigo-600">
                    Data Siswa
                </h3>

                <p class="mt-1 text-xs text-slate-400">
                    Kelola data siswa
                </p>

            </div>

            <span class="quick-action-arrow">
                →
            </span>

        </a>


        {{-- Profil Konseli --}}
        <a
            href="{{ route('profil-konseli.index') }}"
            class="quick-action group"
        >

            <div class="quick-action-icon bg-violet-50 text-violet-600">
                👤
            </div>

            <div class="min-w-0">

                <h3 class="font-semibold text-slate-700 group-hover:text-violet-600">
                    Profil Konseli
                </h3>

                <p class="mt-1 text-xs text-slate-400">
                    Data profil konseli
                </p>

            </div>

            <span class="quick-action-arrow">
                →
            </span>

        </a>


        {{-- Data BMW --}}
        <a
            href="{{ route('data-bmw.index') }}"
            class="quick-action group"
        >

            <div class="quick-action-icon bg-sky-50 text-sky-600">
                📝
            </div>

            <div class="min-w-0">

                <h3 class="font-semibold text-slate-700 group-hover:text-sky-600">
                    Data BMW
                </h3>

                <p class="mt-1 text-xs text-slate-400">
                    Data siswa baru
                </p>

            </div>

            <span class="quick-action-arrow">
                →
            </span>

        </a>


        {{-- Asesmen Awal --}}
        <a
            href="{{ route('asesmen-awal.index') }}"
            class="quick-action group"
        >

            <div class="quick-action-icon bg-emerald-50 text-emerald-600">
                📊
            </div>

            <div class="min-w-0">

                <h3 class="font-semibold text-slate-700 group-hover:text-emerald-600">
                    Asesmen Awal
                </h3>

                <p class="mt-1 text-xs text-slate-400">
                    Hasil asesmen siswa
                </p>

            </div>

            <span class="quick-action-arrow">
                →
            </span>

        </a>

    </div>

</div>



{{-- =========================
     FOOTER NOTE
========================= --}}
<div class="mt-7 rounded-xl border border-indigo-100 bg-indigo-50/60 px-5 py-4">

    <div class="flex items-start gap-3">

        <div class="mt-0.5 text-indigo-600">
            ℹ
        </div>

        <div>

            <p class="text-sm font-semibold text-indigo-800">
                Sistem Informasi Bimbingan dan Konseling
            </p>

            <p class="mt-1 text-xs leading-5 text-indigo-600">
                SMKN 1 Majalaya — Digunakan untuk mendukung pengelolaan
                data, layanan, administrasi, dan pelaporan BK.
            </p>

        </div>

    </div>

</div>

@endsection