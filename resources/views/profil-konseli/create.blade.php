@extends('layouts.app')

@section('content')

<div
    class="max-w-4xl mx-auto space-y-6"
    x-data="{
        tahunAjaran: '{{ old('tahun_ajaran_id') }}',
        tingkat: '{{ old('tingkat') }}',
        jurusan: '{{ old('jurusan_id') }}',
        kelas: '{{ old('kelas_id') }}',
        siswa: '{{ old('siswa_id') }}',

        jurusanData: @js($jurusans),
        kelasData: @js($kelasList),
        siswaData: @js($siswa),

        get filteredJurusan() {
            const jurusanIds = this.kelasData
                .filter(kelas =>
                    String(kelas.tahun_ajaran_id) === String(this.tahunAjaran) &&
                    String(kelas.tingkat) === String(this.tingkat)
                )
                .map(kelas => String(kelas.jurusan_id));

            return this.jurusanData.filter(jurusan =>
                jurusanIds.includes(String(jurusan.id))
            );
        },

        get filteredKelas() {
            return this.kelasData.filter(kelas =>
                String(kelas.tahun_ajaran_id) === String(this.tahunAjaran) &&
                String(kelas.tingkat) === String(this.tingkat) &&
                String(kelas.jurusan_id) === String(this.jurusan)
            );
        },

        get filteredSiswa() {
            return this.siswaData.filter(siswa =>
                String(siswa.kelas_id) === String(this.kelas) &&
                String(siswa.tahun_ajaran_id) === String(this.tahunAjaran)
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
        }
    }"
>

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Profil Konseli
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Tambahkan profil dan kondisi konseli ke dalam sistem.
        </p>
    </div>


    {{-- ERROR --}}
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


    {{-- FORM --}}
    <form
        action="{{ route('profil-konseli.store') }}"
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
                        @selected(old('tahun_ajaran_id') == $item->id)
                    >
                        {{ $item->nama }}
                    </option>

                @endforeach

            </select>

            <p class="text-xs text-gray-400 mt-2">
                Pilih tahun ajaran untuk melihat tingkat kelas.
            </p>

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
                name="tingkat"
                id="tingkat"
                x-model="tingkat"
                @change="resetJurusan()"
                :disabled="!tahunAjaran"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
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

            <p
                x-show="!tahunAjaran"
                class="text-xs text-gray-400 mt-2"
            >
                Pilih tahun ajaran terlebih dahulu.
            </p>

            <p
                x-show="tahunAjaran"
                class="text-xs text-gray-400 mt-2"
            >
                Pilih tingkat kelas.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- JURUSAN --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="jurusan_id"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Jurusan
                <span class="text-red-500">*</span>
            </label>

            <select
                name="jurusan_id"
                id="jurusan_id"
                x-model="jurusan"
                @change="resetKelas()"
                :disabled="!tingkat"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
            >

                <option value="">
                    -- Pilih Jurusan --
                </option>

                <template
                    x-for="item in filteredJurusan"
                    :key="item.id"
                >

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

            <p
                x-show="tingkat && filteredJurusan.length === 0"
                class="text-xs text-red-400 mt-2"
            >
                Tidak ada jurusan yang memiliki kelas pada tingkat ini.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- KELAS --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="kelas_id"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Kelas
                <span class="text-red-500">*</span>
            </label>

            <select
                name="kelas_id"
                id="kelas_id"
                x-model="kelas"
                @change="siswa = ''"
                :disabled="!jurusan"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
            >

                <option value="">
                    -- Pilih Kelas --
                </option>

                <template
                    x-for="item in filteredKelas"
                    :key="item.id"
                >

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

            <p
                x-show="jurusan && filteredKelas.length === 0"
                class="text-xs text-red-400 mt-2"
            >
                Tidak ada kelas yang tersedia untuk pilihan tersebut.
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

                <template
                    x-for="item in filteredSiswa"
                    :key="item.id"
                >

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
                x-show="kelas && filteredSiswa.length === 0"
                class="text-xs text-red-400 mt-2"
            >
                Tidak ada siswa yang terdaftar pada kelas ini.
            </p>

            <p
                x-show="kelas && filteredSiswa.length > 0"
                class="text-xs text-blue-500 mt-2"
            >
                Daftar siswa menyesuaikan kelas yang dipilih.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- KONDISI PRIBADI --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="kondisi_pribadi"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Kondisi Pribadi
            </label>

            <textarea
                name="kondisi_pribadi"
                id="kondisi_pribadi"
                rows="4"
                placeholder="Masukkan kondisi pribadi konseli..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('kondisi_pribadi') }}</textarea>

        </div>


        {{-- ===================================================== --}}
        {{-- KONDISI SOSIAL --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="kondisi_sosial"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Kondisi Sosial
            </label>

            <textarea
                name="kondisi_sosial"
                id="kondisi_sosial"
                rows="4"
                placeholder="Masukkan kondisi sosial konseli..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('kondisi_sosial') }}</textarea>

        </div>


        {{-- ===================================================== --}}
        {{-- KONDISI BELAJAR --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="kondisi_belajar"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Kondisi Belajar
            </label>

            <textarea
                name="kondisi_belajar"
                id="kondisi_belajar"
                rows="4"
                placeholder="Masukkan kondisi belajar konseli..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('kondisi_belajar') }}</textarea>

        </div>


        {{-- ===================================================== --}}
        {{-- KONDISI KARIR --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="kondisi_karir"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Kondisi Karir
            </label>

            <textarea
                name="kondisi_karir"
                id="kondisi_karir"
                rows="4"
                placeholder="Masukkan kondisi karir konseli..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('kondisi_karir') }}</textarea>

        </div>


        {{-- ===================================================== --}}
        {{-- KONDISI KELUARGA --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="kondisi_keluarga"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Kondisi Keluarga
            </label>

            <textarea
                name="kondisi_keluarga"
                id="kondisi_keluarga"
                rows="4"
                placeholder="Masukkan kondisi keluarga konseli..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('kondisi_keluarga') }}</textarea>

        </div>


        {{-- ===================================================== --}}
        {{-- CATATAN --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="catatan"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Catatan
            </label>

            <textarea
                name="catatan"
                id="catatan"
                rows="4"
                placeholder="Tambahkan catatan jika diperlukan..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('catatan') }}</textarea>

        </div>


        {{-- ===================================================== --}}
        {{-- TOMBOL --}}
        {{-- ===================================================== --}}

        <div
            class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200"
        >

            <a
                href="{{ route('profil-konseli.index') }}"
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