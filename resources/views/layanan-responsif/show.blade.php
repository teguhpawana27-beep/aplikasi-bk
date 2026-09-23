@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-6">

        <a
            href="{{ route('layanan-responsif.index') }}"
            class="inline-flex items-center gap-2
                   text-sm text-slate-500
                   hover:text-blue-600 mb-4"
        >
            ← Kembali
        </a>

        <div class="flex flex-col md:flex-row
                    md:items-center md:justify-between gap-4">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Detail Layanan Responsif
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Informasi lengkap pelaksanaan layanan responsif BK.
                </p>

            </div>

            <a
                href="{{ route('layanan-responsif.edit', $layananResponsif->id) }}"
                class="inline-flex items-center justify-center
                       rounded-xl bg-blue-600 px-5 py-3
                       text-sm font-semibold text-white
                       hover:bg-blue-700 transition"
            >
                Edit Data
            </a>

        </div>

    </div>


    {{-- DATA UTAMA --}}
    <div class="space-y-6">


        {{-- DATA PELAKSANAAN --}}
        <div class="bg-white rounded-2xl
                    border border-slate-200
                    shadow-sm p-6">

            <h2 class="text-lg font-semibold text-slate-800 mb-5">
                Data Pelaksanaan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                {{-- GURU BK --}}
                <div>

                    <p class="text-xs font-medium
                              uppercase tracking-wide
                              text-slate-400 mb-1">
                        Guru BK
                    </p>

                    <p class="font-medium text-slate-800">
                        {{ $layananResponsif->guruBK->nama_lengkap ?? '-' }}
                    </p>

                </div>


                {{-- TAHUN AJARAN --}}
                <div>

                    <p class="text-xs font-medium
                              uppercase tracking-wide
                              text-slate-400 mb-1">
                        Tahun Ajaran
                    </p>

                    <p class="font-medium text-slate-800">
                        {{ $layananResponsif->tahunAjaran->nama ?? '-' }}
                    </p>

                </div>


                {{-- TINGKAT --}}
                <div>

                    <p class="text-xs font-medium
                              uppercase tracking-wide
                              text-slate-400 mb-1">
                        Tingkat
                    </p>

                    <p class="font-medium text-slate-800">
                        Kelas {{ $layananResponsif->tingkat }}
                    </p>

                </div>


                {{-- KELAS --}}
                <div>

                    <p class="text-xs font-medium
                              uppercase tracking-wide
                              text-slate-400 mb-1">
                        Kelas
                    </p>

                    <p class="font-medium text-slate-800">
                        {{ $layananResponsif->kelas->nama_kelas ?? '-' }}
                    </p>

                </div>


                {{-- TANGGAL --}}
                <div>

                    <p class="text-xs font-medium
                              uppercase tracking-wide
                              text-slate-400 mb-1">
                        Tanggal
                    </p>

                    <p class="font-medium text-slate-800">
                        {{ $layananResponsif->tanggal
                            ? $layananResponsif->tanggal->format('d F Y')
                            : '-' }}
                    </p>

                </div>


                {{-- JENIS --}}
                <div>

                    <p class="text-xs font-medium
                              uppercase tracking-wide
                              text-slate-400 mb-1">
                        Jenis Layanan
                    </p>

                    <span class="inline-flex rounded-lg
                                 bg-blue-50 px-3 py-1.5
                                 text-sm font-semibold text-blue-700">

                        {{ $layananResponsif->jenis_layanan }}

                    </span>

                </div>


                {{-- BIDANG --}}
                <div>

                    <p class="text-xs font-medium
                              uppercase tracking-wide
                              text-slate-400 mb-1">
                        Bidang Layanan
                    </p>

                    <p class="font-medium text-slate-800">
                        {{ $layananResponsif->bidangLayanan->nama ?? '-' }}
                    </p>

                </div>


                {{-- PENDEKATAN --}}
                <div>

                    <p class="text-xs font-medium
                              uppercase tracking-wide
                              text-slate-400 mb-1">
                        Pendekatan
                    </p>

                    <p class="font-medium text-slate-800">
                        {{ $layananResponsif->pendekatan->nama ?? '-' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- DATA SISWA --}}
        <div class="bg-white rounded-2xl
                    border border-slate-200
                    shadow-sm p-6">

            <h2 class="text-lg font-semibold text-slate-800 mb-5">
                Nama Siswa / Peserta
            </h2>

            <div class="space-y-3">

                @forelse($layananResponsif->peserta as $peserta)

                    <div class="flex items-center justify-between
                                rounded-xl bg-slate-50
                                border border-slate-100
                                px-4 py-3">

                        <div>

                            <p class="font-medium text-slate-800">
                                {{ $peserta->siswa->nama_lengkap ?? '-' }}
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                NIS:
                                {{ $peserta->siswa->nis ?? '-' }}
                            </p>

                        </div>

                        @if($peserta->peran)

                            <span class="rounded-lg bg-white
                                         border border-slate-200
                                         px-3 py-1.5
                                         text-xs text-slate-600">
                                {{ $peserta->peran }}
                            </span>

                        @endif

                    </div>

                @empty

                    <p class="text-sm text-slate-400">
                        Belum ada peserta.
                    </p>

                @endforelse

            </div>

        </div>


        {{-- URAIAN MASALAH --}}
        <div class="bg-white rounded-2xl
                    border border-slate-200
                    shadow-sm p-6">

            <h2 class="text-lg font-semibold text-slate-800 mb-4">
                Uraian Masalah
            </h2>

            <div class="rounded-xl bg-slate-50
                        border border-slate-100
                        p-5 text-sm leading-7
                        text-slate-700 whitespace-pre-line">

                {{ $layananResponsif->uraian_masalah ?: '-' }}

            </div>

        </div>


        {{-- TINDAK LANJUT --}}
        <div class="bg-white rounded-2xl
                    border border-slate-200
                    shadow-sm p-6">

            <h2 class="text-lg font-semibold text-slate-800 mb-4">
                Tindak Lanjut
            </h2>

            <div class="rounded-xl bg-slate-50
                        border border-slate-100
                        p-5 text-sm leading-7
                        text-slate-700 whitespace-pre-line">

                {{ $layananResponsif->tindak_lanjut ?: '-' }}

            </div>

        </div>


        {{-- KETERANGAN & STATUS --}}
        <div class="bg-white rounded-2xl
                    border border-slate-200
                    shadow-sm p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                {{-- KETERANGAN --}}
                <div>

                    <p class="text-xs font-medium
                              uppercase tracking-wide
                              text-slate-400 mb-2">
                        Keterangan
                    </p>

                    <div class="rounded-xl bg-slate-50
                                border border-slate-100
                                p-4 text-sm leading-6
                                text-slate-700 whitespace-pre-line">

                        {{ $layananResponsif->keterangan ?: '-' }}

                    </div>

                </div>


                {{-- STATUS --}}
                <div>

                    <p class="text-xs font-medium
                              uppercase tracking-wide
                              text-slate-400 mb-2">
                        Status Kasus
                    </p>

                    @if($layananResponsif->status_kasus === 'Aktif')

                        <span class="inline-flex rounded-full
                                     bg-amber-100 px-4 py-2
                                     text-sm font-semibold
                                     text-amber-700">
                            Aktif
                        </span>

                    @elseif($layananResponsif->status_kasus === 'Selesai')

                        <span class="inline-flex rounded-full
                                     bg-emerald-100 px-4 py-2
                                     text-sm font-semibold
                                     text-emerald-700">
                            Selesai
                        </span>

                    @else

                        <span class="inline-flex rounded-full
                                     bg-slate-100 px-4 py-2
                                     text-sm font-semibold
                                     text-slate-600">
                            {{ $layananResponsif->status_kasus }}
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- FOOTER ACTION --}}
        <div class="flex justify-between items-center pt-2 pb-6">

            <a
                href="{{ route('layanan-responsif.index') }}"
                class="rounded-xl border border-slate-300
                       px-5 py-3 text-sm font-semibold
                       text-slate-600 hover:bg-slate-50"
            >
                ← Kembali ke Daftar
            </a>

            <form
                action="{{ route('layanan-responsif.destroy', $layananResponsif->id) }}"
                method="POST"
                onsubmit="return confirm('Yakin ingin menghapus data ini?')"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="rounded-xl bg-red-50
                           px-5 py-3 text-sm font-semibold
                           text-red-600 hover:bg-red-100"
                >
                    Hapus Data
                </button>

            </form>

        </div>

    </div>

</div>

@endsection