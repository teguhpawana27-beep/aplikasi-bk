@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>
            <p class="text-sm font-semibold text-blue-600">
                ADMINISTRASI SISTEM
            </p>

            <h1 class="mt-1 text-2xl font-bold text-slate-800">
                Manajemen Pengguna
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Kelola akun pengguna dan hak akses sistem BK.
            </p>
        </div>


        <a
            href="{{ route('users.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
        >
            <svg
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 5v14M5 12h14"
                />
            </svg>

            Tambah Pengguna
        </a>

    </div>


    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            {{ session('error') }}
        </div>

    @endif


    {{-- VALIDATION --}}
    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">

            <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- CARD --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- CARD HEADER --}}
        <div class="border-b border-slate-200 px-6 py-5">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-base font-bold text-slate-800">
                        Daftar Pengguna
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $users->count() }} akun terdaftar
                    </p>
                </div>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-600">
                            No
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-600">
                            Nama
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-600">
                            Email
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-600">
                            Role
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-600">
                            Status
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-center font-semibold text-slate-600">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($users as $index => $user)

                        <tr class="transition hover:bg-slate-50">

                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ $index + 1 }}
                            </td>


                            <td class="whitespace-nowrap px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <div class="font-semibold text-slate-800">
                                            {{ $user->name }}
                                        </div>

                                        @if($user->id === auth()->id())

                                            <div class="text-xs text-blue-600">
                                                Akun Anda
                                            </div>

                                        @endif
                                    </div>

                                </div>

                            </td>


                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                {{ $user->email }}
                            </td>


                            <td class="whitespace-nowrap px-6 py-4">

                                @php
                                    $roleName = $user->role?->name ?? '-';
                                @endphp

                                @if($roleName === 'Admin')

                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                        Admin
                                    </span>

                                @elseif($roleName === 'Guru BK')

                                    <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                        Guru BK
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ $roleName }}
                                    </span>

                                @endif

                            </td>


                            <td class="whitespace-nowrap px-6 py-4">

                                @if($user->is_active)

                                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">

                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                                        Aktif

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">

                                        <span class="h-2 w-2 rounded-full bg-red-500"></span>

                                        Nonaktif

                                    </span>

                                @endif

                            </td>


                            <td class="whitespace-nowrap px-6 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('users.edit', $user) }}"
                                        class="rounded-lg bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-100"
                                    >
                                        Edit
                                    </a>


                                    {{-- DELETE --}}
                                    @if($user->id !== auth()->id())

                                        <form
                                            action="{{ route('users.destroy', $user) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-12 text-center"
                            >

                                <div class="text-slate-400">

                                    <svg
                                        class="mx-auto h-12 w-12"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                                        />
                                        <circle
                                            cx="9"
                                            cy="7"
                                            r="4"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                                        />
                                    </svg>

                                    <p class="mt-3 font-semibold">
                                        Belum ada pengguna.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection