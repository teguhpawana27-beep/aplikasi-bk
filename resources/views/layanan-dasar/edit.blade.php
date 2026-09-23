@extends('layouts.app')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | DATA DEFAULT
    |--------------------------------------------------------------------------
    | Dibuat aman supaya Blade tidak error jika salah satu collection
    | tidak tersedia dari controller.
    */

    $tahunAjaranData = $tahunAjaran ?? collect();
    $kelasData = $kelasList ?? collect();
    $siswaData = $siswaKelas ?? collect();
    $semuaSiswaData = $semuaSiswa ?? collect();
    $skkpdData = $skkpd ?? collect();
    $metodeData = $metode ?? collect();
    $guruBKData = $guruBK ?? collect();
    $jenisLayananData = $jenisLayanan ?? [
        'Bimbingan Klasikal',
        'Bimbingan Kelompok',
        'Bimbingan Kelas Besar / Lintas Kelas',
        'Pengembangan Media BK',
    ];

    /*
    |--------------------------------------------------------------------------
    | PESERTA YANG SUDAH DIPILIH
    |--------------------------------------------------------------------------
    */

    $pesertaTerpilih = collect($layananDasar->peserta ?? [])
        ->pluck('siswa_id')
        ->map(fn ($id) => (string) $id)
        ->values()
        ->toArray();

    /*
    |--------------------------------------------------------------------------
    | KELAS SAAT INI
    |--------------------------------------------------------------------------
    */

    $kelasSaatIni = $layananDasar->kelas_id
        ? $kelasData->firstWhere('id', $layananDasar->kelas_id)
        : null;

@endphp


