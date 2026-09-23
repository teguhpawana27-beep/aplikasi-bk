@extends('layouts.app')

@section('content')

<div
    class="max-w-4xl mx-auto space-y-6"
    x-data="{
        tingkat: '',
        jurusan: '',
        kelas: '',
        siswa: '',

        resetJurusan() {
            this.jurusan = '';
            this.kelas = '';
            this.siswa = '';
        },

        resetKelas() {
            this.kelas = '';
            this.siswa = '';
        },

        resetSiswa() {
            this.siswa = '';
        }
    }"
>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Data Siswa SNPMB
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Tambahkan data pendaftaran dan hasil SNPMB siswa.
        </p>
    </div>


    {{-- ========================================================= --}}
    {{-- ERROR --}}
    {{-- ========================================================= --}}

    @if ($errors->any())

        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">

            <p class="font-semibold text-red-700 mb-2">
                Terdapat kesalahan:
            </p>

            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('data-snpmb.store') }}"
        method="POST"
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- TAHUN AJARAN --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="tahun_ajaran_id"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Tahun Ajaran
                <span class="text-red-500">*</span>
            </label>

            <select
                name="tahun_ajaran_id"
                id="tahun_ajaran_id"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

                <option value="">
                    -- Pilih Tahun Ajaran --
                </option>

                @foreach ($tahunAjaran as $item)

                    <option
                        value="{{ $item->id }}"
                        {{ old('tahun_ajaran_id') == $item->id ? 'selected' : '' }}
                    >
                        {{ $item->nama }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- ===================================================== --}}
        {{-- TINGKAT --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="tingkat"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Tingkat
                <span class="text-red-500">*</span>
            </label>

            <select
                id="tingkat"
                x-model="tingkat"
                @change="resetJurusan()"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

                <option value="">
                    -- Pilih Tingkat --
                </option>

                <option value="10">
                    10
                </option>

                <option value="11">
                    11
                </option>

                <option value="12">
                    12
                </option>

            </select>

            <p class="text-xs text-gray-400 mt-2">
                Pilih tingkat terlebih dahulu.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- JURUSAN --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="jurusan"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Jurusan
                <span class="text-red-500">*</span>
            </label>

            <select
                id="jurusan"
                x-model="jurusan"
                @change="resetKelas()"
                :disabled="!tingkat"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
            >

                <option value="">
                    -- Pilih Jurusan --
                </option>

                @foreach ($jurusans as $item)

                    <option
                        value="{{ $item->id }}"
                        x-show="
                            !tingkat ||
                            (tingkat == '10' &&
                            {{ $kelasList->where('jurusan_id', $item->id)->where('tingkat', 10)->count() }} > 0) ||

                            (tingkat == '11' &&
                            {{ $kelasList->where('jurusan_id', $item->id)->where('tingkat', 11)->count() }} > 0) ||

                            (tingkat == '12' &&
                            {{ $kelasList->where('jurusan_id', $item->id)->where('tingkat', 12)->count() }} > 0)
                        "
                    >
                        {{ $item->kode }} - {{ $item->nama }}
                    </option>

                @endforeach

            </select>

            <p
                x-show="!tingkat"
                class="text-xs text-gray-400 mt-2"
            >
                Pilih tingkat terlebih dahulu untuk melihat jurusan.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- KELAS --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="kelas"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Kelas
                <span class="text-red-500">*</span>
            </label>

            <select
                id="kelas"
                x-model="kelas"
                @change="resetSiswa()"
                :disabled="!jurusan"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
            >

                <option value="">
                    -- Pilih Kelas --
                </option>

                @foreach ($kelasList as $item)

                    <option
                        value="{{ $item->id }}"
                        x-show="
                            tingkat == '{{ $item->tingkat }}' &&
                            jurusan == '{{ $item->jurusan_id }}'
                        "
                    >
                        {{ $item->nama_kelas }}
                    </option>

                @endforeach

            </select>

            <p
                x-show="!jurusan"
                class="text-xs text-gray-400 mt-2"
            >
                Pilih jurusan terlebih dahulu untuk melihat kelas.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- SISWA --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="siswa_id"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Siswa
                <span class="text-red-500">*</span>
            </label>

            <select
                name="siswa_id"
                id="siswa_id"
                x-model="siswa"
                :disabled="!kelas"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
            >

                <option value="">
                    -- Pilih Siswa --
                </option>

                @foreach ($siswa as $item)

                    <option
                        value="{{ $item->id }}"
                        x-show="kelas == '{{ $item->kelas_id }}'"
                    >
                        {{ $item->nis }} - {{ $item->nama_lengkap }}
                    </option>

                @endforeach

            </select>

            <p
                x-show="!kelas"
                class="text-xs text-gray-400 mt-2"
            >
                Pilih kelas terlebih dahulu untuk melihat siswa.
            </p>

            <p
                x-show="kelas"
                class="text-xs text-blue-500 mt-2"
            >
                Daftar siswa menyesuaikan kelas yang dipilih.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- TANGGAL PENDATAAN --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="tanggal_pendataan"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Tanggal Pendataan
                <span class="text-red-500">*</span>
            </label>

            <input
                type="date"
                name="tanggal_pendataan"
                id="tanggal_pendataan"
                value="{{ old('tanggal_pendataan', date('Y-m-d')) }}"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ===================================================== --}}
        {{-- JALUR PENDAFTARAN --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="jalur"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Jalur Pendaftaran
            </label>

            <input
                type="text"
                name="jalur"
                id="jalur"
                value="{{ old('jalur') }}"
                placeholder="Contoh: SNBP / SNBT / Mandiri"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ===================================================== --}}
        {{-- PERGURUAN TINGGI --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="perguruan_tinggi"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Perguruan Tinggi
            </label>

            <input
                type="text"
                name="perguruan_tinggi"
                id="perguruan_tinggi"
                value="{{ old('perguruan_tinggi') }}"
                placeholder="Contoh: Universitas Pendidikan Indonesia"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ===================================================== --}}
        {{-- PROGRAM STUDI --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="program_studi"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Program Studi
            </label>

            <input
                type="text"
                name="program_studi"
                id="program_studi"
                value="{{ old('program_studi') }}"
                placeholder="Contoh: Teknik Informatika"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ===================================================== --}}
        {{-- STATUS PENDAFTARAN --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="status_pendaftaran"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Status Pendaftaran
            </label>

            <select
                name="status_pendaftaran"
                id="status_pendaftaran"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

                <option value="">
                    -- Pilih Status --
                </option>

                <option
                    value="Belum Mendaftar"
                    {{ old('status_pendaftaran') == 'Belum Mendaftar' ? 'selected' : '' }}
                >
                    Belum Mendaftar
                </option>

                <option
                    value="Sudah Mendaftar"
                    {{ old('status_pendaftaran') == 'Sudah Mendaftar' ? 'selected' : '' }}
                >
                    Sudah Mendaftar
                </option>

                <option
                    value="Lulus"
                    {{ old('status_pendaftaran') == 'Lulus' ? 'selected' : '' }}
                >
                    Lulus
                </option>

                <option
                    value="Tidak Lulus"
                    {{ old('status_pendaftaran') == 'Tidak Lulus' ? 'selected' : '' }}
                >
                    Tidak Lulus
                </option>

            </select>

        </div>


        {{-- ===================================================== --}}
        {{-- HASIL --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="hasil"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Hasil
            </label>

            <textarea
                name="hasil"
                id="hasil"
                rows="4"
                placeholder="Masukkan hasil SNPMB siswa..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('hasil') }}</textarea>

        </div>


        {{-- ===================================================== --}}
        {{-- KETERANGAN --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="keterangan"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Keterangan
            </label>

            <textarea
                name="keterangan"
                id="keterangan"
                rows="3"
                placeholder="Tambahkan keterangan jika diperlukan..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('keterangan') }}</textarea>

        </div>


        {{-- ===================================================== --}}
        {{-- TOMBOL --}}
        {{-- ===================================================== --}}

        <div
            class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200"
        >

            <a
                href="{{ route('data-snpmb.index') }}"
                class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-semibold"
            >
                Batal
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold"
            >
                Simpan Data
            </button>

        </div>

    </form>

</div>

@endsection