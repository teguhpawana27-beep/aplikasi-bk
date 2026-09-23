@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Data Siswa SNPMB
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Perbarui data SNPMB yang sudah tersimpan.
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

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <form
        action="{{ url('/data-snpmb/' . request()->route('data_snpmb')) }}"
        method="POST"
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6"
    >

        @csrf
        @method('PUT')


        {{-- ====================================================== --}}
        {{-- DATA SISWA --}}
        {{-- ====================================================== --}}

        <div>

            <label
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Data Siswa
            </label>


            <div
                x-data='{
                    tahunAjaran: "{{ old("tahun_ajaran_id", $dataSNPMB->tahun_ajaran_id) }}",

                    tingkat: "{{ old("tingkat", optional($siswa->firstWhere("id", $dataSNPMB->siswa_id))->tingkat ?? "") }}",

                    jurusan: "{{ old("jurusan_id", optional($siswa->firstWhere("id", $dataSNPMB->siswa_id))->jurusan_id ?? "") }}",

                    kelas: "{{ old("kelas_id", optional($siswa->firstWhere("id", $dataSNPMB->siswa_id))->kelas_id ?? "") }}",

                    siswaId: "{{ old("siswa_id", $dataSNPMB->siswa_id) }}",

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
                            String(item.tahun_ajaran_id) === String(this.tahunAjaran) &&
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

                            // Fallback: ambil jurusan dari kelas jika data siswa
                            // tidak membawa jurusan_id secara langsung.
                            if (!this.jurusan && this.kelas) {
                                let currentKelas = this.kelasData.find(item =>
                                    String(item.id) === String(this.kelas)
                                );

                                if (currentKelas && currentKelas.jurusan_id) {
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
                        name="tingkat"
                        id="tingkat"
                        x-model="tingkat"
                        @change="jurusan = ''; kelas = ''; siswaId = ''"
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
                        name="jurusan_id"
                        id="jurusan_id"
                        x-model="jurusan"
                        @change="kelas = ''; siswaId = ''"
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
                        name="kelas_id"
                        id="kelas_id"
                        x-model="kelas"
                        @change="siswaId = ''"
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


        {{-- ====================================================== --}}
        {{-- TANGGAL PENDATAAN --}}
        {{-- ====================================================== --}}

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
                value="{{ old('tanggal_pendataan', $dataSNPMB->tanggal_pendataan?->format('Y-m-d')) }}"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ====================================================== --}}
        {{-- JALUR --}}
        {{-- ====================================================== --}}

        <div>

            <label
                for="jalur"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Jalur
            </label>

            <input
                type="text"
                name="jalur"
                id="jalur"
                value="{{ old('jalur', $dataSNPMB->jalur) }}"
                placeholder="Contoh: SNBP / SNBT / Mandiri"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ====================================================== --}}
        {{-- PERGURUAN TINGGI --}}
        {{-- ====================================================== --}}

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
                value="{{ old('perguruan_tinggi', $dataSNPMB->perguruan_tinggi) }}"
                placeholder="Contoh: Universitas Pendidikan Indonesia"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ====================================================== --}}
        {{-- PROGRAM STUDI --}}
        {{-- ====================================================== --}}

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
                value="{{ old('program_studi', $dataSNPMB->program_studi) }}"
                placeholder="Contoh: Teknik Informatika"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ====================================================== --}}
        {{-- STATUS PENDAFTARAN --}}
        {{-- ====================================================== --}}

        <div>

            <label
                for="status_pendaftaran"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Status Pendaftaran
            </label>

            <input
                type="text"
                name="status_pendaftaran"
                id="status_pendaftaran"
                value="{{ old('status_pendaftaran', $dataSNPMB->status_pendaftaran) }}"
                placeholder="Contoh: Terdaftar / Sudah Finalisasi"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- ====================================================== --}}
        {{-- HASIL --}}
        {{-- ====================================================== --}}

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
                placeholder="Masukkan hasil SNPMB..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('hasil', $dataSNPMB->hasil) }}</textarea>

        </div>


        {{-- ====================================================== --}}
        {{-- KETERANGAN --}}
        {{-- ====================================================== --}}

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
            >{{ old('keterangan', $dataSNPMB->keterangan) }}</textarea>

        </div>


        {{-- ====================================================== --}}
        {{-- TOMBOL --}}
        {{-- ====================================================== --}}

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">

            <a
                href="{{ url('/data-snpmb') }}"
                class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-semibold"
            >
                Batal
            </a>

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