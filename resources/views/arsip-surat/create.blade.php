@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Tambah Arsip Surat
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Tambahkan dan simpan arsip surat siswa.
        </p>
    </div>


    {{-- =========================================================
         ERROR VALIDATION
    ========================================================== --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="font-semibold text-red-700">
                Terdapat kesalahan:
            </div>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- =========================================================
         FORM
    ========================================================== --}}
    <form
        action="{{ route('arsip-surat.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6"
    >

        @csrf


        {{-- =====================================================
             INFORMASI SURAT
        ====================================================== --}}
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">

            <div class="mb-6 border-b border-slate-200 pb-5">

                <h2 class="text-xl font-bold text-slate-800">
                    Informasi Surat
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Masukkan informasi utama surat.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">


                {{-- JENIS SURAT --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Jenis Surat
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="jenis_surat"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            -- Pilih Jenis Surat --
                        </option>

                        @foreach ($jenisSurat as $jenis)

                            <option
                                value="{{ $jenis }}"
                                {{ old('jenis_surat') == $jenis ? 'selected' : '' }}
                            >
                                {{ $jenis }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- NOMOR SURAT --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Nomor Surat
                    </label>

                    <input
                        type="text"
                        name="nomor_surat"
                        value="{{ old('nomor_surat') }}"
                        placeholder="Contoh: 421.5/001/BK/2026"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- TANGGAL SURAT --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Tanggal Surat
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="tanggal_surat"
                        value="{{ old('tanggal_surat', date('Y-m-d')) }}"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- TAHUN AJARAN --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Tahun Ajaran
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="tahun_ajaran_id"
                        id="tahun_ajaran_id"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            -- Pilih Tahun Ajaran --
                        </option>

                        @foreach ($tahunAjaran as $item)

                            <option
                                value="{{ $item->id }}"
                                {{ old('tahun_ajaran_id') == $item->id ? 'selected' : '' }}
                            >
                                {{ $item->nama ?? $item->tahun ?? $item->tahun_ajaran ?? 'Tahun Ajaran' }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>



        {{-- =====================================================
             DATA SISWA
        ====================================================== --}}
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">

            <div class="mb-6 border-b border-slate-200 pb-5">

                <h2 class="text-xl font-bold text-slate-800">
                    Data Siswa
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Pilih siswa melalui hierarchy Tahun Ajaran, Tingkat,
                    Jurusan, Kelas, dan Siswa.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">


                {{-- =================================================
                     TINGKAT
                ================================================== --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Tingkat
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="tingkat"
                        name="tingkat"
                        required
                        disabled
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400"
                    >

                        <option value="">
                            -- Pilih Tahun Ajaran Terlebih Dahulu --
                        </option>

                    </select>

                    <p
                        id="tingkat_loading"
                        class="mt-2 hidden text-xs text-blue-600"
                    >
                        Memuat tingkat...
                    </p>

                </div>



                {{-- =================================================
                     JURUSAN
                ================================================== --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Jurusan
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="jurusan_id"
                        name="jurusan_id"
                        required
                        disabled
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400"
                    >

                        <option value="">
                            -- Pilih Tingkat Terlebih Dahulu --
                        </option>

                    </select>

                    <p
                        id="jurusan_loading"
                        class="mt-2 hidden text-xs text-blue-600"
                    >
                        Memuat jurusan...
                    </p>

                </div>



                {{-- =================================================
                     KELAS
                ================================================== --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Kelas
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="kelas_id"
                        name="kelas_id"
                        required
                        disabled
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400"
                    >

                        <option value="">
                            -- Pilih Jurusan Terlebih Dahulu --
                        </option>

                    </select>

                    <p
                        id="kelas_loading"
                        class="mt-2 hidden text-xs text-blue-600"
                    >
                        Memuat kelas...
                    </p>

                </div>



                {{-- =================================================
                     SISWA
                ================================================== --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Siswa
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="siswa_id"
                        name="siswa_id"
                        required
                        disabled
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400"
                    >

                        <option value="">
                            -- Pilih Kelas Terlebih Dahulu --
                        </option>

                    </select>

                    <p
                        id="siswa_loading"
                        class="mt-2 hidden text-xs text-blue-600"
                    >
                        Memuat siswa...
                    </p>

                </div>



                {{-- =================================================
                     GURU BK
                ================================================== --}}
                <div class="md:col-span-2">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Guru BK
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="guru_bk_id"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            -- Pilih Guru BK --
                        </option>

                        @foreach ($guruBK as $guru)

                            <option
                                value="{{ $guru->id }}"
                                {{ old('guru_bk_id') == $guru->id ? 'selected' : '' }}
                            >
                                {{ $guru->nama_lengkap }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>



        {{-- =====================================================
             DETAIL SURAT
        ====================================================== --}}
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">

            <div class="mb-6 border-b border-slate-200 pb-5">

                <h2 class="text-xl font-bold text-slate-800">
                    Detail Surat
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Lengkapi informasi isi dan file surat.
                </p>

            </div>


            <div class="space-y-6">


                {{-- PERIHAL --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Perihal
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="perihal"
                        value="{{ old('perihal') }}"
                        required
                        placeholder="Masukkan perihal surat"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>



                {{-- ISI RINGKAS --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Isi Ringkas
                    </label>

                    <textarea
                        name="isi_ringkas"
                        rows="5"
                        placeholder="Masukkan ringkasan isi surat"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >{{ old('isi_ringkas') }}</textarea>

                </div>



                {{-- FILE --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        File Surat
                    </label>

                    <input
                        type="file"
                        name="file"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="block w-full rounded-xl border border-slate-300 bg-white text-sm text-slate-600 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200"
                    >

                    <p class="mt-2 text-xs text-slate-500">
                        Format: PDF, DOC, DOCX, JPG, JPEG, PNG.
                        Maksimal 5 MB.
                    </p>

                </div>



                {{-- KETERANGAN --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        rows="4"
                        placeholder="Keterangan tambahan"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >{{ old('keterangan') }}</textarea>

                </div>

            </div>

        </div>



        {{-- =====================================================
             BUTTON
        ====================================================== --}}
        <div class="flex flex-col justify-end gap-3 sm:flex-row">

            <a
                href="{{ route('arsip-surat.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Batal
            </a>


            <button
                type="submit"
                id="submitButton"
                class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
            >
                Simpan Arsip Surat
            </button>

        </div>

    </form>

</div>



{{-- =============================================================
     JAVASCRIPT HIERARCHY
     Tahun Ajaran
          ↓
     Tingkat
          ↓
     Jurusan
          ↓
     Kelas
          ↓
     Siswa
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ELEMENT
    ========================================================== */

    const tahunAjaran = document.getElementById('tahun_ajaran_id');

    const tingkat = document.getElementById('tingkat');

    const jurusan = document.getElementById('jurusan_id');

    const kelas = document.getElementById('kelas_id');

    const siswa = document.getElementById('siswa_id');


    /* =========================================================
       LOADING ELEMENT
    ========================================================== */

    const loadingTingkat =
        document.getElementById('tingkat_loading');

    const loadingJurusan =
        document.getElementById('jurusan_loading');

    const loadingKelas =
        document.getElementById('kelas_loading');

    const loadingSiswa =
        document.getElementById('siswa_loading');


    /* =========================================================
       OLD VALUE
    ========================================================== */

    const oldTahun =
        @json(old('tahun_ajaran_id'));

    const oldTingkat =
        @json(old('tingkat'));

    const oldJurusan =
        @json(old('jurusan_id'));

    const oldKelas =
        @json(old('kelas_id'));

    const oldSiswa =
        @json(old('siswa_id'));


    /* =========================================================
       ROUTE AJAX
    ========================================================== */

    const urlTingkat =
        "{{ route('arsip-surat.tingkat') }}";

    const urlJurusan =
        "{{ route('arsip-surat.jurusan') }}";

    const urlKelas =
        "{{ route('arsip-surat.kelas') }}";

    const urlSiswa =
        "{{ route('arsip-surat.siswa') }}";


    /* =========================================================
       RESET TINGKAT
    ========================================================== */

    function resetTingkat() {

        tingkat.innerHTML = `
            <option value="">
                -- Pilih Tahun Ajaran Terlebih Dahulu --
            </option>
        `;

        tingkat.disabled = true;

    }


    /* =========================================================
       RESET JURUSAN
    ========================================================== */

    function resetJurusan() {

        jurusan.innerHTML = `
            <option value="">
                -- Pilih Tingkat Terlebih Dahulu --
            </option>
        `;

        jurusan.disabled = true;

    }


    /* =========================================================
       RESET KELAS
    ========================================================== */

    function resetKelas() {

        kelas.innerHTML = `
            <option value="">
                -- Pilih Jurusan Terlebih Dahulu --
            </option>
        `;

        kelas.disabled = true;

    }


    /* =========================================================
       RESET SISWA
    ========================================================== */

    function resetSiswa() {

        siswa.innerHTML = `
            <option value="">
                -- Pilih Kelas Terlebih Dahulu --
            </option>
        `;

        siswa.disabled = true;

    }


    /* =========================================================
       SHOW / HIDE LOADING
    ========================================================== */

    function showLoading(element) {

        if (element) {
            element.classList.remove('hidden');
        }

    }


    function hideLoading(element) {

        if (element) {
            element.classList.add('hidden');
        }

    }


    /* =========================================================
       LOAD TINGKAT
    ========================================================== */

    async function loadTingkat(selectedValue = null) {

        resetTingkat();
        resetJurusan();
        resetKelas();
        resetSiswa();

        const tahunId = tahunAjaran.value;

        if (!tahunId) {
            return;
        }

        showLoading(loadingTingkat);

        tingkat.innerHTML = `
            <option value="">
                -- Memuat Tingkat --
            </option>
        `;

        try {

            const response = await fetch(
                urlTingkat +
                '?tahun_ajaran_id=' +
                encodeURIComponent(tahunId),
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error(
                    'HTTP ' + response.status
                );
            }

            const data = await response.json();

            resetTingkat();

            if (!Array.isArray(data) || data.length === 0) {

                tingkat.innerHTML = `
                    <option value="">
                        -- Tidak Ada Tingkat --
                    </option>
                `;

                return;
            }


            tingkat.innerHTML = `
                <option value="">
                    -- Pilih Tingkat --
                </option>
            `;


            data.forEach(function (item) {

                const value =
                    typeof item === 'object'
                        ? item.tingkat
                        : item;

                if (
                    value === null ||
                    value === undefined ||
                    value === ''
                ) {
                    return;
                }

                const option =
                    document.createElement('option');

                option.value = value;

                option.textContent =
                    value;

                if (
                    selectedValue !== null &&
                    String(selectedValue) === String(value)
                ) {
                    option.selected = true;
                }

                tingkat.appendChild(option);

            });


            tingkat.disabled = false;

        } catch (error) {

            console.error(
                'Gagal memuat tingkat:',
                error
            );

            resetTingkat();

            tingkat.innerHTML = `
                <option value="">
                    -- Gagal Memuat Tingkat --
                </option>
            `;

        } finally {

            hideLoading(loadingTingkat);

        }

    }


    /* =========================================================
       LOAD JURUSAN
    ========================================================== */

    async function loadJurusan(selectedValue = null) {

        resetJurusan();
        resetKelas();
        resetSiswa();

        const tahunId =
            tahunAjaran.value;

        const tingkatValue =
            tingkat.value;

        if (
            !tahunId ||
            !tingkatValue
        ) {
            return;
        }

        showLoading(loadingJurusan);

        jurusan.innerHTML = `
            <option value="">
                -- Memuat Jurusan --
            </option>
        `;

        try {

            const url =
                urlJurusan +
                '?tahun_ajaran_id=' +
                encodeURIComponent(tahunId) +
                '&tingkat=' +
                encodeURIComponent(tingkatValue);


            const response =
                await fetch(
                    url,
                    {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'HTTP ' + response.status
                );

            }


            const data =
                await response.json();


            resetJurusan();


            if (
                !Array.isArray(data) ||
                data.length === 0
            ) {

                jurusan.innerHTML = `
                    <option value="">
                        -- Tidak Ada Jurusan --
                    </option>
                `;

                return;
            }


            jurusan.innerHTML = `
                <option value="">
                    -- Pilih Jurusan --
                </option>
            `;


            data.forEach(function (item) {

                const option =
                    document.createElement('option');


                option.value =
                    item.id;


                option.textContent =
                    item.nama ??
                    item.kode ??
                    'Jurusan';


                if (
                    selectedValue !== null &&
                    String(selectedValue) ===
                    String(item.id)
                ) {

                    option.selected = true;

                }


                jurusan.appendChild(option);

            });


            jurusan.disabled = false;


        } catch (error) {

            console.error(
                'Gagal memuat jurusan:',
                error
            );


            resetJurusan();


            jurusan.innerHTML = `
                <option value="">
                    -- Gagal Memuat Jurusan --
                </option>
            `;

        } finally {

            hideLoading(loadingJurusan);

        }

    }


    /* =========================================================
       LOAD KELAS
    ========================================================== */

    async function loadKelas(selectedValue = null) {

        resetKelas();
        resetSiswa();

        const tahunId =
            tahunAjaran.value;

        const tingkatValue =
            tingkat.value;

        const jurusanValue =
            jurusan.value;


        if (
            !tahunId ||
            !tingkatValue ||
            !jurusanValue
        ) {
            return;
        }


        showLoading(loadingKelas);


        kelas.innerHTML = `
            <option value="">
                -- Memuat Kelas --
            </option>
        `;


        try {

            const url =
                urlKelas +
                '?tahun_ajaran_id=' +
                encodeURIComponent(tahunId) +
                '&tingkat=' +
                encodeURIComponent(tingkatValue) +
                '&jurusan_id=' +
                encodeURIComponent(jurusanValue);


            const response =
                await fetch(
                    url,
                    {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'HTTP ' + response.status
                );

            }


            const data =
                await response.json();


            resetKelas();


            if (
                !Array.isArray(data) ||
                data.length === 0
            ) {

                kelas.innerHTML = `
                    <option value="">
                        -- Tidak Ada Kelas --
                    </option>
                `;

                return;
            }


            kelas.innerHTML = `
                <option value="">
                    -- Pilih Kelas --
                </option>
            `;


            data.forEach(function (item) {

                const option =
                    document.createElement('option');


                option.value =
                    item.id;


                option.textContent =
                    item.nama_kelas ??
                    item.nama ??
                    'Kelas';


                if (
                    selectedValue !== null &&
                    String(selectedValue) ===
                    String(item.id)
                ) {

                    option.selected = true;

                }


                kelas.appendChild(option);

            });


            kelas.disabled = false;


        } catch (error) {

            console.error(
                'Gagal memuat kelas:',
                error
            );


            resetKelas();


            kelas.innerHTML = `
                <option value="">
                    -- Gagal Memuat Kelas --
                </option>
            `;

        } finally {

            hideLoading(loadingKelas);

        }

    }


    /* =========================================================
       LOAD SISWA
    ========================================================== */

    async function loadSiswa(selectedValue = null) {

        resetSiswa();

        const kelasId =
            kelas.value;


        if (!kelasId) {
            return;
        }


        showLoading(loadingSiswa);


        siswa.innerHTML = `
            <option value="">
                -- Memuat Siswa --
            </option>
        `;


        try {

            const url =
                urlSiswa +
                '?kelas_id=' +
                encodeURIComponent(kelasId);


            const response =
                await fetch(
                    url,
                    {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'HTTP ' + response.status
                );

            }


            const data =
                await response.json();


            resetSiswa();


            if (
                !Array.isArray(data) ||
                data.length === 0
            ) {

                siswa.innerHTML = `
                    <option value="">
                        -- Tidak Ada Siswa --
                    </option>
                `;

                return;
            }


            siswa.innerHTML = `
                <option value="">
                    -- Pilih Siswa --
                </option>
            `;


            data.forEach(function (item) {

                const option =
                    document.createElement('option');


                option.value =
                    item.id;


                option.textContent =
                    (item.nis ?? '-') +
                    ' - ' +
                    (item.nama_lengkap ?? '-');


                if (
                    selectedValue !== null &&
                    String(selectedValue) ===
                    String(item.id)
                ) {

                    option.selected = true;

                }


                siswa.appendChild(option);

            });


            siswa.disabled = false;


        } catch (error) {

            console.error(
                'Gagal memuat siswa:',
                error
            );


            resetSiswa();


            siswa.innerHTML = `
                <option value="">
                    -- Gagal Memuat Siswa --
                </option>
            `;

        } finally {

            hideLoading(loadingSiswa);

        }

    }


    /* =========================================================
       EVENT TAHUN AJARAN
    ========================================================== */

    tahunAjaran.addEventListener(
        'change',
        function () {

            loadTingkat();

        }
    );


    /* =========================================================
       EVENT TINGKAT
    ========================================================== */

    tingkat.addEventListener(
        'change',
        function () {

            loadJurusan();

        }
    );


    /* =========================================================
       EVENT JURUSAN
    ========================================================== */

    jurusan.addEventListener(
        'change',
        function () {

            loadKelas();

        }
    );


    /* =========================================================
       EVENT KELAS
    ========================================================== */

    kelas.addEventListener(
        'change',
        function () {

            loadSiswa();

        }
    );


    /* =========================================================
       RESTORE OLD VALUE
       Dipakai kalau validasi Laravel gagal.
    ========================================================== */

    async function restoreOldValue() {

        if (!oldTahun) {
            return;
        }


        tahunAjaran.value =
            oldTahun;


        await loadTingkat(
            oldTingkat
        );


        if (!oldTingkat) {
            return;
        }


        tingkat.value =
            oldTingkat;


        await loadJurusan(
            oldJurusan
        );


        if (!oldJurusan) {
            return;
        }


        jurusan.value =
            oldJurusan;


        await loadKelas(
            oldKelas
        );


        if (!oldKelas) {
            return;
        }


        kelas.value =
            oldKelas;


        await loadSiswa(
            oldSiswa
        );


        if (oldSiswa) {

            siswa.value =
                oldSiswa;

        }

    }


    /* =========================================================
       INITIAL LOAD
    ========================================================== */

    restoreOldValue();

});

</script>

@endsection