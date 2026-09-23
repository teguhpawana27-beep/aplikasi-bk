@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-50 px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-5xl">


        {{-- HEADER --}}
        <div class="mb-6">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <h1 class="text-2xl font-bold text-gray-900">
                        Detail Arsip Surat
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Informasi lengkap arsip surat.
                    </p>

                </div>


                <div class="flex flex-wrap gap-2">


                    <a
                        href="{{ route('arsip-surat.index') }}"
                        class="rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Kembali
                    </a>


                    <a
                        href="{{ route('arsip-surat.edit', $arsipSurat->id) }}"
                        class="rounded-xl bg-yellow-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-yellow-600"
                    >
                        Edit
                    </a>


                    <a
                        href="{{ route('arsip-surat.pdf', $arsipSurat->id) }}"
                        target="_blank"
                        class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700"
                    >
                        Cetak PDF
                    </a>

                </div>

            </div>

        </div>


        {{-- DETAIL SURAT --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-6 py-5">

                <h2 class="text-lg font-bold text-gray-900">
                    Informasi Surat
                </h2>

            </div>


            <div class="grid grid-cols-1 divide-y divide-gray-100 md:grid-cols-2 md:divide-y-0">


                {{-- JENIS --}}
                <div class="border-b border-gray-100 p-6 md:border-r">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Jenis Surat
                    </div>

                    <div class="mt-2 text-sm font-semibold text-gray-800">
                        {{ $arsipSurat->jenis_surat ?? '-' }}
                    </div>
                </div>


                {{-- NOMOR --}}
                <div class="border-b border-gray-100 p-6">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Nomor Surat
                    </div>

                    <div class="mt-2 text-sm font-semibold text-gray-800">
                        {{ $arsipSurat->nomor_surat ?? '-' }}
                    </div>
                </div>


                {{-- TANGGAL --}}
                <div class="border-b border-gray-100 p-6 md:border-r">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Tanggal Surat
                    </div>

                    <div class="mt-2 text-sm font-semibold text-gray-800">

                        @if ($arsipSurat->tanggal_surat)

                            {{ \Carbon\Carbon::parse($arsipSurat->tanggal_surat)->format('d F Y') }}

                        @else

                            -

                        @endif

                    </div>
                </div>


                {{-- TAHUN AJARAN --}}
                <div class="border-b border-gray-100 p-6">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Tahun Ajaran
                    </div>

                    <div class="mt-2 text-sm font-semibold text-gray-800">
                        {{ $arsipSurat->tahunAjaran->nama ?? $arsipSurat->tahunAjaran->tahun ?? $arsipSurat->tahunAjaran->tahun_ajaran ?? '-' }}
                    </div>
                </div>


                {{-- SISWA --}}
                <div class="border-b border-gray-100 p-6 md:border-r">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Siswa
                    </div>

                    <div class="mt-2 text-sm font-semibold text-gray-800">
                        {{ $arsipSurat->siswa->nama_lengkap ?? '-' }}
                    </div>

                    @if ($arsipSurat->siswa)

                        <div class="mt-1 text-xs text-gray-500">
                            NIS: {{ $arsipSurat->siswa->nis }}
                        </div>

                    @endif

                </div>


                {{-- GURU BK --}}
                <div class="border-b border-gray-100 p-6">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Guru BK
                    </div>

                    <div class="mt-2 text-sm font-semibold text-gray-800">
                        {{ $arsipSurat->guruBK->nama_lengkap ?? '-' }}
                    </div>
                </div>


                {{-- PERIHAL --}}
                <div class="border-b border-gray-100 p-6 md:col-span-2">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Perihal
                    </div>

                    <div class="mt-2 text-sm font-semibold text-gray-800">
                        {{ $arsipSurat->perihal ?? '-' }}
                    </div>
                </div>

            </div>

        </div>


        {{-- ISI RINGKAS --}}
        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Isi Ringkas
            </h2>

            <div class="mt-4 rounded-xl bg-gray-50 p-5 text-sm leading-7 text-gray-700">

                {!! nl2br(e($arsipSurat->isi_ringkas ?? '-')) !!}

            </div>

        </div>


        {{-- FILE --}}
        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                File Surat
            </h2>

            <div class="mt-4">

                @if ($arsipSurat->file_path)

                    <a
                        href="{{ route('arsip-surat.file', $arsipSurat->id) }}"
                        target="_blank"
                        class="inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        Buka File Surat
                    </a>

                @else

                    <div class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">
                        File surat belum tersedia.
                    </div>

                @endif

            </div>

        </div>


        {{-- KETERANGAN --}}
        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Keterangan
            </h2>

            <div class="mt-4 rounded-xl bg-gray-50 p-5 text-sm leading-7 text-gray-700">

                {!! nl2br(e($arsipSurat->keterangan ?? '-')) !!}

            </div>

        </div>


        {{-- DELETE --}}
        <div class="mt-6 flex justify-end">

            <form
                action="{{ route('arsip-surat.destroy', $arsipSurat->id) }}"
                method="POST"
                onsubmit="return confirm('Yakin ingin menghapus arsip surat ini?')"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700"
                >
                    Hapus Arsip Surat
                </button>

            </form>

        </div>

    </div>

</div>

@endsection