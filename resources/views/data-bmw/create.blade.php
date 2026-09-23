@extends('layouts.app')

@section('content')

@php
    /*
     * Normalisasi data siswa untuk kebutuhan filter:
     * Tahun Ajaran -> Tingkat -> Jurusan -> Kelas -> Siswa.
     *
     * Siswa adalah master data tanpa tahun_ajaran_id langsung.
     * Karena itu informasi kelas/tahun diambil dari riwayat_kelas.
     */
    $tahunAjaranDefault = (int) session('tahun_ajaran_id', 0);

    $siswaBmwData = collect($siswa)->map(function ($item) use ($tahunAjaranDefault) {
        $riwayat = collect($item->riwayatKelas ?? [])
            ->filter(function ($riwayat) use ($tahunAjaranDefault) {
                return $riwayat->kelas &&
                    (int) $riwayat->kelas->tahun_ajaran_id === $tahunAjaranDefault;
            })
            ->sortByDesc(function ($riwayat) {
                return $riwayat->tanggal_mulai;
            })
            ->first();

        return [
            'id' => $item->id,
            'nis' => $item->nis,
            'nama_lengkap' => $item->nama_lengkap,
            'kelas_id' => $riwayat?->kelas_id,
            'tahun_ajaran_id' => $riwayat?->kelas?->tahun_ajaran_id,
            'tingkat' => $riwayat?->kelas?->tingkat,
            'jurusan_id' => $riwayat?->kelas?->jurusan_id,
        ];
    })->values();
@endphp

<div
    class="max-w-4xl mx-auto space-y-6"
    x-data="{
        tahunAjaran: '{{ old('tahun_ajaran_id', $tahunAjaranDefault) }}',
        tingkat: '',
        jurusan: '',
        kelas: '',
        siswa: '',

        kelasData: @js($kelasList),
        siswaData: @js($siswaBmwData),

        get tingkatList() {
            return [...new Set(
                this.kelasData
                    .filter(item =>
                        String(item.tahun_ajaran_id) === String(this.tahunAjaran)
                    )
                    .map(item => item.tingkat)
            )].sort((a, b) => Number(a) - Number(b));
        },

        get jurusanList() {
            const ids = [...new Set(
                this.kelasData
                    .filter(item =>
                        String(item.tahun_ajaran_id) === String(this.tahunAjaran) &&
                        String(item.tingkat) === String(this.tingkat)
                    )
                    .map(item => String(item.jurusan_id))
            )];

            return @js($jurusans)
                .filter(item => ids.includes(String(item.id)));
        },

        get kelasFiltered() {
            return this.kelasData.filter(item =>
                String(item.tahun_ajaran_id) === String(this.tahunAjaran) &&
                String(item.tingkat) === String(this.tingkat) &&
                String(item.jurusan_id) === String(this.jurusan)
            );
        },

        get siswaFiltered() {
            if (!this.kelas) {
                return [];
            }

            return this.siswaData.filter(item =>
                String(item.kelas_id) === String(this.kelas) &&
                String(item.tahun_ajaran_id) === String(this.tahunAjaran)
            );
        },

        resetTingkat() {
            this.tingkat = '';
            this.jurusan = '';
            this.kelas = '';
            this.siswa = '';
        },

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
            Tambah Data Siswa Baru (BMW)
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Tambahkan data siswa baru ke dalam sistem.
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
        action="{{ route('data-bmw.store') }}"
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
                x-model="tahunAjaran"
                @change="resetTingkat()"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

                <option value="">
                    -- Pilih Tahun Ajaran --
                </option>

                @foreach ($tahunAjaran as $item)

                    <option
                        value="{{ $item->id }}"
                        {{ old('tahun_ajaran_id', $tahunAjaranDefault) == $item->id ? 'selected' : '' }}
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
                name="jurusan_id"
                x-model="jurusan"
                @change="resetKelas()"
                :disabled="!tingkat"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
            >

                <option value="">
                    -- Pilih Jurusan --
                </option>

                <template x-for="item in jurusanList" :key="item.id">
                    <option
                        :value="item.id"
                        x-text="item.kode + ' - ' + item.nama"
                    ></option>
                </template>

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
                name="kelas_id"
                x-model="kelas"
                @change="resetSiswa()"
                :disabled="!jurusan"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
            >

                <option value="">
                    -- Pilih Kelas --
                </option>

                <template x-for="item in kelasFiltered" :key="item.id">
                    <option
                        :value="item.id"
                        x-text="item.nama_kelas"
                    ></option>
                </template>

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

                <template x-for="item in siswaFiltered" :key="item.id">
                    <option
                        :value="item.id"
                        x-text="item.nis + ' - ' + item.nama_lengkap"
                    ></option>
                </template>

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
        {{-- ASAL SEKOLAH --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="asal_sekolah"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Asal Sekolah
            </label>

            <input
                type="text"
                name="asal_sekolah"
                id="asal_sekolah"
                value="{{ old('asal_sekolah') }}"
                placeholder="Contoh: SMP Negeri 1 Majalaya"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ===================================================== --}}
        {{-- DATA MASUK --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="data_masuk"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Data Masuk
            </label>

            <textarea
                name="data_masuk"
                id="data_masuk"
                rows="4"
                placeholder="Masukkan informasi data masuk siswa..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('data_masuk') }}</textarea>

        </div>


        {{-- ===================================================== --}}
        {{-- DATA ORANG TUA --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="data_orang_tua"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Data Orang Tua
            </label>

            <textarea
                name="data_orang_tua"
                id="data_orang_tua"
                rows="4"
                placeholder="Masukkan informasi orang tua/wali siswa..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('data_orang_tua') }}</textarea>

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
                href="{{ route('data-bmw.index') }}"
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