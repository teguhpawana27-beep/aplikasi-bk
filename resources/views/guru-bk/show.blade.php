@extends('layouts.app')

@section('title', 'Detail Guru BK')

@section('content')
<div class="min-h-screen bg-slate-100 px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-4xl">

        <div class="mb-6">

            <a
                href="{{ route('guru-bk.index') }}"
                class="text-sm font-medium text-slate-500 hover:text-slate-800"
            >
                ← Kembali ke Data Guru BK
            </a>

            <h1 class="mt-4 text-2xl font-bold text-slate-800 sm:text-3xl">
                Detail Guru BK
            </h1>

        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="grid gap-5 md:grid-cols-2">

                <div>
                    <p class="text-sm text-slate-500">
                        Nama Lengkap
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $guruBK->nama_lengkap }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        NIP
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $guruBK->nip ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Email
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $guruBK->user?->email ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Nomor HP
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $guruBK->no_hp ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Jabatan
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $guruBK->jabatan ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Status
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ ucfirst($guruBK->status) }}
                    </p>
                </div>

            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">

                <a
                    href="{{ route('guru-bk.edit', $guruBK) }}"
                    class="rounded-xl bg-amber-500 px-5 py-3 text-center text-sm font-semibold text-white hover:bg-amber-600"
                >
                    Edit Data
                </a>

                <a
                    href="{{ route('guru-bk.index') }}"
                    class="rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Kembali
                </a>

            </div>

        </div>

    </div>

</div>
@endsection