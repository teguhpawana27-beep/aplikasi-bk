@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-50 px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-5xl">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <p class="text-sm font-semibold text-blue-600">
                    Arsip Surat
                </p>

                <h1 class="text-2xl font-bold text-gray-900">
                    Edit Arsip Surat
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Perbarui data arsip surat.
                </p>
            </div>

            <a
                href="{{ route('arsip-surat.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
            >
                Kembali
            </a>

        </div>


        {{-- ERROR --}}
        @if ($errors->any())

            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">

                <div class="font-semibold text-red-800">
                    Terdapat kesalahan:
                </div>

                <ul class="mt-2 list-disc pl-5 text-sm text-red-700">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- FORM --}}
        <form
            action="{{ route('arsip-surat.update', $arsipSurat->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')


            {{-- =====================================================
                 INFORMASI SURAT
            ====================================================== --}}
            <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="mb-6 border-b border-gray-100 pb-4">

                    <h2 class="text-lg font-bold text-gray-900">
                        Informasi Surat
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Informasi utama surat.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                    {{-- JENIS SURAT --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Jenis Surat
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="jenis_surat"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                            <option value="">
                                -- Pilih Jenis Surat --
                            </option>

                            @foreach ($jenisSurat as $jenis)

                                <option
                                    value="{{ $jenis }}"
                                    {{ old('jenis_surat', $arsipSurat->jenis_surat) == $jenis ? 'selected' : '' }}
                                >
                                    {{ $jenis }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- NOMOR SURAT --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Nomor Surat
                        </label>

                        <input
                            type="text"
                            name="nomor_surat"
                            value="{{ old('nomor_surat', $arsipSurat->nomor_surat) }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                    </div>


                    {{-- TANGGAL SURAT --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Tanggal Surat
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="date"
                            name="tanggal_surat"
                            value="{{ old(
                                'tanggal_surat',
                                \Carbon\Carbon::parse($arsipSurat->tanggal_surat)->format('Y-m-d')
                            ) }}"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                    </div>


                    {{-- TAHUN AJARAN --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Tahun Ajaran
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="tahun_ajaran_id"
                            id="tahun_ajaran_id"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                            <option value="">
                                -- Pilih Tahun Ajaran --
                            </option>

                            @foreach ($tahunAjaran as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ old('tahun_ajaran_id', $arsipSurat->tahun_ajaran_id) == $item->id ? 'selected' : '' }}
                                >
                                    {{ $item->nama ?? $item->tahun ?? $item->tahun_ajaran ?? $item->id }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 DATA SISWA
            ====================================================== --}}
            <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="mb-6 border-b border-gray-100 pb-4">

                    <h2 class="text-lg font-bold text-gray-900">
                        Data Siswa
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Pilih siswa berdasarkan Tahun Ajaran, Tingkat,
                        Jurusan, Kelas, dan Siswa.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                    {{-- TINGKAT --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Tingkat
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="tingkat"
                            name="tingkat"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                            <option value="">
                                -- Pilih Tingkat --
                            </option>

                        </select>

                    </div>


                    {{-- JURUSAN --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Jurusan
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="jurusan_id"
                            name="jurusan_id"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                            <option value="">
                                -- Pilih Jurusan --
                            </option>

                        </select>

                    </div>


                    {{-- KELAS --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Kelas
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="kelas_id"
                            name="kelas_id"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                            <option value="">
                                -- Pilih Kelas --
                            </option>

                        </select>

                    </div>


                    {{-- SISWA --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Siswa
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="siswa_id"
                            name="siswa_id"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                            <option value="">
                                -- Pilih Siswa --
                            </option>

                        </select>

                    </div>


                    {{-- GURU BK --}}
                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Guru BK
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="guru_bk_id"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                            <option value="">
                                -- Pilih Guru BK --
                            </option>

                            @foreach ($guruBK as $guru)

                                <option
                                    value="{{ $guru->id }}"
                                    {{ old('guru_bk_id', $arsipSurat->guru_bk_id) == $guru->id ? 'selected' : '' }}
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
            <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="mb-6 border-b border-gray-100 pb-4">

                    <h2 class="text-lg font-bold text-gray-900">
                        Detail Surat
                    </h2>

                </div>


                {{-- PERIHAL --}}
                <div class="mb-5">

                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Perihal
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="perihal"
                        value="{{ old('perihal', $arsipSurat->perihal) }}"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- ISI RINGKAS --}}
                <div class="mb-5">

                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Isi Ringkas
                    </label>

                    <textarea
                        name="isi_ringkas"
                        rows="5"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >{{ old('isi_ringkas', $arsipSurat->isi_ringkas) }}</textarea>

                </div>


                {{-- FILE LAMA --}}
                <div class="mb-5">

                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        File Surat Saat Ini
                    </label>

                    @if ($arsipSurat->file_path)

                        <a
                            href="{{ route('arsip-surat.file', $arsipSurat->id) }}"
                            target="_blank"
                            class="inline-flex rounded-xl bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100"
                        >
                            Lihat File Surat
                        </a>

                    @else

                        <div class="rounded-xl bg-gray-50 px-4 py-3 text-sm text-gray-500">
                            Belum ada file surat.
                        </div>

                    @endif

                </div>


                {{-- FILE BARU --}}
                <div class="mb-5">

                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Ganti File Surat
                    </label>

                    <input
                        type="file"
                        name="file"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="block w-full rounded-xl border border-gray-300 bg-white text-sm text-gray-600"
                    >

                    <p class="mt-2 text-xs text-gray-500">
                        Kosongkan jika tidak ingin mengganti file. Maksimal 5 MB.
                    </p>

                </div>


                {{-- KETERANGAN --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        rows="4"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >{{ old('keterangan', $arsipSurat->keterangan) }}</textarea>

                </div>

            </div>


            {{-- BUTTON --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('arsip-surat.index') }}"
                    class="inline-flex justify-center rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ================================================================
     JAVASCRIPT
     TIDAK MENGGUNAKAN @PUSH
================================================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    console.log('ARSIP SURAT EDIT JS BERJALAN');


    // ==========================================================
    // ELEMENT
    // ==========================================================

    const tahunAjaran = document.getElementById('tahun_ajaran_id');
    const tingkat     = document.getElementById('tingkat');
    const jurusan     = document.getElementById('jurusan_id');
    const kelas       = document.getElementById('kelas_id');
    const siswa       = document.getElementById('siswa_id');


    // ==========================================================
    // DATA LAMA
    // ==========================================================

    const tingkatLama = @json($tingkatLama ?? null);
    const jurusanLama = @json($jurusanLama ?? null);
    const kelasLama   = @json($kelasLama ?? null);
    const siswaLama   = @json($arsipSurat->siswa_id);


    console.log('Tahun:', tahunAjaran.value);
    console.log('Tingkat lama:', tingkatLama);
    console.log('Jurusan lama:', jurusanLama);
    console.log('Kelas lama:', kelasLama);
    console.log('Siswa lama:', siswaLama);


    // ==========================================================
    // RESET
    // ==========================================================

    function resetSelect(select, text)
    {

        select.innerHTML = '';

        const option = document.createElement('option');

        option.value = '';
        option.textContent = text;

        select.appendChild(option);

    }


    // ==========================================================
    // LOAD TINGKAT
    // ==========================================================

    async function loadTingkat(selected = null)
    {

        resetSelect(
            tingkat,
            '-- Memuat Tingkat --'
        );

        try {

            const response = await fetch(
                "{{ route('arsip-surat.tingkat') }}" +
                "?tahun_ajaran_id=" +
                encodeURIComponent(tahunAjaran.value),
                {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );


            if (!response.ok) {

                throw new Error(
                    'HTTP ' + response.status
                );

            }


            const data = await response.json();

            console.log('TINGKAT:', data);


            resetSelect(
                tingkat,
                '-- Pilih Tingkat --'
            );


            data.forEach(function (item) {

                const option =
                    document.createElement('option');

                option.value = item;
                option.textContent = item;

                if (
                    selected !== null &&
                    String(item) === String(selected)
                ) {

                    option.selected = true;

                }

                tingkat.appendChild(option);

            });

        }
        catch (error) {

            console.error(
                'ERROR TINGKAT:',
                error
            );

            resetSelect(
                tingkat,
                '-- Gagal Memuat Tingkat --'
            );

        }

    }


    // ==========================================================
    // LOAD JURUSAN
    // ==========================================================

    async function loadJurusan(selected = null)
    {

        resetSelect(
            jurusan,
            '-- Memuat Jurusan --'
        );

        try {

            const response = await fetch(
                "{{ route('arsip-surat.jurusan') }}" +
                "?tahun_ajaran_id=" +
                encodeURIComponent(tahunAjaran.value) +
                "&tingkat=" +
                encodeURIComponent(tingkat.value),
                {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );


            if (!response.ok) {

                throw new Error(
                    'HTTP ' + response.status
                );

            }


            const data = await response.json();

            console.log('JURUSAN:', data);


            resetSelect(
                jurusan,
                '-- Pilih Jurusan --'
            );


            data.forEach(function (item) {

                const option =
                    document.createElement('option');

                option.value = item.id;

                option.textContent =
                    item.kode
                        ? item.kode + ' - ' + item.nama
                        : item.nama;


                if (
                    selected !== null &&
                    String(item.id) === String(selected)
                ) {

                    option.selected = true;

                }


                jurusan.appendChild(option);

            });

        }
        catch (error) {

            console.error(
                'ERROR JURUSAN:',
                error
            );

            resetSelect(
                jurusan,
                '-- Gagal Memuat Jurusan --'
            );

        }

    }


    // ==========================================================
    // LOAD KELAS
    // ==========================================================

    async function loadKelas(selected = null)
    {

        resetSelect(
            kelas,
            '-- Memuat Kelas --'
        );

        try {

            const response = await fetch(
                "{{ route('arsip-surat.kelas') }}" +
                "?tahun_ajaran_id=" +
                encodeURIComponent(tahunAjaran.value) +
                "&tingkat=" +
                encodeURIComponent(tingkat.value) +
                "&jurusan_id=" +
                encodeURIComponent(jurusan.value),
                {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );


            if (!response.ok) {

                throw new Error(
                    'HTTP ' + response.status
                );

            }


            const data = await response.json();

            console.log('KELAS:', data);


            resetSelect(
                kelas,
                '-- Pilih Kelas --'
            );


            data.forEach(function (item) {

                const option =
                    document.createElement('option');

                option.value = item.id;

                option.textContent = item.nama_kelas;


                if (
                    selected !== null &&
                    String(item.id) === String(selected)
                ) {

                    option.selected = true;

                }


                kelas.appendChild(option);

            });

        }
        catch (error) {

            console.error(
                'ERROR KELAS:',
                error
            );

            resetSelect(
                kelas,
                '-- Gagal Memuat Kelas --'
            );

        }

    }


    // ==========================================================
    // LOAD SISWA
    // ==========================================================

    async function loadSiswa(selected = null)
    {

        resetSelect(
            siswa,
            '-- Memuat Siswa --'
        );

        try {

            const response = await fetch(
                "{{ route('arsip-surat.siswa') }}" +
                "?kelas_id=" +
                encodeURIComponent(kelas.value),
                {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );


            if (!response.ok) {

                throw new Error(
                    'HTTP ' + response.status
                );

            }


            const data = await response.json();

            console.log('SISWA:', data);


            resetSelect(
                siswa,
                '-- Pilih Siswa --'
            );


            data.forEach(function (item) {

                const option =
                    document.createElement('option');

                option.value = item.id;

                option.textContent =
                    item.nis +
                    ' - ' +
                    item.nama_lengkap;


                if (
                    selected !== null &&
                    String(item.id) === String(selected)
                ) {

                    option.selected = true;

                }


                siswa.appendChild(option);

            });

        }
        catch (error) {

            console.error(
                'ERROR SISWA:',
                error
            );

            resetSelect(
                siswa,
                '-- Gagal Memuat Siswa --'
            );

        }

    }


    // ==========================================================
    // TAHUN AJARAN BERUBAH
    // ==========================================================

    tahunAjaran.addEventListener(
        'change',
        async function () {

            await loadTingkat();

            resetSelect(
                jurusan,
                '-- Pilih Tingkat Terlebih Dahulu --'
            );

            resetSelect(
                kelas,
                '-- Pilih Jurusan Terlebih Dahulu --'
            );

            resetSelect(
                siswa,
                '-- Pilih Kelas Terlebih Dahulu --'
            );

        }
    );


    // ==========================================================
    // TINGKAT BERUBAH
    // ==========================================================

    tingkat.addEventListener(
        'change',
        async function () {

            resetSelect(
                kelas,
                '-- Pilih Jurusan Terlebih Dahulu --'
            );

            resetSelect(
                siswa,
                '-- Pilih Kelas Terlebih Dahulu --'
            );

            await loadJurusan();

        }
    );


    // ==========================================================
    // JURUSAN BERUBAH
    // ==========================================================

    jurusan.addEventListener(
        'change',
        async function () {

            resetSelect(
                siswa,
                '-- Pilih Kelas Terlebih Dahulu --'
            );

            await loadKelas();

        }
    );


    // ==========================================================
    // KELAS BERUBAH
    // ==========================================================

    kelas.addEventListener(
        'change',
        async function () {

            await loadSiswa();

        }
    );


    // ==========================================================
    // RESTORE DATA LAMA
    // ==========================================================

    async function restore()
    {

        if (!tahunAjaran.value) {

            return;

        }


        console.log('Mulai restore data...');


        await loadTingkat(
            tingkatLama
        );


        if (!tingkat.value) {

            console.warn(
                'Tingkat lama tidak ditemukan'
            );

            return;

        }


        await loadJurusan(
            jurusanLama
        );


        if (!jurusan.value) {

            console.warn(
                'Jurusan lama tidak ditemukan'
            );

            return;

        }


        await loadKelas(
            kelasLama
        );


        if (!kelas.value) {

            console.warn(
                'Kelas lama tidak ditemukan'
            );

            return;

        }


        await loadSiswa(
            siswaLama
        );


        console.log(
            'Restore selesai'
        );

    }


    // ==========================================================
    // JALANKAN
    // ==========================================================

    restore();

});

</script>

@endsection