<div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- ================================================================
         HEADER
    ================================================================= --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        <div>
            <a
                href="{{ route('layanan-dasar.index') }}"
                class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-800"
            >
                ← Kembali
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Edit Layanan Dasar
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Perbarui data layanan dasar Bimbingan dan Konseling.
            </p>
        </div>

    </div>


    {{-- ================================================================
         ERROR VALIDASI
    ================================================================= --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-5">

            <p class="font-semibold text-red-700">
                Terdapat kesalahan:
            </p>

            <ul class="mt-2 list-disc list-inside space-y-1 text-sm text-red-600">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- ================================================================
         FORM
    ================================================================= --}}
    <form
        action="{{ route('layanan-dasar.update', $layananDasar) }}"
        method="POST"
        class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    >

        @csrf
        @method('PUT')


        {{-- ============================================================
             HEADER CARD
        ============================================================= --}}
        <div class="border-b border-slate-200 px-6 py-6 sm:px-8">

            <h2 class="text-lg font-semibold text-slate-800">
                Data Layanan Dasar
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Silakan perbarui informasi layanan yang diperlukan.
            </p>

        </div>


        {{-- ============================================================
             ISI FORM
        ============================================================= --}}
        <div class="space-y-7 px-6 py-7 sm:px-8">


            {{-- ========================================================
                 JENIS LAYANAN
            ========================================================= --}}
            <div>

                <label
                    for="jenis_layanan"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Jenis Layanan <span class="text-red-500">*</span>
                </label>

                <select
                    name="jenis_layanan"
                    id="jenis_layanan"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

                    <option value="">
                        -- Pilih Jenis Layanan --
                    </option>

                    @foreach ($jenisLayananData as $jenis)
                        <option
                            value="{{ $jenis }}"
                            @selected(old('jenis_layanan', $layananDasar->jenis_layanan) == $jenis)
                        >
                            {{ $jenis }}
                        </option>
                    @endforeach

                </select>

            </div>


            {{-- ========================================================
                 TANGGAL + TAHUN AJARAN
            ========================================================= --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Tanggal --}}
                <div>

                    <label
                        for="tanggal"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Tanggal <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        id="tanggal"
                        required
                        value="{{ old('tanggal', $layananDasar->tanggal?->format('Y-m-d')) }}"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                </div>


                {{-- Tahun Ajaran --}}
                <div>

                    <label
                        for="tahun_ajaran_id"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Tahun Ajaran <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="tahun_ajaran_id"
                        id="tahun_ajaran_id"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                        <option value="">
                            -- Pilih Tahun Ajaran --
                        </option>

                        @foreach ($tahunAjaranData as $item)

                            <option
                                value="{{ $item->id }}"
                                @selected(old('tahun_ajaran_id', $layananDasar->tahun_ajaran_id) == $item->id)
                            >
                                {{ $item->nama }}
                            </option>

                        @endforeach

                    </select>

                    <p class="mt-2 text-xs text-slate-400">
                        Pilih tahun ajaran untuk melihat tingkat.
                    </p>

                </div>

            </div>


            {{-- ========================================================
                 GURU BK
            ========================================================= --}}
            <div>

                <label
                    for="guru_bk_id"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Guru BK <span class="text-red-500">*</span>
                </label>

                <select
                    name="guru_bk_id"
                    id="guru_bk_id"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

                    <option value="">
                        -- Pilih Guru BK --
                    </option>

                    @foreach ($guruBKData as $guru)

                        <option
                            value="{{ $guru->id }}"
                            @selected(old('guru_bk_id', $layananDasar->guru_bk_id) == $guru->id)
                        >
                            {{ $guru->nama_lengkap }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- ========================================================
                 HIERARCHY KELAS
            ========================================================= --}}

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6">

                <div class="mb-5">

                    <h3 class="text-base font-semibold text-slate-800">
                        Sasaran / Kelas
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Pilih secara berurutan: Tahun Ajaran → Tingkat → Jurusan → Kelas.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                    {{-- ==================================================
                         TINGKAT
                    ================================================== --}}
                    <div>

                        <label
                            for="tingkat"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Tingkat
                        </label>

                        <select
                            id="tingkat"
                            name="tingkat"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                            <option value="">
                                -- Pilih Tingkat --
                            </option>

                        </select>

                    </div>


                    {{-- ==================================================
                         JURUSAN
                    ================================================== --}}
                    <div>

                        <label
                            for="jurusan_id"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Jurusan
                        </label>

                        <select
                            id="jurusan_id"
                            name="jurusan_id"
                            disabled
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:bg-slate-100 disabled:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                            <option value="">
                                -- Pilih Jurusan --
                            </option>

                        </select>

                    </div>


                    {{-- ==================================================
                         KELAS
                    ================================================== --}}
                    <div>

                        <label
                            for="kelas_id"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Kelas
                        </label>

                        <select
                            name="kelas_id"
                            id="kelas_id"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:bg-slate-100 disabled:text-slate-400"
                        >

                            <option value="">
                                -- Pilih Kelas --
                            </option>

                        </select>

                    </div>

                </div>


                {{-- Hidden current class --}}
                <input
                    type="hidden"
                    id="kelas_lama"
                    value="{{ old('kelas_id', $layananDasar->kelas_id) }}"
                >

            </div>


            {{-- ========================================================
                 PESERTA SISWA
            ========================================================= --}}
            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-5 py-5">

                    <h3 class="text-base font-semibold text-slate-800">
                        Siswa Peserta
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Pilih siswa berdasarkan kelas yang sudah dipilih.
                    </p>

                </div>


                <div class="p-5">

                    <div
                        id="peserta_empty"
                        class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center"
                    >

                        <p class="text-sm text-slate-500">
                            Silakan pilih Tahun Ajaran, Tingkat, Jurusan, dan Kelas terlebih dahulu.
                        </p>

                    </div>


                    <div
                        id="peserta_container"
                        class="hidden"
                    >

                        <div class="mb-4 flex items-center justify-between">

                            <p class="text-sm font-medium text-slate-700">
                                Daftar siswa
                            </p>

                            <button
                                type="button"
                                id="pilih_semua"
                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-800"
                            >
                                Pilih Semua
                            </button>

                        </div>


                        <div
                            id="siswa_list"
                            class="max-h-80 overflow-y-auto rounded-xl border border-slate-200"
                        >
                        </div>


                        <p class="mt-3 text-xs text-slate-400">
                            Siswa yang dicentang akan menjadi peserta layanan ini.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 SKKPD + METODE
            ========================================================= --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- SKKPD --}}
                <div>

                    <label
                        for="skkpd_id"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        SKKPD <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="skkpd_id"
                        id="skkpd_id"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                        <option value="">
                            -- Pilih SKKPD --
                        </option>

                        @foreach ($skkpdData as $item)

                            <option
                                value="{{ $item->id }}"
                                @selected(old('skkpd_id', $layananDasar->skkpd_id) == $item->id)
                            >
                                {{ $item->kode }} — {{ $item->nama }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Metode --}}
                <div>

                    <label
                        for="metode_id"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Metode BK <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="metode_id"
                        id="metode_id"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                        <option value="">
                            -- Pilih Metode --
                        </option>

                        @foreach ($metodeData as $item)

                            <option
                                value="{{ $item->id }}"
                                @selected(old('metode_id', $layananDasar->metode_id) == $item->id)
                            >
                                {{ $item->nama }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- ========================================================
                 TOPIK
            ========================================================= --}}
            <div>

                <label
                    for="topik"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Topik <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="topik"
                    id="topik"
                    required
                    value="{{ old('topik', $layananDasar->topik) }}"
                    placeholder="Masukkan topik layanan..."
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

            </div>


            {{-- ========================================================
                 SASARAN
            ========================================================= --}}
            <div>

                <label
                    for="sasaran"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Sasaran
                </label>

                <textarea
                    name="sasaran"
                    id="sasaran"
                    rows="3"
                    placeholder="Masukkan sasaran layanan..."
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('sasaran', $layananDasar->sasaran) }}</textarea>

            </div>


            {{-- ========================================================
                 URAIAN KEGIATAN
            ========================================================= --}}
            <div>

                <label
                    for="uraian_kegiatan"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Uraian Kegiatan
                </label>

                <textarea
                    name="uraian_kegiatan"
                    id="uraian_kegiatan"
                    rows="5"
                    placeholder="Jelaskan kegiatan layanan yang dilaksanakan..."
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('uraian_kegiatan', $layananDasar->uraian_kegiatan) }}</textarea>

            </div>


            {{-- ========================================================
                 HASIL
            ========================================================= --}}
            <div>

                <label
                    for="hasil"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Hasil
                </label>

                <textarea
                    name="hasil"
                    id="hasil"
                    rows="4"
                    placeholder="Masukkan hasil layanan..."
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('hasil', $layananDasar->hasil) }}</textarea>

            </div>


            {{-- ========================================================
                 EVALUASI
            ========================================================= --}}
            <div>

                <label
                    for="evaluasi"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Evaluasi
                </label>

                <textarea
                    name="evaluasi"
                    id="evaluasi"
                    rows="4"
                    placeholder="Masukkan hasil evaluasi layanan..."
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('evaluasi', $layananDasar->evaluasi) }}</textarea>

            </div>


            {{-- ========================================================
                 KETERANGAN
            ========================================================= --}}
            <div>

                <label
                    for="keterangan"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Keterangan
                </label>

                <textarea
                    name="keterangan"
                    id="keterangan"
                    rows="3"
                    placeholder="Tambahkan keterangan jika diperlukan..."
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('keterangan', $layananDasar->keterangan) }}</textarea>

            </div>


        </div>


        {{-- ============================================================
             FOOTER BUTTON
        ============================================================= --}}
        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">

            <a
                href="{{ route('layanan-dasar.index') }}"
                class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-700 ring-1 ring-inset ring-slate-300 transition hover:bg-slate-100"
            >
                Batal
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>


{{-- ====================================================================
     JAVASCRIPT HIERARCHY
===================================================================== --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DATA DARI CONTROLLER
    |--------------------------------------------------------------------------
    */

    const kelasData = @json($kelasData);

    const siswaData = @json($siswaData);

    const semuaSiswaData = @json($semuaSiswaData);

    const pesertaLama = @json($pesertaTerpilih);


    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    const tahunAjaran = document.getElementById('tahun_ajaran_id');

    const tingkat = document.getElementById('tingkat');

    const jurusan = document.getElementById('jurusan_id');

    const kelas = document.getElementById('kelas_id');

    const siswaList = document.getElementById('siswa_list');

    const pesertaContainer =
        document.getElementById('peserta_container');

    const pesertaEmpty =
        document.getElementById('peserta_empty');

    const pilihSemua =
        document.getElementById('pilih_semua');


    /*
    |--------------------------------------------------------------------------
    | NILAI KELAS LAMA
    |--------------------------------------------------------------------------
    */

    const kelasLama =
        document.getElementById('kelas_lama').value;


    /*
    |--------------------------------------------------------------------------
    | RESET SELECT
    |--------------------------------------------------------------------------
    */

    function resetSelect(select, text) {

        select.innerHTML =
            '<option value="">' +
            text +
            '</option>';

        select.disabled = false;

    }


    /*
    |--------------------------------------------------------------------------
    | TINGKAT
    |--------------------------------------------------------------------------
    */

    function loadTingkat() {

        const tahunId = tahunAjaran.value;

        tingkat.innerHTML =
            '<option value="">-- Pilih Tingkat --</option>';

        jurusan.innerHTML =
            '<option value="">-- Pilih Jurusan --</option>';

        kelas.innerHTML =
            '<option value="">-- Pilih Kelas --</option>';

        jurusan.disabled = true;

        kelas.disabled = false;

        renderEmptySiswa();


        if (!tahunId) {

            tingkat.disabled = true;

            return;

        }


        const tingkatData = [
            ...new Set(
                kelasData
                    .filter(item =>
                        String(item.tahun_ajaran_id) ===
                        String(tahunId)
                    )
                    .map(item => item.tingkat)
            )
        ];


        tingkatData.sort();


        tingkatData.forEach(item => {

            const option =
                document.createElement('option');

            option.value = item;

            option.textContent = item;

            tingkat.appendChild(option);

        });


        tingkat.disabled = false;


        /*
        |--------------------------------------------------------------------------
        | JIKA EDIT
        |--------------------------------------------------------------------------
        */

        if (kelasLama) {

            const kelasSekarang =
                kelasData.find(item =>
                    String(item.id) === String(kelasLama)
                );


            if (
                kelasSekarang &&
                String(kelasSekarang.tahun_ajaran_id) ===
                String(tahunId)
            ) {

                tingkat.value =
                    kelasSekarang.tingkat;

                loadJurusan();

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | JURUSAN
    |--------------------------------------------------------------------------
    */

    function loadJurusan() {

        const tahunId =
            tahunAjaran.value;

        const tingkatValue =
            tingkat.value;


        jurusan.innerHTML =
            '<option value="">-- Pilih Jurusan --</option>';

        kelas.innerHTML =
            '<option value="">-- Pilih Kelas --</option>';

        kelas.disabled = true;

        renderEmptySiswa();


        if (!tahunId || !tingkatValue) {

            jurusan.disabled = true;

            return;

        }


        const filtered =
            kelasData.filter(item =>
                String(item.tahun_ajaran_id) ===
                String(tahunId)
                &&
                String(item.tingkat) ===
                String(tingkatValue)
            );


        const mapJurusan =
            new Map();


        filtered.forEach(item => {

            if (!mapJurusan.has(String(item.jurusan_id))) {

                mapJurusan.set(
                    String(item.jurusan_id),
                    {
                        id: item.jurusan_id,
                        nama:
                            item.jurusan_nama ??
                            item.jurusan_kode ??
                            'Jurusan'
                    }
                );

            }

        });


        mapJurusan.forEach(item => {

            const option =
                document.createElement('option');

            option.value = item.id;

            option.textContent = item.nama;

            jurusan.appendChild(option);

        });


        jurusan.disabled = false;


        /*
        |--------------------------------------------------------------------------
        | JIKA EDIT
        |--------------------------------------------------------------------------
        */

        if (kelasLama) {

            const kelasSekarang =
                kelasData.find(item =>
                    String(item.id) ===
                    String(kelasLama)
                );


            if (
                kelasSekarang &&
                String(kelasSekarang.tingkat) ===
                String(tingkatValue)
            ) {

                jurusan.value =
                    kelasSekarang.jurusan_id;

                loadKelas();

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | KELAS
    |--------------------------------------------------------------------------
    */

    function loadKelas() {

        const tahunId =
            tahunAjaran.value;

        const tingkatValue =
            tingkat.value;

        const jurusanId =
            jurusan.value;


        kelas.innerHTML =
            '<option value="">-- Pilih Kelas --</option>';


        if (
            !tahunId ||
            !tingkatValue ||
            !jurusanId
        ) {

            kelas.disabled = true;

            renderEmptySiswa();

            return;

        }


        const filtered =
            kelasData.filter(item =>
                String(item.tahun_ajaran_id) ===
                String(tahunId)
                &&
                String(item.tingkat) ===
                String(tingkatValue)
                &&
                String(item.jurusan_id) ===
                String(jurusanId)
            );


        filtered.forEach(item => {

            const option =
                document.createElement('option');

            option.value = item.id;

            option.textContent =
                item.nama_kelas;

            kelas.appendChild(option);

        });


        kelas.disabled = false;


        /*
        |--------------------------------------------------------------------------
        | JIKA EDIT
        |--------------------------------------------------------------------------
        */

        if (kelasLama) {

            const ditemukan =
                filtered.find(item =>
                    String(item.id) ===
                    String(kelasLama)
                );


            if (ditemukan) {

                kelas.value =
                    ditemukan.id;

                loadSiswa();

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SISWA
    |--------------------------------------------------------------------------
    */

    function loadSiswa() {

        const kelasId =
            kelas.value;


        siswaList.innerHTML = '';


        if (!kelasId) {

            renderEmptySiswa();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Cari siswa berdasarkan kelas
        |--------------------------------------------------------------------------
        */

        let filtered =
            siswaData.filter(item =>
                String(item.kelas_id) ===
                String(kelasId)
            );


        /*
        |--------------------------------------------------------------------------
        | Jika siswa dari peserta lama tidak ditemukan
        |--------------------------------------------------------------------------
        | Ambil dari semua siswa supaya data lama tetap bisa tampil.
        |--------------------------------------------------------------------------
        */

        pesertaLama.forEach(id => {

            const sudahAda =
                filtered.some(item =>
                    String(item.siswa_id ?? item.id) ===
                    String(id)
                );


            if (!sudahAda) {

                const tambahan =
                    semuaSiswaData.find(item =>
                        String(item.id) ===
                        String(id)
                    );


                if (tambahan) {

                    filtered.push({
                        id: tambahan.id,
                        siswa_id: tambahan.id,
                        nis: tambahan.nis,
                        nisn: tambahan.nisn,
                        nama_lengkap:
                            tambahan.nama_lengkap,
                        jenis_kelamin:
                            tambahan.jenis_kelamin,
                        status:
                            tambahan.status
                    });

                }

            }

        });


        if (filtered.length === 0) {

            pesertaContainer.classList.add('hidden');

            pesertaEmpty.classList.remove('hidden');

            pesertaEmpty.innerHTML =
                '<p class="text-sm text-slate-500">' +
                'Tidak ada siswa pada kelas yang dipilih.' +
                '</p>';

            return;

        }


        pesertaEmpty.classList.add('hidden');

        pesertaContainer.classList.remove('hidden');


        /*
        |--------------------------------------------------------------------------
        | Urutkan nama
        |--------------------------------------------------------------------------
        */

        filtered.sort((a, b) =>
            String(a.nama_lengkap ?? '')
                .localeCompare(
                    String(b.nama_lengkap ?? ''),
                    'id'
                )
        );


        /*
        |--------------------------------------------------------------------------
        | Render siswa
        |--------------------------------------------------------------------------
        */

        filtered.forEach(item => {

            const siswaId =
                item.siswa_id ?? item.id;


            const checked =
                pesertaLama.includes(
                    String(siswaId)
                );


            const wrapper =
                document.createElement('label');

            wrapper.className =
                'flex cursor-pointer items-center gap-4 border-b border-slate-100 px-4 py-3 last:border-b-0 hover:bg-slate-50';


            wrapper.innerHTML = `

                <input
                    type="checkbox"
                    name="siswa_id[]"
                    value="${siswaId}"
                    ${checked ? 'checked' : ''}
                    class="siswa-checkbox h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >

                <div class="min-w-0 flex-1">

                    <p class="font-medium text-slate-800">
                        ${escapeHtml(item.nama_lengkap ?? '-')}
                    </p>

                    <p class="mt-0.5 text-xs text-slate-400">
                        NIS: ${escapeHtml(item.nis ?? '-')}
                    </p>

                </div>

            `;


            siswaList.appendChild(wrapper);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | EMPTY SISWA
    |--------------------------------------------------------------------------
    */

    function renderEmptySiswa() {

        pesertaContainer.classList.add('hidden');

        pesertaEmpty.classList.remove('hidden');

        pesertaEmpty.innerHTML =
            '<p class="text-sm text-slate-500">' +
            'Silakan pilih Tahun Ajaran, Tingkat, Jurusan, dan Kelas terlebih dahulu.' +
            '</p>';

        siswaList.innerHTML = '';

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /*
    |--------------------------------------------------------------------------
    | PILIH SEMUA
    |--------------------------------------------------------------------------
    */

    pilihSemua.addEventListener('click', function () {

        const checkboxes =
            document.querySelectorAll(
                '.siswa-checkbox'
            );


        if (!checkboxes.length) {

            return;

        }


        const semuaDicentang =
            [...checkboxes].every(
                checkbox => checkbox.checked
            );


        checkboxes.forEach(
            checkbox => {
                checkbox.checked =
                    !semuaDicentang;
            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | EVENT
    |--------------------------------------------------------------------------
    */

    tahunAjaran.addEventListener(
        'change',
        function () {

            loadTingkat();

        }
    );


    tingkat.addEventListener(
        'change',
        function () {

            loadJurusan();

        }
    );


    jurusan.addEventListener(
        'change',
        function () {

            loadKelas();

        }
    );


    kelas.addEventListener(
        'change',
        function () {

            loadSiswa();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    tingkat.disabled = true;

    jurusan.disabled = true;

    kelas.disabled = true;


    /*
    |--------------------------------------------------------------------------
    | LOAD DATA EDIT
    |--------------------------------------------------------------------------
    */

    if (tahunAjaran.value) {

        loadTingkat();

    }

});

</script>

@endsection