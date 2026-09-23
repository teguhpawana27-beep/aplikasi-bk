@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    <div>

        <a
            href="{{ route('layanan-dasar.index') }}"
            class="text-sm font-medium text-slate-500 hover:text-slate-800"
        >
            ← Kembali
        </a>

        <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Detail Layanan Dasar
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $layananDasar->topik }}
                </p>
            </div>

            <a
                href="{{ route('layanan-dasar.edit', $layananDasar) }}"
                class="inline-flex justify-center rounded-lg bg-slate-800 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700"
            >
                Edit Data
            </a>

        </div>

    </div>


    <div class="rounded-xl bg-white p-6 shadow-sm">

        <div class="grid gap-6 md:grid-cols-2">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Jenis Layanan
                </p>

                <p class="mt-1 font-semibold text-gray-800">
                    {{ $layananDasar->jenis_layanan }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Tanggal
                </p>

                <p class="mt-1 text-gray-800">
                    {{ $layananDasar->tanggal?->format('d F Y') }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Guru BK
                </p>

                <p class="mt-1 text-gray-800">
                    {{ $layananDasar->guruBK?->nama_lengkap ?? '-' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Tahun Ajaran
                </p>

                <p class="mt-1 text-gray-800">
                    {{ $layananDasar->tahunAjaran?->nama ?? '-' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Kelas
                </p>

                <p class="mt-1 text-gray-800">
                    {{ $layananDasar->kelas?->nama_kelas ?? '-' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Metode BK
                </p>

                <p class="mt-1 text-gray-800">
                    {{ $layananDasar->metode?->nama ?? '-' }}
                </p>
            </div>


            <div class="md:col-span-2">

                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    SKKPD
                </p>

                <p class="mt-1 text-gray-800">
                    {{ $layananDasar->skkpd?->kode }}
                    —
                    {{ $layananDasar->skkpd?->nama }}
                </p>

            </div>

        </div>

    </div>


    @foreach ([
        'Topik' => $layananDasar->topik,
        'Sasaran' => $layananDasar->sasaran,
        'Uraian Kegiatan' => $layananDasar->uraian_kegiatan,
        'Hasil' => $layananDasar->hasil,
        'Evaluasi' => $layananDasar->evaluasi,
        'Keterangan' => $layananDasar->keterangan,
    ] as $judul => $isi)

        @if ($isi)

            <div class="rounded-xl bg-white p-6 shadow-sm">

                <h3 class="text-base font-semibold text-gray-800">
                    {{ $judul }}
                </h3>

                <div class="mt-3 whitespace-pre-line text-sm leading-7 text-gray-600">
                    {{ $isi }}
                </div>

            </div>

        @endif

    @endforeach


    {{-- PESERTA --}}
    <div class="rounded-xl bg-white p-6 shadow-sm">

        <h3 class="text-base font-semibold text-gray-800">
            Peserta Layanan
        </h3>

        <div class="mt-4">

            @forelse ($layananDasar->peserta as $peserta)

                <div class="border-b border-gray-100 py-3 last:border-0">

                    <p class="font-medium text-gray-800">
                        {{ $peserta->siswa?->nama_lengkap }}
                    </p>

                    <p class="text-xs text-gray-400">
                        NIS: {{ $peserta->siswa?->nis }}
                    </p>

                </div>

            @empty

                <p class="text-sm text-gray-500">
                    Belum ada peserta yang dicatat.
                </p>

            @endforelse

        </div>

    </div>

</div>

@endsection