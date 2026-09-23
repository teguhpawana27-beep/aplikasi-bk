@extends('layouts.app')

@section('content')

<div
    x-data='{
        tahunAjaranId: "{{ old("tahun_ajaran_id", "") }}",
        tingkat: "{{ old("tingkat", "") }}",
        jurusanId: "{{ old("jurusan_id", "") }}",
        kelasId: "{{ old("kelas_id", "") }}",

        search: "",

        selectedSiswa: @json(
            array_map(
                "strval",
                old("siswa_id", [])
            )
        ),

        kelasData: @json($kelas),
        siswaData: @json($siswa),

        init() {
            this.tahunAjaranId = String(this.tahunAjaranId || "");
            this.tingkat = String(this.tingkat || "");
            this.jurusanId = String(this.jurusanId || "");
            this.kelasId = String(this.kelasId || "");

            this.selectedSiswa =
                (this.selectedSiswa || []).map(String);

            /*
             * Jika halaman kembali karena validation error
             * dan kelas sudah terisi, ambil tingkat + jurusan
             * dari data kelas.
             */
            if (this.kelasId) {
                const kelas = this.kelasData.find(
                    item => String(item.id) === String(this.kelasId)
                );

                if (kelas) {
                    this.tahunAjaranId =
                        String(kelas.tahun_ajaran_id);

                    this.tingkat =
                        String(kelas.tingkat);

                    this.jurusanId =
                        String(kelas.jurusan_id);
                }
            }
        },

        /*
         * =========================================================
         * TINGKAT
         * =========================================================
         */
        get tingkatList() {

            if (!this.tahunAjaranId) {
                return [];
            }

            return [
                ...new Set(
                    this.kelasData
                        .filter(item =>
                            String(item.tahun_ajaran_id) ===
                            String(this.tahunAjaranId)
                        )
                        .map(item =>
                            String(item.tingkat)
                        )
                )
            ];
        },

        /*
         * =========================================================
         * JURUSAN
         * =========================================================
         */
        get jurusanList() {

            if (
                !this.tahunAjaranId ||
                !this.tingkat
            ) {
                return [];
            }

            const hasil = [];

            this.kelasData
                .filter(item =>
                    String(item.tahun_ajaran_id) ===
                    String(this.tahunAjaranId)
                    &&
                    String(item.tingkat) ===
                    String(this.tingkat)
                )
                .forEach(item => {

                    const sudahAda =
                        hasil.some(
                            jurusan =>
                                String(jurusan.id) ===
                                String(item.jurusan_id)
                        );

                    if (!sudahAda) {

                        hasil.push({
                            id: item.jurusan_id,
                            kode: item.jurusan_kode,
                            nama: item.jurusan_nama
                        });

                    }

                });

            return hasil;
        },

        /*
         * =========================================================
         * KELAS
         * =========================================================
         */
        get kelasList() {

            if (
                !this.tahunAjaranId ||
                !this.tingkat ||
                !this.jurusanId
            ) {
                return [];
            }

            return this.kelasData.filter(item =>
                String(item.tahun_ajaran_id) ===
                String(this.tahunAjaranId)
                &&
                String(item.tingkat) ===
                String(this.tingkat)
                &&
                String(item.jurusan_id) ===
                String(this.jurusanId)
            );
        },

        /*
         * =========================================================
         * SISWA
         * =========================================================
         *
         * PENTING:
         * Siswa HANYA ditampilkan berdasarkan kelas_id.
         * Jadi setelah pilih:
         *
         * Tahun Ajaran
         *      ↓
         * Tingkat
         *      ↓
         * Jurusan
         *      ↓
         * Kelas
         *
         * siswa yang muncul hanya siswa dari kelas tersebut.
         */
        get siswaList() {

            if (!this.kelasId) {
                return [];
            }

            const keyword =
                String(this.search || "")
                    .toLowerCase()
                    .trim();

            return this.siswaData.filter(siswa => {

                /*
                 * FILTER UTAMA:
                 * siswa harus berada di kelas yang dipilih.
                 */
                if (
                    String(siswa.kelas_id) !==
                    String(this.kelasId)
                ) {
                    return false;
                }

                /*
                 * Pastikan tahun ajaran juga sama.
                 */
                if (
                    this.tahunAjaranId &&
                    String(siswa.tahun_ajaran_id) !==
                    String(this.tahunAjaranId)
                ) {
                    return false;
                }

                /*
                 * Search nama / NIS / NISN
                 */
                if (!keyword) {
                    return true;
                }

                const nama =
                    String(
                        siswa.nama_lengkap || ""
                    ).toLowerCase();

                const nis =
                    String(
                        siswa.nis || ""
                    ).toLowerCase();

                const nisn =
                    String(
                        siswa.nisn || ""
                    ).toLowerCase();

                return (
                    nama.includes(keyword) ||
                    nis.includes(keyword) ||
                    nisn.includes(keyword)
                );
            });
        },

        /*
         * =========================================================
         * RESET TINGKAT
         * =========================================================
         */
        resetTingkat() {

            this.jurusanId = "";
            this.kelasId = "";
            this.selectedSiswa = [];
            this.search = "";
        },

        /*
         * =========================================================
         * RESET JURUSAN
         * =========================================================
         */
        resetJurusan() {

            this.kelasId = "";
            this.selectedSiswa = [];
            this.search = "";
        },

        /*
         * =========================================================
         * RESET KELAS
         * =========================================================
         */
        resetKelas() {

            this.selectedSiswa = [];
            this.search = "";
        },

        /*
         * =========================================================
         * CEK SISWA
         * =========================================================
         */
        isSelected(id) {

            return this.selectedSiswa
                .map(String)
                .includes(String(id));
        },

        /*
         * =========================================================
         * PILIH / BATAL PILIH SISWA
         * =========================================================
         */
        toggleSiswa(id) {

            const idString = String(id);

            if (this.isSelected(id)) {

                this.selectedSiswa =
                    this.selectedSiswa.filter(
                        item =>
                            String(item) !==
                            idString
                    );

            } else {

                this.selectedSiswa.push(idString);

            }
        },

        /*
         * =========================================================
         * PILIH SEMUA SISWA
         * =========================================================
         */
        toggleSemuaSiswa() {

            const visibleIds =
                this.siswaList.map(
                    siswa => String(siswa.id)
                );

            const semuaDipilih =
                visibleIds.length > 0 &&
                visibleIds.every(
                    id => this.isSelected(id)
                );

            if (semuaDipilih) {

                this.selectedSiswa =
                    this.selectedSiswa.filter(
                        id =>
                            !visibleIds.includes(
                                String(id)
                            )
                    );

            } else {

                visibleIds.forEach(id => {

                    if (!this.isSelected(id)) {
                        this.selectedSiswa.push(id);
                    }

                });

            }
        }
    }'
