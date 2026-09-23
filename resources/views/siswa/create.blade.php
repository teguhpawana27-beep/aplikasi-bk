@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-5xl">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            Tambah Data Siswa
        </h1>

        <p class="mt-1 text-gray-500">
            Tambahkan data siswa baru ke dalam sistem.
        </p>
    </div>

    {{-- Form --}}
    <div class="rounded-xl bg-white p-6 shadow-sm">

        <form method="POST" action="{{ route('siswa.store') }}">
            @csrf

            {{-- Identitas Siswa --}}
            <div class="mb-8">
                <h2 class="mb-4 border-b pb-3 text-lg font-semibold text-gray-800">
                    Identitas Siswa
                </h2>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- NIS --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            NIS <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="nis"
                            value="{{ old('nis') }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: 2026001"
                        >

                        @error('nis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NISN --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            NISN
                        </label>

                        <input
                            type="text"
                            name="nisn"
                            value="{{ old('nisn') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: 0061234567"
                        >

                        @error('nisn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama_lengkap"
                            value="{{ old('nama_lengkap') }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Masukkan nama lengkap siswa"
                        >

                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="jenis_kelamin"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>

                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tahun Masuk --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Tahun Masuk <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="number"
                            name="tahun_masuk"
                            value="{{ old('tahun_masuk', date('Y')) }}"
                            required
                            min="2000"
                            max="2100"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: 2026"
                        >

                        @error('tahun_masuk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Data Kelahiran --}}
            <div class="mb-8">
                <h2 class="mb-4 border-b pb-3 text-lg font-semibold text-gray-800">
                    Data Kelahiran
                </h2>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Tempat Lahir --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Tempat Lahir
                        </label>

                        <input
                            type="text"
                            name="tempat_lahir"
                            value="{{ old('tempat_lahir') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: Bandung"
                        >

                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Tanggal Lahir
                        </label>

                        <input
                            type="date"
                            name="tanggal_lahir"
                            value="{{ old('tanggal_lahir') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Kontak --}}
            <div class="mb-8">
                <h2 class="mb-4 border-b pb-3 text-lg font-semibold text-gray-800">
                    Kontak & Alamat
                </h2>

                <div class="space-y-5">

                    {{-- Alamat --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Masukkan alamat lengkap siswa"
                        >{{ old('alamat') }}</textarea>

                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            No. HP
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: 081234567890"
                        >

                        @error('no_hp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Status --}}
            <div class="mb-8">
                <h2 class="mb-4 border-b pb-3 text-lg font-semibold text-gray-800">
                    Status Siswa
                </h2>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="status"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="lulus" {{ old('status') == 'lulus' ? 'selected' : '' }}>
                            Lulus
                        </option>
                        <option value="pindah" {{ old('status') == 'pindah' ? 'selected' : '' }}>
                            Pindah
                        </option>
                        <option value="keluar" {{ old('status') == 'keluar' ? 'selected' : '' }}>
                            Keluar
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-3 border-t pt-6">

                <a
                    href="{{ route('siswa.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Simpan Data Siswa
                </button>

            </div>

        </form>

    </div>

</div>

@endsection