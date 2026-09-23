@extends('layouts.app')

@section('title', 'Tambah Guru BK')

@section('content')
<div class="min-h-screen bg-slate-100 px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-4xl">

        {{-- HEADER --}}
        <div class="mb-6">

            <a
                href="{{ route('guru-bk.index') }}"
                class="text-sm font-medium text-slate-500 hover:text-slate-800"
            >
                ← Kembali ke Data Guru BK
            </a>

            <h1 class="mt-4 text-2xl font-bold text-slate-800 sm:text-3xl">
                Tambah Guru BK
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Tambahkan data Guru BK dan akun login baru.
            </p>

        </div>

        {{-- ERROR --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        <form
            method="POST"
            action="{{ route('guru-bk.store') }}"
            class="space-y-6"
        >

            @csrf

            {{-- DATA PROFIL --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="mb-6">
                    <h2 class="text-lg font-bold text-slate-800">
                        Data Profil Guru BK
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Isi informasi dasar Guru Bimbingan dan Konseling.
                    </p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">

                    {{-- NAMA --}}
                    <div class="md:col-span-2">

                        <label
                            for="nama_lengkap"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama_lengkap"
                            id="nama_lengkap"
                            value="{{ old('nama_lengkap') }}"
                            required
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-slate-500 focus:ring-slate-500"
                            placeholder="Masukkan nama lengkap Guru BK"
                        >

                    </div>

                    {{-- NIP --}}
                    <div>

                        <label
                            for="nip"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            NIP
                        </label>

                        <input
                            type="text"
                            name="nip"
                            id="nip"
                            value="{{ old('nip') }}"
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-slate-500 focus:ring-slate-500"
                            placeholder="Masukkan NIP"
                        >

                    </div>

                    {{-- NO HP --}}
                    <div>

                        <label
                            for="no_hp"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nomor HP
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            id="no_hp"
                            value="{{ old('no_hp') }}"
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-slate-500 focus:ring-slate-500"
                            placeholder="Contoh: 081234567890"
                        >

                    </div>

                    {{-- JABATAN --}}
                    <div>

                        <label
                            for="jabatan"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Jabatan
                        </label>

                        <input
                            type="text"
                            name="jabatan"
                            id="jabatan"
                            value="{{ old('jabatan') }}"
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-slate-500 focus:ring-slate-500"
                            placeholder="Contoh: Guru BK"
                        >

                    </div>

                    {{-- STATUS --}}
                    <div>

                        <label
                            for="status"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Status <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            required
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-slate-500 focus:ring-slate-500"
                        >

                            <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>
                                Aktif
                            </option>

                            <option value="nonaktif" @selected(old('status') === 'nonaktif')>
                                Nonaktif
                            </option>

                        </select>

                    </div>

                </div>

            </div>

            {{-- DATA AKUN --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="mb-6">

                    <h2 class="text-lg font-bold text-slate-800">
                        Akun Login Guru BK
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Akun ini akan menggunakan role Guru BK.
                    </p>

                </div>

                <div class="grid gap-5 md:grid-cols-2">

                    {{-- EMAIL --}}
                    <div class="md:col-span-2">

                        <label
                            for="email"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Email Login <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-slate-500 focus:ring-slate-500"
                            placeholder="contoh@guru.sch.id"
                        >

                    </div>

                    {{-- PASSWORD --}}
                    <div>

                        <label
                            for="password"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Password <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            minlength="8"
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-slate-500 focus:ring-slate-500"
                            placeholder="Minimal 8 karakter"
                        >

                    </div>

                    {{-- KONFIRMASI PASSWORD --}}
                    <div>

                        <label
                            for="password_confirmation"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            required
                            minlength="8"
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-slate-500 focus:ring-slate-500"
                            placeholder="Ulangi password"
                        >

                    </div>

                </div>

            </div>

            {{-- TOMBOL --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('guru-bk.index') }}"
                    class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-slate-800 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-700"
                >
                    Simpan Guru BK
                </button>

            </div>

        </form>

    </div>

</div>
@endsection