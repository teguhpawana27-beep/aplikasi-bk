@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Data Siswa Baru (BMW)
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Perbarui data siswa baru yang sudah tersimpan.
        </p>
    </div>


    {{-- Error Validasi --}}
    @if ($errors->any())

        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">

            <p class="font-semibold text-red-700 mb-2">
                Terdapat kesalahan:
            </p>

            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <form
        action="{{ url('/data-bmw/' . request()->route('data_bmw')) }}"
        method="POST"
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6"
    >

        @csrf
        @method('PUT')


        {{-- ========================================================== --}}
        {{-- DATA SISWA --}}
        {{-- ========================================================== --}}

        <div>

            <label
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Siswa <span class="text-red-500">*</span>
            </label>


            <div
                x-data='{
                    tahunAjaran: "{{ old("tahun_ajaran_id", $dataBMW->tahun_ajaran_id) }}",

                    tingkat: "{{ old("tingkat", optional($siswa->firstWhere("id", $dataBMW->siswa_id))->tingkat ?? "") }}",

                    jurusan: "{{ old("jurusan_id", optional($siswa->firstWhere("id", $dataBMW->siswa_id))->jurusan_id ?? "") }}",

                    kelas: "{{ old("kelas_id", optional($siswa->firstWhere("id", $dataBMW->siswa_id))->kelas_id ?? "") }}",

                    siswaId: "{{ old("siswa_id", $dataBMW->siswa_id) }}",

                    kelasData: @json($kelasList),

                    siswaData: @json($siswa),

                    jurusanData: @json($jurusans),


                    get tingkatList() {

                        let data = this.kelasData.filter(item =>
                            String(item.tahun_ajaran_id) === String(this.tahunAjaran)
                        );

                        return [
                            ...new Set(
                                data.map(item => item.tingkat)
                            )
                        ];

                    },


                    get jurusanList() {

                        let data = this.kelasData.filter(item =>
                            String(item.tahun_ajaran_id) === String(this.tahunAjaran) &&
                            String(item.tingkat) === String(this.tingkat)
                        );

                        let ids = [
                            ...new Set(
                                data.map(item => item.jurusan_id)
                            )
                        ];

                        return this.jurusanData.filter(item =>
                            ids.includes(item.id) ||
                            ids.includes(String(item.id))
                        );

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
                            String(item.kelas_id) === String(this.kelas)
                        );

                    },


                    init() {

                        let current = this.siswaData.find(item =>
                            String(item.id) === String(this.siswaId)
                        );

                        if (current) {

                            if (!this.tingkat && current.tingkat) {
                                this.tingkat = current.tingkat;
                            }

                            if (!this.jurusan && current.jurusan_id) {
                                this.jurusan = current.jurusan_id;
                            }

                            if (!this.kelas && current.kelas_id) {
                                this.kelas = current.kelas_id;
                            }

                            // Siswa pada master data tidak menyimpan jurusan langsung.
                            // Jika jurusan kosong, ambil dari kelas yang sedang dipilih.
                            if (!this.jurusan && this.kelas) {
                                const currentKelas = this.kelasData.find(item =>
                                    String(item.id) === String(this.kelas)
                                );

                                if (currentKelas) {
                                    this.jurusan = currentKelas.jurusan_id;
                                }
                            }

                        }

                    },


                    resetTingkat() {

                        this.tingkat = "";
                        this.jurusan = "";
                        this.kelas = "";
                        this.siswaId = "";

                    },


                    resetJurusan() {

                        this.jurusan = "";
                        this.kelas = "";
                        this.siswaId = "";

                    },


                    resetKelas() {

                        this.kelas = "";
                        this.siswaId = "";

                    }

                }'
                x-init="init()"
                class="space-y-5"
            >


                {{-- ================================================== --}}
                {{-- TAHUN AJARAN --}}
                {{-- ================================================== --}}

                <div>

                    <label
                        for="tahun_ajaran_id"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Tahun Ajaran <span class="text-red-500">*</span>
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

                            <option value="{{ $item->id }}">
                                {{ $item->nama }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ================================================== --}}
                {{-- TINGKAT --}}
                {{-- ================================================== --}}

                <div>

                    <label
                        for="tingkat"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Tingkat <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="tingkat"
                        name="tingkat"
                        x-model="tingkat"
                        @change="resetJurusan()"
                        :disabled="!tahunAjaran"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                    >

                        <option value="">
                            -- Pilih Tingkat --
                        </option>

                        <template
                            x-for="item in tingkatList"
                            :key="item"
                        >

                            <option
                                :value="item"
                                x-text="item"
                            ></option>

                        </template>

                    </select>

                </div>


                {{-- ================================================== --}}
                {{-- JURUSAN --}}
                {{-- ================================================== --}}

                <div>

                    <label
                        for="jurusan_id"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Jurusan <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="jurusan_id"
                        name="jurusan_id"
                        x-model="jurusan"
                        @change="resetKelas()"
                        :disabled="!tingkat"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                    >

                        <option value="">
                            -- Pilih Jurusan --
                        </option>

                        <template
                            x-for="item in jurusanList"
                            :key="item.id"
                        >

                            <option
                                :value="item.id"
                                x-text="item.nama"
                            ></option>

                        </template>

                    </select>

                </div>


                {{-- ================================================== --}}
                {{-- KELAS --}}
                {{-- ================================================== --}}

                <div>

                    <label
                        for="kelas_id"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Kelas <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="kelas_id"
                        name="kelas_id"
                        x-model="kelas"
                        @change="resetSiswa"
                        :disabled="!jurusan"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                    >

                        <option value="">
                            -- Pilih Kelas --
                        </option>

                        <template
                            x-for="item in kelasFiltered"
                            :key="item.id"
                        >

                            <option
                                :value="item.id"
                                x-text="item.nama_kelas"
                            ></option>

                        </template>

                    </select>

                </div>


                {{-- ================================================== --}}
                {{-- SISWA --}}
                {{-- ================================================== --}}

                <div>

                    <label
                        for="siswa_id"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Siswa <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="siswa_id"
                        id="siswa_id"
                        x-model="siswaId"
                        :disabled="!kelas"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                    >

                        <option value="">
                            -- Pilih Siswa --
                        </option>

                        <template
                            x-for="item in siswaFiltered"
                            :key="item.id"
                        >

                            <option
                                :value="item.id"
                                x-text="item.nis + ' - ' + item.nama_lengkap"
                            ></option>

                        </template>

                    </select>

                    <p class="text-xs text-gray-500 mt-2">
                        Pilih Tahun Ajaran → Tingkat → Jurusan → Kelas untuk menampilkan siswa.
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================== --}}
        {{-- TANGGAL PENDATAAN --}}
        {{-- ========================================================== --}}

        <div>

            <label
                for="tanggal_pendataan"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Tanggal Pendataan <span class="text-red-500">*</span>
            </label>

            <input
                type="date"
                name="tanggal_pendataan"
                id="tanggal_pendataan"
                value="{{ old('tanggal_pendataan', $dataBMW->tanggal_pendataan?->format('Y-m-d')) }}"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ========================================================== --}}
        {{-- ASAL SEKOLAH --}}
        {{-- ========================================================== --}}

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
                value="{{ old('asal_sekolah', $dataBMW->asal_sekolah) }}"
                placeholder="Contoh: SMP Negeri 1 Majalaya"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ========================================================== --}}
        {{-- DATA MASUK --}}
        {{-- ========================================================== --}}

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
            >{{ old('data_masuk', $dataBMW->data_masuk) }}</textarea>

        </div>


        {{-- ========================================================== --}}
        {{-- DATA ORANG TUA --}}
        {{-- ========================================================== --}}

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
            >{{ old('data_orang_tua', $dataBMW->data_orang_tua) }}</textarea>

        </div>


        {{-- ========================================================== --}}
        {{-- KETERANGAN --}}
        {{-- ========================================================== --}}

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
            >{{ old('keterangan', $dataBMW->keterangan) }}</textarea>

        </div>


        {{-- ========================================================== --}}
        {{-- TOMBOL --}}
        {{-- ========================================================== --}}

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">

            {{-- Batal --}}
            <a
                href="{{ url('/data-bmw') }}"
                class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-semibold"
            >
                Batal
            </a>


            {{-- Simpan --}}
            <button
                type="submit"
                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold"
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection