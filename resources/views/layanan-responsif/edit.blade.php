@extends('layouts.app')

@section('content')

<div
    x-data="layananResponsifEdit()"
    x-init="init()"
    class="max-w-5xl mx-auto"
>

    {{-- HEADER --}}
    <div class="mb-6">

        <a
            href="{{ route('layanan-responsif.index') }}"
            class="inline-flex items-center gap-2
                   text-sm text-slate-500
                   hover:text-blue-600 mb-4"
        >
            ← Kembali
        </a>

        <h1 class="text-2xl font-bold text-slate-800">
            Edit Layanan Responsif
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Perbarui data pelaksanaan layanan responsif BK.
        </p>

    </div>


    {{-- ERROR --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-200
                    bg-red-50 px-5 py-4">

            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('layanan-responsif.update', $layananResponsif) }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        <div class="bg-white rounded-2xl border border-slate-200
                    shadow-sm p-6 space-y-6">


            {{-- ===================================================== --}}
            {{-- DATA PELAKSANAAN --}}
            {{-- ===================================================== --}}

            <div>

                <h2 class="text-lg font-semibold text-slate-800 mb-4">
                    Data Pelaksanaan
                </h2>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                    {{-- GURU BK --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Guru BK <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="guru_bk_id"
                            required
                            class="w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                Pilih Guru BK
                            </option>

                            @foreach($guruBK as $guru)

                                <option
                                    value="{{ $guru->id }}"
                                    {{ old('guru_bk_id', $layananResponsif->guru_bk_id) == $guru->id ? 'selected' : '' }}
                                >
                                    {{ $guru->nama_lengkap }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TAHUN AJARAN --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="tahun_ajaran_id"
                            x-model="tahunAjaranId"
                            @change="ubahTahunAjaran()"
                            required
                            class="w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500"
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

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Tingkat <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="tingkat"
                            x-model="tingkat"
                            @change="ubahTingkat()"
                            required
                            class="w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500"
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
                                    x-text="'Kelas ' + item"
                                ></option>

                            </template>

                        </select>

                    </div>


                    {{-- JURUSAN --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Jurusan <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="jurusan_id"
                            x-model="jurusanId"
                            @change="ubahJurusan()"
                            :disabled="!tingkat"
                            required
                            class="w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   disabled:bg-gray-100 disabled:text-gray-400"
                        >

                            <option value="">
                                Pilih Jurusan
                            </option>

                            <template
                                x-for="jurusan in jurusanList"
                                :key="jurusan.id"
                            >

                                <option
                                    :value="String(jurusan.id)"
                                    x-text="
                                        (jurusan.kode
                                            ? jurusan.kode + ' - '
                                            : ''
                                        ) + jurusan.nama
                                    "
                                ></option>

                            </template>

                        </select>

                    </div>


                    {{-- KELAS --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Kelas
                        </label>

                        <select
                            name="kelas_id"
                            x-model="kelasId"
                            @change="ubahKelas()"
                            :disabled="!jurusanId"
                            class="w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   disabled:bg-gray-100 disabled:text-gray-400"
                        >

                            <option value="">
                                Pilih Kelas
                            </option>

                            <template
                                x-for="kelas in kelasList"
                                :key="kelas.id"
                            >

                                <option
                                    :value="String(kelas.id)"
                                    x-text="kelas.nama_kelas"
                                ></option>

                            </template>

                        </select>

                    </div>


                    {{-- TANGGAL --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            value="{{ old('tanggal', $layananResponsif->tanggal?->format('Y-m-d')) }}"
                            required
                            class="w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>


                    {{-- JENIS LAYANAN --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Jenis Layanan <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="jenis_layanan"
                            required
                            class="w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                Pilih Jenis Layanan
                            </option>

                            @foreach($jenisLayanan as $jenis)

                                <option
                                    value="{{ $jenis }}"
                                    {{ old('jenis_layanan', $layananResponsif->jenis_layanan) === $jenis ? 'selected' : '' }}
                                >
                                    {{ $jenis }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            <div class="border-t border-slate-200"></div>


            {{-- ===================================================== --}}
            {{-- DATA SISWA --}}
            {{-- ===================================================== --}}

            <div>

                <h2 class="text-lg font-semibold text-slate-800 mb-4">
                    Data Siswa
                </h2>

                <p class="text-sm text-slate-500 mb-4">
                    Siswa akan otomatis difilter berdasarkan Tahun Ajaran,
                    Tingkat, Jurusan, dan Kelas yang dipilih.
                </p>


                {{-- SEARCH --}}
                <div class="mb-4">

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Cari Nama Siswa
                    </label>

                    <input
                        type="text"
                        x-model="search"
                        placeholder="Cari nama siswa, NIS, atau NISN..."
                        :disabled="!kelasId"
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500
                               disabled:bg-gray-100 disabled:text-gray-400"
                    >

                </div>


                {{-- PILIH SEMUA --}}
                <div
                    x-show="kelasId && siswaList.length > 0"
                    class="mb-3 flex items-center justify-between"
                >

                    <button
                        type="button"
                        @click="toggleSemuaSiswa()"
                        class="text-sm font-medium text-blue-600
                               hover:text-blue-800"
                    >
                        <span
                            x-text="semuaDipilih ? 'Batalkan Semua' : 'Pilih Semua Siswa'"
                        ></span>
                    </button>


                    <span
                        class="text-sm text-gray-500"
                        x-text="selectedSiswa.length + ' siswa dipilih'"
                    ></span>

                </div>


                {{-- BELUM PILIH KELAS --}}
                <div
                    x-show="!kelasId"
                    class="rounded-xl border border-dashed
                           border-slate-300 bg-slate-50
                           px-5 py-8 text-center"
                >

                    <div class="text-sm font-medium text-slate-600">
                        Pilih kelas terlebih dahulu
                    </div>

                    <div class="mt-1 text-xs text-slate-400">
                        Daftar siswa akan muncul setelah kelas dipilih.
                    </div>

                </div>


                {{-- ADA KELAS --}}
                <div
                    x-show="kelasId"
                    class="border border-gray-200 rounded-xl overflow-hidden"
                >

                    <template x-if="siswaList.length > 0">

                        <div class="divide-y divide-gray-100">

                            <template
                                x-for="siswaItem in siswaList"
                                :key="siswaItem.id"
                            >

                                <label
                                    class="flex items-center gap-4
                                           p-4 hover:bg-gray-50
                                           cursor-pointer"
                                >

                                    <input
                                        type="checkbox"
                                        name="siswa_id[]"
                                        :value="siswaItem.id"
                                        :checked="isSelected(siswaItem.id)"
                                        @change="toggleSiswa(siswaItem.id)"
                                        class="rounded border-gray-300
                                               text-blue-600
                                               focus:ring-blue-500"
                                    >


                                    <div>

                                        <div
                                            class="font-semibold text-gray-800"
                                            x-text="siswaItem.nama_lengkap"
                                        ></div>

                                        <div class="text-xs text-gray-500 mt-1">

                                            NIS:
                                            <span
                                                x-text="siswaItem.nis ?? '-'"
                                            ></span>

                                            <span class="mx-1">
                                                •
                                            </span>

                                            NISN:
                                            <span
                                                x-text="siswaItem.nisn ?? '-'"
                                            ></span>

                                        </div>

                                    </div>

                                </label>

                            </template>

                        </div>

                    </template>


                    {{-- TIDAK ADA SISWA --}}
                    <template x-if="siswaList.length === 0">

                        <div class="p-8 text-center text-sm text-gray-500">

                            Tidak ada siswa pada kelas yang dipilih.

                        </div>

                    </template>

                </div>

            </div>


            <div class="border-t border-slate-200"></div>


            {{-- ===================================================== --}}
            {{-- BIDANG & PENDEKATAN --}}
            {{-- ===================================================== --}}

            <div>

                <h2 class="text-lg font-semibold text-slate-800 mb-4">
                    Bidang dan Pendekatan
                </h2>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                    {{-- BIDANG --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Bidang Layanan <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="bidang_layanan_id"
                            required
                            class="w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                Pilih Bidang Layanan
                            </option>

                            @foreach($bidangLayanan as $bidang)

                                <option
                                    value="{{ $bidang->id }}"
                                    {{ old('bidang_layanan_id', $layananResponsif->bidang_layanan_id) == $bidang->id ? 'selected' : '' }}
                                >
                                    {{ $bidang->nama }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- PENDEKATAN --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Pendekatan <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="pendekatan_id"
                            required
                            class="w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                Pilih Pendekatan
                            </option>

                            @foreach($pendekatan as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ old('pendekatan_id', $layananResponsif->pendekatan_id) == $item->id ? 'selected' : '' }}
                                >
                                    {{ $item->nama }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            <div class="border-t border-slate-200"></div>


            {{-- ===================================================== --}}
            {{-- URAIAN LAYANAN --}}
            {{-- ===================================================== --}}

            <div>

                <h2 class="text-lg font-semibold text-slate-800 mb-4">
                    Uraian Layanan
                </h2>


                {{-- URAIAN MASALAH --}}
                <div class="mb-5">

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Uraian Masalah
                        <span class="text-red-500">*</span>
                    </label>

                    <textarea
                        name="uraian_masalah"
                        rows="5"
                        required
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('uraian_masalah', $layananResponsif->uraian_masalah) }}</textarea>

                </div>


                {{-- TINDAK LANJUT --}}
                <div class="mb-5">

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Tindak Lanjut
                    </label>

                    <textarea
                        name="tindak_lanjut"
                        rows="4"
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('tindak_lanjut', $layananResponsif->tindak_lanjut) }}</textarea>

                </div>


                {{-- KETERANGAN --}}
                <div class="mb-5">

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        rows="3"
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('keterangan', $layananResponsif->keterangan) }}</textarea>

                </div>


                {{-- STATUS --}}
                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Status Kasus <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="status_kasus"
                        required
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih Status
                        </option>

                        <option
                            value="Aktif"
                            {{ old('status_kasus', $layananResponsif->status_kasus) === 'Aktif' ? 'selected' : '' }}
                        >
                            Aktif
                        </option>

                        <option
                            value="Selesai"
                            {{ old('status_kasus', $layananResponsif->status_kasus) === 'Selesai' ? 'selected' : '' }}
                        >
                            Selesai
                        </option>

                        <option
                            value="Rujuk"
                            {{ old('status_kasus', $layananResponsif->status_kasus) === 'Rujuk' ? 'selected' : '' }}
                        >
                            Rujuk
                        </option>

                    </select>

                </div>

            </div>


            {{-- BUTTON --}}
            <div class="flex items-center justify-end gap-3 pt-4">

                <a
                    href="{{ route('layanan-responsif.index') }}"
                    class="rounded-xl border border-slate-300
                           px-5 py-3 text-sm font-semibold
                           text-slate-600 hover:bg-slate-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600
                           px-6 py-3 text-sm font-semibold
                           text-white hover:bg-blue-700 transition"
                >
                    Simpan Perubahan
                </button>

            </div>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- ALPINE JS --}}
{{-- ============================================================= --}}

<script>

function layananResponsifEdit() {

    return {

        {{-- NILAI AWAL --}}
        tahunAjaranId:
            @json(
                old(
                    'tahun_ajaran_id',
                    $layananResponsif->tahun_ajaran_id
                )
            ),

        tingkat:
            @json(
                old(
                    'tingkat',
                    $layananResponsif->tingkat
                )
            ),

        jurusanId:
            '',

        kelasId:
            @json(
                old(
                    'kelas_id',
                    $layananResponsif->kelas_id
                )
            ),

        search: '',


        {{-- SISWA YANG SUDAH TERPILIH --}}
        selectedSiswa:
            @json(
                old(
                    'siswa_id',
                    $pesertaTerpilih
                )
            ),


        {{-- DATA KELAS DARI CONTROLLER --}}
        kelasData:
            @json($kelas),


        {{-- DATA SISWA DARI CONTROLLER --}}
        siswaData:
            @json($siswa),


        {{-- ================================================= --}}
        {{-- INIT --}}
        {{-- ================================================= --}}

        init() {

            this.tahunAjaranId =
                String(this.tahunAjaranId || '');

            this.tingkat =
                String(this.tingkat || '');

            this.kelasId =
                String(this.kelasId || '');

            this.selectedSiswa =
                (this.selectedSiswa || [])
                    .map(id => String(id));


            {{-- CARI KELAS YANG SEDANG DIEDIT --}}
            const kelasAktif =
                this.kelasData.find(
                    item =>
                        String(item.id) ===
                        String(this.kelasId)
                );


            {{-- ISI JURUSAN OTOMATIS --}}
            if (kelasAktif) {

                this.jurusanId =
                    String(kelasAktif.jurusan_id);

            }

        },


        {{-- ================================================= --}}
        {{-- LIST TINGKAT --}}
        {{-- ================================================= --}}

        get tingkatList() {

            if (!this.tahunAjaranId) {

                return [];

            }

            return [
                ...new Set(

                    this.kelasData

                        .filter(
                            kelas =>
                                String(
                                    kelas.tahun_ajaran_id
                                ) ===
                                String(
                                    this.tahunAjaranId
                                )
                        )

                        .map(
                            kelas =>
                                String(kelas.tingkat)
                        )

                )
            ];

        },


        {{-- ================================================= --}}
        {{-- LIST JURUSAN --}}
        {{-- ================================================= --}}

        get jurusanList() {

            if (
                !this.tahunAjaranId ||
                !this.tingkat
            ) {

                return [];

            }


            const hasil = [];


            this.kelasData

                .filter(
                    kelas =>

                        String(
                            kelas.tahun_ajaran_id
                        ) ===
                        String(
                            this.tahunAjaranId
                        )

                        &&

                        String(
                            kelas.tingkat
                        ) ===
                        String(
                            this.tingkat
                        )
                )

                .forEach(kelas => {

                    const id =
                        String(kelas.jurusan_id);


                    if (
                        !hasil.some(
                            item =>
                                String(item.id) === id
                        )
                    ) {

                        hasil.push({

                            id: kelas.jurusan_id,

                            kode:
                                kelas.jurusan_kode,

                            nama:
                                kelas.jurusan_nama

                        });

                    }

                });


            return hasil;

        },


        {{-- ================================================= --}}
        {{-- LIST KELAS --}}
        {{-- ================================================= --}}

        get kelasList() {

            if (
                !this.tahunAjaranId ||
                !this.tingkat ||
                !this.jurusanId
            ) {

                return [];

            }


            return this.kelasData.filter(

                kelas =>

                    String(
                        kelas.tahun_ajaran_id
                    ) ===
                    String(
                        this.tahunAjaranId
                    )

                    &&

                    String(
                        kelas.tingkat
                    ) ===
                    String(
                        this.tingkat
                    )

                    &&

                    String(
                        kelas.jurusan_id
                    ) ===
                    String(
                        this.jurusanId
                    )

            );

        },


        {{-- ================================================= --}}
        {{-- FILTER SISWA --}}
        {{-- ================================================= --}}

        get siswaList() {

            if (!this.kelasId) {

                return [];

            }


            const keyword =
                this.search
                    .toLowerCase()
                    .trim();


            return this.siswaData.filter(

                siswa => {

                    {{-- FILTER TAHUN AJARAN --}}
                    if (
                        String(
                            siswa.tahun_ajaran_id
                        ) !==
                        String(
                            this.tahunAjaranId
                        )
                    ) {

                        return false;

                    }


                    {{-- FILTER TINGKAT --}}
                    if (
                        String(
                            siswa.tingkat
                        ) !==
                        String(
                            this.tingkat
                        )
                    ) {

                        return false;

                    }


                    {{-- FILTER JURUSAN --}}
                    if (
                        String(
                            siswa.jurusan_id
                        ) !==
                        String(
                            this.jurusanId
                        )
                    ) {

                        return false;

                    }


                    {{-- FILTER KELAS --}}
                    if (
                        String(
                            siswa.kelas_id
                        ) !==
                        String(
                            this.kelasId
                        )
                    ) {

                        return false;

                    }


                    {{-- FILTER SEARCH --}}
                    if (!keyword) {

                        return true;

                    }


                    const nama =
                        String(
                            siswa.nama_lengkap ?? ''
                        )
                        .toLowerCase();


                    const nis =
                        String(
                            siswa.nis ?? ''
                        )
                        .toLowerCase();


                    const nisn =
                        String(
                            siswa.nisn ?? ''
                        )
                        .toLowerCase();


                    return (

                        nama.includes(keyword)

                        ||

                        nis.includes(keyword)

                        ||

                        nisn.includes(keyword)

                    );

                }

            );

        },


        {{-- ================================================= --}}
        {{-- CEK SISWA --}}
        {{-- ================================================= --}}

        isSelected(id) {

            return this.selectedSiswa
                .map(String)
                .includes(
                    String(id)
                );

        },


        {{-- ================================================= --}}
        {{-- PILIH SISWA --}}
        {{-- ================================================= --}}

        toggleSiswa(id) {

            id = String(id);


            if (
                this.selectedSiswa
                    .map(String)
                    .includes(id)
            ) {

                this.selectedSiswa =
                    this.selectedSiswa.filter(
                        item =>
                            String(item) !== id
                    );

            } else {

                this.selectedSiswa.push(id);

            }

        },


        {{-- ================================================= --}}
        {{-- PILIH SEMUA --}}
        {{-- ================================================= --}}

        get semuaDipilih() {

            if (
                this.siswaList.length === 0
            ) {

                return false;

            }


            return this.siswaList.every(

                siswa =>
                    this.selectedSiswa
                        .map(String)
                        .includes(
                            String(siswa.id)
                        )

            );

        },


        toggleSemuaSiswa() {

            const ids =
                this.siswaList.map(
                    siswa =>
                        String(siswa.id)
                );


            if (this.semuaDipilih) {

                this.selectedSiswa =
                    this.selectedSiswa.filter(

                        id =>
                            !ids.includes(
                                String(id)
                            )

                    );

            } else {

                ids.forEach(id => {

                    if (
                        !this.selectedSiswa
                            .map(String)
                            .includes(id)
                    ) {

                        this.selectedSiswa.push(id);

                    }

                });

            }

        },


        {{-- ================================================= --}}
        {{-- GANTI TAHUN AJARAN --}}
        {{-- ================================================= --}}

        ubahTahunAjaran() {

            this.tingkat = '';

            this.jurusanId = '';

            this.kelasId = '';

            this.selectedSiswa = [];

            this.search = '';

        },


        {{-- ================================================= --}}
        {{-- GANTI TINGKAT --}}
        {{-- ================================================= --}}

        ubahTingkat() {

            this.jurusanId = '';

            this.kelasId = '';

            this.selectedSiswa = [];

            this.search = '';

        },


        {{-- ================================================= --}}
        {{-- GANTI JURUSAN --}}
        {{-- ================================================= --}}

        ubahJurusan() {

            this.kelasId = '';

            this.selectedSiswa = [];

            this.search = '';

        },


        {{-- ================================================= --}}
        {{-- GANTI KELAS --}}
        {{-- ================================================= --}}

        ubahKelas() {

            this.selectedSiswa = [];

            this.search = '';

        }

    };

}

</script>

@endsection