>

    <div class="space-y-6">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Tambah Layanan Responsif
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Tambahkan data layanan responsif siswa.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- ERROR --}}
        {{-- ===================================================== --}}

        @if($errors->any())

            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">

                <div class="font-semibold text-red-700 mb-2">
                    Terjadi kesalahan:
                </div>

                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ===================================================== --}}
        {{-- FORM --}}
        {{-- ===================================================== --}}

        <form
            action="{{ route('layanan-responsif.store') }}"
            method="POST"
            class="bg-white rounded-xl shadow-sm border border-gray-200"
        >

            @csrf


            <div class="p-8 space-y-8">


                {{-- ================================================= --}}
                {{-- DATA LAYANAN --}}
                {{-- ================================================= --}}

                <div>

                    <h2 class="text-xl font-semibold text-gray-800 mb-6">
                        Data Layanan
                    </h2>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        {{-- GURU BK --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Guru BK
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="guru_bk_id"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                                <option value="">
                                    Pilih Guru BK
                                </option>

                                @foreach($guruBK as $guru)

                                    <option
                                        value="{{ $guru->id }}"
                                        @selected(
                                            old('guru_bk_id') == $guru->id
                                        )
                                    >
                                        {{ $guru->nama_lengkap }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- TAHUN AJARAN --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tahun Ajaran
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="tahun_ajaran_id"
                                x-model="tahunAjaranId"
                                @change="resetTingkat()"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                                <option value="">
                                    Pilih Tahun Ajaran
                                </option>

                                @foreach($tahunAjaran as $tahun)

                                    <option
                                        value="{{ $tahun->id }}"
                                    >
                                        {{ $tahun->nama }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- TINGKAT --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tingkat
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                x-model="tingkat"
                                @change="resetTingkat()"
                                :disabled="!tahunAjaranId"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500
                                       disabled:bg-gray-100
                                       disabled:text-gray-400"
                            >

                                <option value="">
                                    Pilih Tingkat
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

                            <p
                                x-show="!tahunAjaranId"
                                class="text-xs text-gray-400 mt-2"
                            >
                                Pilih tahun ajaran terlebih dahulu.
                            </p>

                        </div>


                        {{-- JURUSAN --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jurusan
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="jurusan_id"
                                x-model="jurusanId"
                                @change="resetJurusan()"
                                :disabled="!tingkat"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500
                                       disabled:bg-gray-100
                                       disabled:text-gray-400"
                            >

                                <option value="">
                                    Pilih Jurusan
                                </option>

                                <template
                                    x-for="jurusan in jurusanList"
                                    :key="jurusan.id"
                                >

                                    <option
                                        :value="jurusan.id"
                                        x-text="
                                            (jurusan.kode
                                                ? jurusan.kode + ' - '
                                                : ''
                                            ) + jurusan.nama
                                        "
                                    ></option>

                                </template>

                            </select>

                            <p
                                x-show="!tingkat"
                                class="text-xs text-gray-400 mt-2"
                            >
                                Pilih tingkat terlebih dahulu.
                            </p>

                        </div>


                        {{-- KELAS --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kelas
                            </label>

                            <select
                                name="kelas_id"
                                x-model="kelasId"
                                @change="resetKelas()"
                                :disabled="!jurusanId"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500
                                       disabled:bg-gray-100
                                       disabled:text-gray-400"
                            >

                                <option value="">
                                    Pilih Kelas
                                </option>

                                <template
                                    x-for="item in kelasList"
                                    :key="item.id"
                                >

                                    <option
                                        :value="item.id"
                                        x-text="item.nama_kelas"
                                    ></option>

                                </template>

                            </select>

                            <p
                                x-show="!jurusanId"
                                class="text-xs text-gray-400 mt-2"
                            >
                                Pilih jurusan terlebih dahulu.
                            </p>

                        </div>


                        {{-- TANGGAL --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="date"
                                name="tanggal"
                                value="{{ old('tanggal', date('Y-m-d')) }}"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                        </div>


                        {{-- JENIS LAYANAN --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Layanan
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="jenis_layanan"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                                <option value="">
                                    Pilih Jenis Layanan
                                </option>

                                @foreach($jenisLayanan as $jenis)

                                    <option
                                        value="{{ $jenis }}"
                                        @selected(
                                            old('jenis_layanan') == $jenis
                                        )
                                    >
                                        {{ $jenis }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>


                <div class="border-t border-gray-200"></div>


                {{-- ================================================= --}}
                {{-- DATA SISWA --}}
                {{-- ================================================= --}}

                <div>

                    <h2 class="text-xl font-semibold text-gray-800 mb-6">
                        Data Siswa
                    </h2>


                    <div class="space-y-4">


                        {{-- SEARCH --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">

                                Nama Siswa
                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="text"
                                x-model="search"
                                :disabled="!kelasId"
                                placeholder="Cari nama siswa atau NIS..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500
                                       disabled:bg-gray-100
                                       disabled:text-gray-400"
                            >

                        </div>


                        {{-- PILIH SEMUA --}}
                        <div
                            x-show="kelasId && siswaList.length > 0"
                            class="flex items-center justify-between"
                        >

                            <button
                                type="button"
                                @click="toggleSemuaSiswa()"
                                class="text-sm font-medium
                                       text-blue-600
                                       hover:text-blue-800"
                            >
                                Pilih Semua Siswa
                            </button>


                            <span
                                class="text-sm text-gray-500"
                                x-text="
                                    selectedSiswa.length +
                                    ' siswa dipilih'
                                "
                            ></span>

                        </div>


                        {{-- LIST SISWA --}}
                        <div
                            class="border border-gray-200
                                   rounded-lg overflow-hidden"
                        >


                            {{-- BELUM PILIH KELAS --}}
                            <template x-if="!kelasId">

                                <div class="p-8 text-center">

                                    <div class="text-3xl mb-2">
                                        👥
                                    </div>

                                    <p class="text-sm text-gray-500">
                                        Silakan pilih Tahun Ajaran,
                                        Tingkat, Jurusan, dan Kelas
                                        terlebih dahulu.
                                    </p>

                                </div>

                            </template>


                            {{-- SUDAH PILIH KELAS TAPI TIDAK ADA SISWA --}}
                            <template
                                x-if="
                                    kelasId &&
                                    siswaList.length === 0
                                "
                            >

                                <div class="p-8 text-center">

                                    <div class="text-3xl mb-2">
                                        📚
                                    </div>

                                    <p class="text-sm text-gray-500">

                                        Tidak ada siswa pada kelas
                                        yang dipilih.

                                    </p>

                                </div>

                            </template>


                            {{-- SISWA --}}
                            <template
                                x-if="
                                    kelasId &&
                                    siswaList.length > 0
                                "
                            >

                                <div class="divide-y divide-gray-100">

                                    <template
                                        x-for="
                                            siswaItem in siswaList
                                        "
                                        :key="siswaItem.id"
                                    >

                                        <label
                                            class="flex items-center
                                                   gap-4 p-4
                                                   hover:bg-gray-50
                                                   cursor-pointer"
                                        >


                                            <input
                                                type="checkbox"
                                                name="siswa_id[]"
                                                :value="siswaItem.id"
                                                :checked="
                                                    isSelected(
                                                        siswaItem.id
                                                    )
                                                "
                                                @change="
                                                    toggleSiswa(
                                                        siswaItem.id
                                                    )
                                                "
                                                class="rounded
                                                       border-gray-300
                                                       text-blue-600
                                                       focus:ring-blue-500"
                                            >


                                            <div>

                                                <div
                                                    class="font-semibold
                                                           text-gray-800"
                                                    x-text="
                                                        siswaItem.nama_lengkap
                                                    "
                                                ></div>


                                                <div
                                                    class="text-xs
                                                           text-gray-500
                                                           mt-1"
                                                >

                                                    NIS:
                                                    <span
                                                        x-text="
                                                            siswaItem.nis
                                                                || '-'
                                                        "
                                                    ></span>

                                                    <span
                                                        class="mx-1"
                                                    >
                                                        •
                                                    </span>

                                                    Kelas:
                                                    <span
                                                        x-text="
                                                            siswaItem.nama_kelas
                                                                || '-'
                                                        "
                                                    ></span>

                                                </div>

                                            </div>

                                        </label>

                                    </template>

                                </div>

                            </template>

                        </div>

                    </div>

                </div>


                <div class="border-t border-gray-200"></div>


                {{-- ================================================= --}}
                {{-- DETAIL LAYANAN --}}
                {{-- ================================================= --}}

                <div>

                    <h2 class="text-xl font-semibold text-gray-800 mb-6">
                        Detail Layanan
                    </h2>


                    <div class="space-y-6">


                        {{-- BIDANG --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">

                                Bidang Layanan
                                <span class="text-red-500">*</span>

                            </label>

                            <select
                                name="bidang_layanan_id"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                                <option value="">
                                    Pilih Bidang Layanan
                                </option>

                                @foreach($bidangLayanan as $bidang)

                                    <option
                                        value="{{ $bidang->id }}"
                                        @selected(
                                            old('bidang_layanan_id') ==
                                            $bidang->id
                                        )
                                    >
                                        {{ $bidang->nama }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- PENDEKATAN --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">

                                Pendekatan
                                <span class="text-red-500">*</span>

                            </label>

                            <select
                                name="pendekatan_id"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                                <option value="">
                                    Pilih Pendekatan
                                </option>

                                @foreach($pendekatan as $item)

                                    <option
                                        value="{{ $item->id }}"
                                        @selected(
                                            old('pendekatan_id') ==
                                            $item->id
                                        )
                                    >
                                        {{ $item->nama }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- URAIAN MASALAH --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">

                                Uraian Masalah
                                <span class="text-red-500">*</span>

                            </label>

                            <textarea
                                name="uraian_masalah"
                                rows="5"
                                required
                                placeholder="Tuliskan uraian masalah..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >{{ old('uraian_masalah') }}</textarea>

                        </div>


                        {{-- TINDAK LANJUT --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tindak Lanjut
                            </label>

                            <textarea
                                name="tindak_lanjut"
                                rows="4"
                                placeholder="Tuliskan tindak lanjut..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >{{ old('tindak_lanjut') }}</textarea>

                        </div>


                        {{-- STATUS --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">

                                Status Kasus
                                <span class="text-red-500">*</span>

                            </label>

                            <select
                                name="status_kasus"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                                <option value="">
                                    Pilih Status
                                </option>

                                <option
                                    value="Belum Ditangani"
                                    @selected(
                                        old('status_kasus') ==
                                        'Belum Ditangani'
                                    )
                                >
                                    Belum Ditangani
                                </option>

                                <option
                                    value="Dalam Proses"
                                    @selected(
                                        old('status_kasus') ==
                                        'Dalam Proses'
                                    )
                                >
                                    Dalam Proses
                                </option>

                                <option
                                    value="Selesai"
                                    @selected(
                                        old('status_kasus') ==
                                        'Selesai'
                                    )
                                >
                                    Selesai
                                </option>

                                <option
                                    value="Dirujuk"
                                    @selected(
                                        old('status_kasus') ==
                                        'Dirujuk'
                                    )
                                >
                                    Dirujuk
                                </option>

                            </select>

                        </div>


                        {{-- KETERANGAN --}}
                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Keterangan
                            </label>

                            <textarea
                                name="keterangan"
                                rows="4"
                                placeholder="Keterangan tambahan..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >{{ old('keterangan') }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- FOOTER --}}
            {{-- ===================================================== --}}

            <div
                class="px-8 py-5 bg-gray-50 border-t border-gray-200
                       flex justify-end gap-3"
            >

                <a
                    href="{{ route('layanan-responsif.index') }}"
                    class="inline-flex items-center
                           px-4 py-2.5
                           bg-white border border-gray-300
                           text-gray-700 text-sm font-semibold
                           rounded-lg hover:bg-gray-50 transition"
                >
                    Batal
                </a>


                <button
                    type="submit"
                    class="inline-flex items-center
                           px-4 py-2.5
                           bg-blue-600 text-white
                           text-sm font-semibold
                           rounded-lg hover:bg-blue-700 transition"
                >
                    Simpan Data
                </button>

            </div>

        </form>

    </div>

</div>

@endsection