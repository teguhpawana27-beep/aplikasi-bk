@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    {{-- HEADER --}}
    <div>

        <a
            href="{{ route('users.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700"
        >
            ← Kembali ke Manajemen Pengguna
        </a>

        <p class="mt-5 text-sm font-semibold text-blue-600">
            MANAJEMEN PENGGUNA
        </p>

        <h1 class="mt-1 text-2xl font-bold text-slate-800">
            Tambah Pengguna
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Tambahkan akun pengguna baru ke dalam sistem BK.
        </p>

    </div>


    {{-- ERROR --}}
    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">

            <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- FORM --}}
    <form
        action="{{ route('users.store') }}"
        method="POST"
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    >

        @csrf


        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-bold text-slate-800">
                Informasi Akun
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Isi informasi akun pengguna.
            </p>

        </div>


        <div class="grid gap-6 p-6 md:grid-cols-2">


            {{-- NAMA --}}
            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Nama Pengguna
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="Masukkan nama pengguna"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- EMAIL --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="contoh@email.com"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- ROLE --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Role
                </label>

                <select
                    name="role_id"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                    <option value="">
                        -- Pilih Role --
                    </option>

                    @foreach($roles as $role)

                        <option
                            value="{{ $role->id }}"
                            @selected(old('role_id') == $role->id)
                        >
                            {{ $role->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- PASSWORD --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    placeholder="Minimal 8 karakter"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- KONFIRMASI --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    placeholder="Ulangi password"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- STATUS --}}
            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Status Akun
                </label>

                <select
                    name="is_active"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                    <option
                        value="1"
                        @selected(old('is_active', '1') == '1')
                    >
                        Aktif
                    </option>

                    <option
                        value="0"
                        @selected(old('is_active') === '0')
                    >
                        Nonaktif
                    </option>

                </select>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end">

            <a
                href="{{ route('users.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-100"
            >
                Batal
            </a>

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Simpan Pengguna
            </button>

        </div>

    </form>

</div>

@endsection