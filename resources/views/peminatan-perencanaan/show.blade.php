@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Detail Peminatan & Perencanaan Individu
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Detail data kegiatan peminatan dan perencanaan individu.
            </p>
        </div>

        <div class="flex items-center gap-3">

            <a href="{{ route('peminatan-perencanaan.edit', $peminatan) }}"
               class="inline-flex items-center justify-center
                      px-4 py-2.5
                      bg-indigo-600 text-white
                      text-sm font-semibold
                      rounded-lg
                      hover:bg-indigo-700 transition">
                Edit
            </a>

            <a href="{{ route('peminatan-perencanaan.index', ['jenis' => $peminatan->jenis_layanan]) }}"
               class="inline-flex items-center justify-center
                      px-4 py-2.5
                      border border-gray-300
                      bg-white text-gray-700
                      text-sm font-semibold
                      rounded-lg
                      hover:bg-gray-50 transition">
                ← Kembali
            </a>

        </div>

    </div>


    {{-- INFORMASI UTAMA --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-8 py-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">
                Informasi Kegiatan
            </h2>
        </div>

        <div class="p-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                {{-- GURU BK --}}
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">
                        Guru BK
                    </div>

                    <div class="text-base font-semibold text-gray-800">
                        {{ $peminatan->guruBK->nama_lengkap ?? '-' }}
                    </div>
                </div>


                {{-- TAHUN AJARAN --}}
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">
                        Tahun Ajaran
                    </div>

                    <div class="text-base font-semibold text-gray-800">
                        {{ $peminatan->tahunAjaran->nama ?? '-' }}
                    </div>
                </div>


                {{-- TINGKAT --}}
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">
                        Tingkat
                    </div>

                    <div class="text-base font-semibold text-gray-800">
                        {{ $peminatan->kelas->tingkat ?? '-' }}
                    </div>
                </div>


                {{-- JURUSAN --}}
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">
                        Jurusan
                    </div>

                    <div class="text-base font-semibold text-gray-800">
                        {{ $peminatan->kelas->jurusan->kode ?? '-' }}

                        @if($peminatan->kelas?->jurusan?->nama)
                            - {{ $peminatan->kelas->jurusan->nama }}
                        @endif
                    </div>
                </div>


                {{-- KELAS --}}
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">
                        Kelas
                    </div>

                    <div class="text-base font-semibold text-gray-800">
                        {{ $peminatan->kelas->nama_kelas ?? '-' }}
                    </div>
                </div>


                {{-- BIDANG LAYANAN --}}
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">
                        Bidang Layanan
                    </div>

                    <div class="text-base font-semibold text-gray-800">
                        {{ $peminatan->bidangLayanan->nama ?? '-' }}
                    </div>
                </div>


                {{-- JENIS LAYANAN --}}
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">
                        Jenis Layanan
                    </div>

                    <div class="text-base font-semibold text-gray-800">
                        {{ $peminatan->jenis_layanan ?? '-' }}
                    </div>
                </div>


                {{-- TANGGAL --}}
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">
                        Tanggal
                    </div>

                    <div class="text-base font-semibold text-gray-800">
                        {{ $peminatan->tanggal?->format('d-m-Y') ?? '-' }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- DATA SISWA --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-8 py-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">
                Siswa
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Daftar siswa yang terkait dengan kegiatan ini.
            </p>
        </div>


        @if($peminatan->peserta && $peminatan->peserta->count())

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-gray-50 border-b border-gray-200">

                        <tr>

                            <th class="px-6 py-4 font-semibold text-gray-600">
                                NO
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-600">
                                NAMA SISWA
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-600">
                                NIS
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @foreach($peminatan->peserta as $peserta)

                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-5 text-gray-600">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-5">
                                    <div class="font-semibold text-gray-800">
                                        {{ $peserta->siswa->nama_lengkap ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-gray-600">
                                    {{ $peserta->siswa->nis ?? '-' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="text-center py-12 px-6">

                <div class="text-4xl mb-3">
                    👤
                </div>

                <h3 class="text-base font-semibold text-gray-800">
                    Belum Ada Siswa
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Belum ada siswa yang dipilih pada kegiatan ini.
                </p>

            </div>

        @endif

    </div>


    {{-- DETAIL KEGIATAN --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-8 py-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">
                Detail Kegiatan
            </h2>
        </div>

        <div class="p-8 space-y-6">

            {{-- SASARAN --}}
            <div>

                <div class="text-sm font-medium text-gray-500 mb-2">
                    Sasaran
                </div>

                <div class="text-gray-800 whitespace-pre-line">
                    {{ $peminatan->sasaran ?: '-' }}
                </div>

            </div>


            {{-- URAIAN KEGIATAN --}}
            <div>

                <div class="text-sm font-medium text-gray-500 mb-2">
                    Uraian Kegiatan
                </div>

                <div class="text-gray-800 whitespace-pre-line">
                    {{ $peminatan->uraian_kegiatan ?: '-' }}
                </div>

            </div>


            {{-- TINDAK LANJUT --}}
            <div>

                <div class="text-sm font-medium text-gray-500 mb-2">
                    Tindak Lanjut
                </div>

                <div class="text-gray-800 whitespace-pre-line">
                    {{ $peminatan->tindak_lanjut ?: '-' }}
                </div>

            </div>


            {{-- KETERANGAN --}}
            <div>

                <div class="text-sm font-medium text-gray-500 mb-2">
                    Keterangan
                </div>

                <div class="text-gray-800 whitespace-pre-line">
                    {{ $peminatan->keterangan ?: '-' }}
                </div>

            </div>

        </div>

    </div>


    {{-- FOOTER ACTION --}}
    <div class="flex flex-wrap items-center gap-3">

        <a href="{{ route('peminatan-perencanaan.edit', $peminatan) }}"
           class="inline-flex items-center justify-center
                  px-4 py-2.5
                  bg-indigo-600 text-white
                  text-sm font-semibold
                  rounded-lg
                  hover:bg-indigo-700 transition">
            Edit Data
        </a>

        <a href="{{ route('peminatan-perencanaan.index', ['jenis' => $peminatan->jenis_layanan]) }}"
           class="inline-flex items-center justify-center
                  px-4 py-2.5
                  border border-gray-300
                  bg-white text-gray-700
                  text-sm font-semibold
                  rounded-lg
                  hover:bg-gray-50 transition">
            ← Kembali
        </a>

    </div>

</div>

@endsection