@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Data Hasil Asesmen Awal
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Masukkan hasil asesmen awal siswa sebagai dasar layanan BK.
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
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- FORM --}}
    <form action="{{ route('asesmen-awal.store') }}"
          method="POST"
          class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">

        @csrf


        {{-- ========================================================= --}}
        {{-- TAHUN AJARAN --}}
        {{-- ========================================================= --}}

        <div>
            <label for="tahun_ajaran_id"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Tahun Ajaran
                <span class="text-red-500">*</span>

            </label>

            <select
                name="tahun_ajaran_id"
                id="tahun_ajaran_id"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                <option value="">
                    -- Pilih Tahun Ajaran --
                </option>

                @foreach ($tahunAjaran as $item)

                    <option
                        value="{{ $item->id }}"
                        {{ old('tahun_ajaran_id') == $item->id ? 'selected' : '' }}>

                        {{ $item->nama }}

                    </option>

                @endforeach

            </select>

            @error('tahun_ajaran_id')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- TINGKAT --}}
        {{-- ========================================================= --}}

        <div>

            <label for="tingkat"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Tingkat
                <span class="text-red-500">*</span>

            </label>

            <select
                id="tingkat"
                disabled
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400">

                <option value="">
                    -- Pilih Tahun Ajaran Terlebih Dahulu --
                </option>

            </select>

        </div>


        {{-- ========================================================= --}}
        {{-- JURUSAN --}}
        {{-- ========================================================= --}}

        <div>

            <label for="jurusan_id"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Jurusan
                <span class="text-red-500">*</span>

            </label>

            <select
                id="jurusan_id"
                disabled
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400">

                <option value="">
                    -- Pilih Tingkat Terlebih Dahulu --
                </option>

            </select>

        </div>


        {{-- ========================================================= --}}
        {{-- KELAS --}}
        {{-- ========================================================= --}}

        <div>

            <label for="kelas_id"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Kelas
                <span class="text-red-500">*</span>

            </label>

            <select
                id="kelas_id"
                disabled
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400">

                <option value="">
                    -- Pilih Jurusan Terlebih Dahulu --
                </option>

            </select>

        </div>


        {{-- ========================================================= --}}
        {{-- SISWA --}}
        {{-- ========================================================= --}}

        <div>

            <label for="siswa_id"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Siswa
                <span class="text-red-500">*</span>

            </label>

            <select
                name="siswa_id"
                id="siswa_id"
                required
                disabled
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400">

                <option value="">
                    -- Pilih Kelas Terlebih Dahulu --
                </option>

            </select>

            @error('siswa_id')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- GURU BK --}}
        {{-- ========================================================= --}}

        <div>

            <label for="guru_bk_id"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Guru BK
                <span class="text-red-500">*</span>

            </label>

            <select
                name="guru_bk_id"
                id="guru_bk_id"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                <option value="">
                    -- Pilih Guru BK --
                </option>

                @foreach ($guruBK as $item)

                    <option
                        value="{{ $item->id }}"
                        {{ old('guru_bk_id') == $item->id ? 'selected' : '' }}>

                        {{ $item->nama_lengkap }}

                    </option>

                @endforeach

            </select>

            @error('guru_bk_id')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- TANGGAL ASESMEN --}}
        {{-- ========================================================= --}}

        <div>

            <label for="tanggal_asesmen"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Tanggal Asesmen
                <span class="text-red-500">*</span>

            </label>

            <input
                type="date"
                name="tanggal_asesmen"
                id="tanggal_asesmen"
                value="{{ old('tanggal_asesmen', date('Y-m-d')) }}"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

            @error('tanggal_asesmen')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- INSTRUMEN --}}
        {{-- ========================================================= --}}

        <div>

            <label for="instrumen"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Instrumen Asesmen
                <span class="text-red-500">*</span>

            </label>

            <input
                type="text"
                name="instrumen"
                id="instrumen"
                value="{{ old('instrumen') }}"
                required
                placeholder="Contoh: AKPD / Angket Kebutuhan Siswa"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

            @error('instrumen')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- HASIL --}}
        {{-- ========================================================= --}}

        <div>

            <label for="hasil"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Hasil Asesmen

            </label>

            <textarea
                name="hasil"
                id="hasil"
                rows="5"
                placeholder="Masukkan hasil asesmen siswa..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('hasil') }}</textarea>

        </div>


        {{-- ========================================================= --}}
        {{-- REKOMENDASI --}}
        {{-- ========================================================= --}}

        <div>

            <label for="rekomendasi"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Rekomendasi

            </label>

            <textarea
                name="rekomendasi"
                id="rekomendasi"
                rows="4"
                placeholder="Masukkan rekomendasi berdasarkan hasil asesmen..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('rekomendasi') }}</textarea>

        </div>


        {{-- ========================================================= --}}
        {{-- TINDAK LANJUT --}}
        {{-- ========================================================= --}}

        <div>

            <label for="tindak_lanjut"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Tindak Lanjut

            </label>

            <textarea
                name="tindak_lanjut"
                id="tindak_lanjut"
                rows="4"
                placeholder="Masukkan rencana tindak lanjut..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('tindak_lanjut') }}</textarea>

        </div>


        {{-- ========================================================= --}}
        {{-- KETERANGAN --}}
        {{-- ========================================================= --}}

        <div>

            <label for="keterangan"
                   class="block text-sm font-semibold text-gray-700 mb-2">

                Keterangan

            </label>

            <textarea
                name="keterangan"
                id="keterangan"
                rows="3"
                placeholder="Tambahkan keterangan jika diperlukan..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('keterangan') }}</textarea>

        </div>


        {{-- ========================================================= --}}
        {{-- BUTTON --}}
        {{-- ========================================================= --}}

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">

            <a
                href="{{ route('asesmen-awal.index') }}"
                class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-semibold">

                Batal

            </a>

            <button
                type="submit"
                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold">

                Simpan Data

            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT CASCADING --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const tahunAjaran = document.getElementById('tahun_ajaran_id');
    const tingkat = document.getElementById('tingkat');
    const jurusan = document.getElementById('jurusan_id');
    const kelas = document.getElementById('kelas_id');
    const siswa = document.getElementById('siswa_id');

    /*
    |--------------------------------------------------------------------------
    | DATA KELAS DARI CONTROLLER
    |--------------------------------------------------------------------------
    */

    const kelasData = @json($kelasList);

    const siswaKelasData = @json($siswaKelas);

    const semuaSiswaData = @json($semuaSiswa);


    /*
    |--------------------------------------------------------------------------
    | RESET SELECT
    |--------------------------------------------------------------------------
    */

    function resetSelect(select, text) {

        select.innerHTML = '';

        const option = document.createElement('option');

        option.value = '';
        option.textContent = text;

        select.appendChild(option);

        select.disabled = true;
    }


    /*
    |--------------------------------------------------------------------------
    | TAHUN AJARAN
    |--------------------------------------------------------------------------
    */

    tahunAjaran.addEventListener('change', function () {

        resetSelect(
            tingkat,
            '-- Pilih Tingkat --'
        );

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


        const tahunId = this.value;

        if (!tahunId) {
            return;
        }


        /*
        | Ambil tingkat unik berdasarkan tahun ajaran
        */

        const tingkatList = [];

        kelasData.forEach(function (item) {

            if (
                String(item.tahun_ajaran_id) === String(tahunId)
                &&
                !tingkatList.includes(String(item.tingkat))
            ) {

                tingkatList.push(String(item.tingkat));

            }

        });


        tingkatList.sort(function (a, b) {

            return Number(a) - Number(b);

        });


        tingkatList.forEach(function (value) {

            const option = document.createElement('option');

            option.value = value;

            option.textContent = value;

            tingkat.appendChild(option);

        });


        if (tingkatList.length > 0) {

            tingkat.disabled = false;

        }

    });


    /*
    |--------------------------------------------------------------------------
    | TINGKAT
    |--------------------------------------------------------------------------
    */

    tingkat.addEventListener('change', function () {

        resetSelect(
            jurusan,
            '-- Pilih Jurusan --'
        );

        resetSelect(
            kelas,
            '-- Pilih Jurusan Terlebih Dahulu --'
        );

        resetSelect(
            siswa,
            '-- Pilih Kelas Terlebih Dahulu --'
        );


        const tahunId = tahunAjaran.value;

        const tingkatValue = this.value;

        if (!tahunId || !tingkatValue) {
            return;
        }


        const jurusanList = [];


        kelasData.forEach(function (item) {

            if (
                String(item.tahun_ajaran_id) === String(tahunId)
                &&
                String(item.tingkat) === String(tingkatValue)
            ) {

                const exists = jurusanList.some(function (jurusanItem) {

                    return String(jurusanItem.id) === String(item.jurusan_id);

                });


                if (!exists) {

                    jurusanList.push({

                        id: item.jurusan_id,

                        kode: item.jurusan_kode,

                        nama: item.jurusan_nama

                    });

                }

            }

        });


        jurusanList.sort(function (a, b) {

            return a.kode.localeCompare(b.kode);

        });


        jurusanList.forEach(function (item) {

            const option = document.createElement('option');

            option.value = item.id;

            option.textContent =
                item.kode + ' - ' + item.nama;

            jurusan.appendChild(option);

        });


        if (jurusanList.length > 0) {

            jurusan.disabled = false;

        }

    });


    /*
    |--------------------------------------------------------------------------
    | JURUSAN
    |--------------------------------------------------------------------------
    */

    jurusan.addEventListener('change', function () {

        resetSelect(
            kelas,
            '-- Pilih Kelas --'
        );

        resetSelect(
            siswa,
            '-- Pilih Kelas Terlebih Dahulu --'
        );


        const tahunId = tahunAjaran.value;

        const tingkatValue = tingkat.value;

        const jurusanId = this.value;


        if (
            !tahunId
            ||
            !tingkatValue
            ||
            !jurusanId
        ) {

            return;

        }


        const kelasList = kelasData.filter(function (item) {

            return (
                String(item.tahun_ajaran_id) === String(tahunId)
                &&
                String(item.tingkat) === String(tingkatValue)
                &&
                String(item.jurusan_id) === String(jurusanId)
            );

        });


        kelasList.forEach(function (item) {

            const option = document.createElement('option');

            option.value = item.id;

            option.textContent = item.nama_kelas;

            kelas.appendChild(option);

        });


        if (kelasList.length > 0) {

            kelas.disabled = false;

        }

    });


    /*
    |--------------------------------------------------------------------------
    | KELAS
    |--------------------------------------------------------------------------
    */

    kelas.addEventListener('change', function () {

        resetSelect(
            siswa,
            '-- Pilih Siswa --'
        );


        const kelasId = this.value;


        if (!kelasId) {

            return;

        }


        /*
        | Cari siswa yang mempunyai riwayat pada kelas tersebut
        */

        const siswaList = siswaKelasData.filter(function (item) {

            return String(item.kelas_id) === String(kelasId);

        });


        /*
        | Jika belum ada riwayat kelas,
        | cari dari semua siswa sebagai fallback.
        */

        if (siswaList.length > 0) {

            siswaList.forEach(function (item) {

                const option = document.createElement('option');

                option.value = item.siswa_id;

                option.textContent =
                    item.nis
                    + ' - '
                    + item.nama_lengkap;

                siswa.appendChild(option);

            });

        } else {

            semuaSiswaData.forEach(function (item) {

                const option = document.createElement('option');

                option.value = item.id;

                option.textContent =
                    item.nis
                    + ' - '
                    + item.nama_lengkap;

                siswa.appendChild(option);

            });

        }


        if (siswa.options.length > 1) {

            siswa.disabled = false;

        }

    });

});

</script>

@endsection