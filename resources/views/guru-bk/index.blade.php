@extends('layouts.app')

@section('title', 'Data Guru BK')

@section('content')
<div class="min-h-screen bg-slate-100 px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-7xl">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Manajemen Data
                </p>

                <h1 class="text-2xl font-bold text-slate-800 sm:text-3xl">
                    Data Guru BK
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola data Guru Bimbingan dan Konseling.
                </p>
            </div>

            <a
                href="{{ route('guru-bk.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mr-2 h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                Tambah Guru BK
            </a>

        </div>

        {{-- PESAN SUKSES --}}
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- PESAN ERROR --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FILTER --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <form
                method="GET"
                action="{{ route('guru-bk.index') }}"
                class="flex flex-col gap-3 sm:flex-row"
            >

                <div class="flex-1">
                    <label
                        for="search"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Cari Guru BK
                    </label>

                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama, NIP, nomor HP, atau jabatan..."
                        class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-slate-500 focus:ring-slate-500"
                    >
                </div>

                <div class="flex items-end gap-2">

                    <button
                        type="submit"
                        class="rounded-xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('guru-bk.index') }}"
                        class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

        {{-- TABEL --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px] text-left text-sm">

                    <thead class="bg-slate-800 text-white">

                        <tr>
                            <th class="px-5 py-4 font-semibold">
                                No
                            </th>

                            <th class="px-5 py-4 font-semibold">
                                Nama Lengkap
                            </th>

                            <th class="px-5 py-4 font-semibold">
                                NIP
                            </th>

                            <th class="px-5 py-4 font-semibold">
                                Email
                            </th>

                            <th class="px-5 py-4 font-semibold">
                                No. HP
                            </th>

                            <th class="px-5 py-4 font-semibold">
                                Jabatan
                            </th>

                            <th class="px-5 py-4 font-semibold">
                                Status
                            </th>

                            <th class="px-5 py-4 text-center font-semibold">
                                Aksi
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($guruBK as $index => $guru)

                            <tr class="transition hover:bg-slate-50">

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $guruBK->firstItem() + $index }}
                                </td>

                                <td class="px-5 py-4">

                                    <div class="font-semibold text-slate-800">
                                        {{ $guru->nama_lengkap }}
                                    </div>

                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $guru->nip ?: '-' }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $guru->user?->email ?: '-' }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $guru->no_hp ?: '-' }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $guru->jabatan ?: '-' }}
                                </td>

                                <td class="px-5 py-4">

                                    @if($guru->status === 'aktif')

                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Aktif
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Nonaktif
                                        </span>

                                    @endif

                                </td>

                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-center gap-2">

                                        <a
                                            href="{{ route('guru-bk.show', $guru) }}"
                                            class="rounded-lg bg-blue-100 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-200"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="{{ route('guru-bk.edit', $guru) }}"
                                            class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-200"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('guru-bk.destroy', $guru) }}"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Guru BK ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg bg-red-100 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-200"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="8"
                                    class="px-5 py-12 text-center text-slate-500"
                                >
                                    Belum ada data Guru BK.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($guruBK->hasPages())

                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $guruBK->links() }}
                </div>

            @endif

        </div>

    </div>

</div>
@endsection