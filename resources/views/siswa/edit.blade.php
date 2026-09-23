@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-5xl">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            Edit Data Siswa
        </h1>
        <p class="mt-1 text-gray-500">
            Perbarui informasi data siswa.
        </p>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm">

        <form method="POST" action="{{ route('siswa.update', $siswa) }}">
            @csrf
            @method('PUT')

            {{-- Identitas --}}
            <div class="mb-8">
                <h2 class="mb-4 border-b pb-3 text-lg font-semibold">
                    Identitas Siswa
                </h2>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            NIS <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="nis"
                            value="{{ old('nis', $siswa->nis) }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('nis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            NISN
                        </label>

                        <input
                            type="text"
                            name="nisn"
                            value="{{ old('nisn', $siswa->nisn) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('nisn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama_lengkap"
                            value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="jenis_kelamin"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>

                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Tahun Masuk <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="number"
                            name="tahun_masuk"
                            value="{{ old('tahun_masuk', $siswa->tahun_masuk) }}"
                            required
                            min="2000"
                            max="2100"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('tahun_masuk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Kelahiran --}}
            <div class="mb-8">
                <h2 class="mb-4 border-b pb-3 text-lg font-semibold">
                    Data Kelahiran
                </h2>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Tempat Lahir
                        </label>

                        <input
                            type="text"
                            name="tempat_lahir"
                            value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Tanggal Lahir
                        </label>

                        <input
                            type="date"
                            name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d')) }}"
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
                <h2 class="mb-4 border-b pb-3 text-lg font-semibold">
                    Kontak & Alamat
                </h2>

                <div class="space-y-5">

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('alamat', $siswa->alamat) }}</textarea>

                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            No. HP
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp', $siswa->no_hp) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('no_hp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Status --}}
            <div class="mb-8">
                <h2 class="mb-4 border-b pb-3 text-lg font-semibold">
                    Status Siswa
                </h2>

                <select
                    name="status"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="aktif" {{ old('status', $siswa->status) == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="lulus" {{ old('status', $siswa->status) == 'lulus' ? 'selected' : '' }}>
                        Lulus
                    </option>

                    <option value="pindah" {{ old('status', $siswa->status) == 'pindah' ? 'selected' : '' }}>
                        Pindah
                    </option>

                    <option value="keluar" {{ old('status', $siswa->status) == 'keluar' ? 'selected' : '' }}>
                        Keluar
                    </option>
                </select>

                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 border-t pt-6">

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
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection