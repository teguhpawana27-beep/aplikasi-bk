@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Tambah Dukungan Sistem
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Tambahkan kegiatan dukungan sistem Bimbingan dan Konseling
            </p>
        </div>

        <a
            href="{{ route('dukungan-sistem.index') }}"
            class="rounded-xl border border-slate-300
                   px-5 py-3 text-sm font-semibold
                   text-slate-600 hover:bg-slate-50"
        >
            Kembali
        </a>

    </div>


    {{-- ERROR --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-200
                    bg-red-50 px-5 py-4">

            <p class="font-semibold text-red-700 mb-2">
                Terdapat kesalahan:
            </p>

            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('dukungan-sistem.store') }}"
        method="POST"
        class="space-y-6"
    >

        @csrf


        {{-- ========================================= --}}
        {{-- DATA KEGIATAN --}}
        {{-- ========================================= --}}

        <div class="rounded-2xl border border-slate-200
                    bg-white p-6 shadow-sm">

            <h2 class="text-lg font-semibold text-slate-800 mb-5">
                Data Kegiatan
            </h2>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- GURU BK --}}
                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Guru BK
                        <span class="text-red-500">*</span>
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

                        @foreach($guruBK as $item)

                            <option
                                value="{{ $item->id }}"
                                {{ old('guru_bk_id') == $item->id ? 'selected' : '' }}
                            >
                                {{ $item->nama_lengkap }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- TAHUN AJARAN --}}
                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Tahun Ajaran
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="tahun_ajaran_id"
                        id="tahun_ajaran_id"
                        required
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih Tahun Ajaran
                        </option>

                        @foreach($tahunAjaran as $item)

                            <option
                                value="{{ $item->id }}"
                                {{ old('tahun_ajaran_id') == $item->id ? 'selected' : '' }}
                            >
                                {{ $item->nama }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- JENIS KEGIATAN --}}
                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Jenis Kegiatan
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="jenis_kegiatan"
                        required
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih Jenis Kegiatan
                        </option>

                        @foreach($jenisKegiatan as $jenis)

                            <option
                                value="{{ $jenis }}"
                                {{ old('jenis_kegiatan') == $jenis ? 'selected' : '' }}
                            >
                                {{ $jenis }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- TANGGAL --}}
                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Tanggal
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        value="{{ old('tanggal', date('Y-m-d')) }}"
                        required
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>

            </div>

        </div>



        {{-- ========================================= --}}
        {{-- DATA SISWA --}}
        {{-- ========================================= --}}

        <div class="rounded-2xl border border-slate-200
                    bg-white p-6 shadow-sm">

            <h2 class="text-lg font-semibold text-slate-800 mb-2">
                Data Siswa
            </h2>

            <p class="text-sm text-slate-500 mb-5">
                Jika kegiatan berkaitan dengan siswa tertentu,
                pilih data siswa melalui filter berikut.
            </p>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- TINGKAT --}}
                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Tingkat
                    </label>

                    <select
                        id="tingkat"
                        name="tingkat"
                        disabled
                        class="w-full rounded-xl border-slate-300
                               bg-slate-50
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih Tahun Ajaran terlebih dahulu
                        </option>

                    </select>

                </div>


                {{-- JURUSAN --}}
                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Jurusan
                    </label>

                    <select
                        id="jurusan_id"
                        name="jurusan_id"
                        disabled
                        class="w-full rounded-xl border-slate-300
                               bg-slate-50
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih Tingkat terlebih dahulu
                        </option>

                    </select>

                </div>


                {{-- KELAS --}}
                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Kelas
                    </label>

                    <select
                        id="kelas_id"
                        name="kelas_id"
                        disabled
                        class="w-full rounded-xl border-slate-300
                               bg-slate-50
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih Jurusan terlebih dahulu
                        </option>

                    </select>

                </div>


                {{-- SISWA --}}
                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Siswa
                    </label>

                    <select
                        id="siswa_id"
                        name="siswa_id"
                        disabled
                        class="w-full rounded-xl border-slate-300
                               bg-slate-50
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih Kelas terlebih dahulu
                        </option>

                    </select>

                </div>

            </div>


            <div class="mt-4 rounded-xl bg-blue-50
                        border border-blue-100
                        px-4 py-3">

                <p class="text-sm text-blue-700">

                    <strong>Catatan:</strong>
                    Siswa akan otomatis difilter berdasarkan
                    Tahun Ajaran, Tingkat, Jurusan, dan Kelas.

                    Jika kegiatan bersifat umum,
                    bagian siswa boleh dikosongkan.

                </p>

            </div>

        </div>



        {{-- ========================================= --}}
        {{-- SASARAN --}}
        {{-- ========================================= --}}

        <div class="rounded-2xl border border-slate-200
                    bg-white p-6 shadow-sm">

            <h2 class="text-lg font-semibold text-slate-800 mb-5">
                Sasaran & Uraian Kegiatan
            </h2>


            {{-- SASARAN --}}
            <div class="mb-5">

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Sasaran
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="sasaran"
                    value="{{ old('sasaran') }}"
                    required
                    placeholder="Contoh: Siswa kelas X"
                    class="w-full rounded-xl border-slate-300
                           focus:border-blue-500 focus:ring-blue-500"
                >

            </div>


            {{-- URAIAN --}}
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Uraian Kegiatan
                    <span class="text-red-500">*</span>
                </label>

                <textarea
                    name="uraian_kegiatan"
                    rows="5"
                    required
                    placeholder="Tuliskan uraian kegiatan..."
                    class="w-full rounded-xl border-slate-300
                           focus:border-blue-500 focus:ring-blue-500"
                >{{ old('uraian_kegiatan') }}</textarea>

            </div>

        </div>



        {{-- ========================================= --}}
        {{-- HASIL --}}
        {{-- ========================================= --}}

        <div class="rounded-2xl border border-slate-200
                    bg-white p-6 shadow-sm">

            <h2 class="text-lg font-semibold text-slate-800 mb-5">
                Hasil & Evaluasi
            </h2>


            <div class="space-y-5">

                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Hasil
                    </label>

                    <textarea
                        name="hasil"
                        rows="4"
                        placeholder="Tuliskan hasil kegiatan..."
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('hasil') }}</textarea>

                </div>


                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Evaluasi
                    </label>

                    <textarea
                        name="evaluasi"
                        rows="4"
                        placeholder="Tuliskan hasil evaluasi..."
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('evaluasi') }}</textarea>

                </div>


                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Tindak Lanjut
                    </label>

                    <textarea
                        name="tindak_lanjut"
                        rows="4"
                        placeholder="Tuliskan tindak lanjut..."
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('tindak_lanjut') }}</textarea>

                </div>


                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        rows="3"
                        placeholder="Keterangan tambahan..."
                        class="w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('keterangan') }}</textarea>

                </div>

            </div>

        </div>



        {{-- ========================================= --}}
        {{-- BUTTON --}}
        {{-- ========================================= --}}

        <div class="flex justify-end gap-3">

            <a
                href="{{ route('dukungan-sistem.index') }}"
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
                Simpan Kegiatan
            </button>

        </div>

    </form>

</div>



{{-- ========================================= --}}
{{-- JAVASCRIPT FILTER SISWA --}}
{{-- ========================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const tahunAjaran = document.getElementById('tahun_ajaran_id');
    const tingkat = document.getElementById('tingkat');
    const jurusan = document.getElementById('jurusan_id');
    const kelas = document.getElementById('kelas_id');
    const siswa = document.getElementById('siswa_id');


    const oldTingkat = @json(old('tingkat'));
    const oldJurusan = @json(old('jurusan_id'));
    const oldKelas = @json(old('kelas_id'));
    const oldSiswa = @json(old('siswa_id'));


    function resetSelect(
        select,
        text,
        disabled = true
    ) {

        select.innerHTML =
            `<option value="">${text}</option>`;

        select.disabled = disabled;

        select.classList.toggle(
            'bg-slate-50',
            disabled
        );
    }


    function fillSelect(
        select,
        data,
        placeholder,
        valueField,
        textCallback
    ) {

        select.innerHTML =
            `<option value="">${placeholder}</option>`;

        data.forEach(function (item) {

            const option =
                document.createElement('option');

            option.value =
                item[valueField];

            option.textContent =
                textCallback(item);

            select.appendChild(option);

        });

        select.disabled =
            data.length === 0;

        select.classList.toggle(
            'bg-slate-50',
            data.length === 0
        );
    }


    async function loadTingkat(
        selected = ''
    ) {

        resetSelect(
            tingkat,
            'Memuat tingkat...'
        );

        resetSelect(
            jurusan,
            'Pilih Tingkat terlebih dahulu'
        );

        resetSelect(
            kelas,
            'Pilih Jurusan terlebih dahulu'
        );

        resetSelect(
            siswa,
            'Pilih Kelas terlebih dahulu'
        );


        if (!tahunAjaran.value) {

            resetSelect(
                tingkat,
                'Pilih Tahun Ajaran terlebih dahulu'
            );

            return;
        }


        try {

            const response =
                await fetch(
                    `{{ route('dukungan-sistem.tingkat') }}?tahun_ajaran_id=${encodeURIComponent(tahunAjaran.value)}`
                );

            const data =
                await response.json();


            fillSelect(
                tingkat,
                data.map(item => ({
                    tingkat: item
                })),
                'Pilih Tingkat',
                'tingkat',
                item => item.tingkat
            );


            if (selected) {

                tingkat.value =
                    selected;

                if (tingkat.value) {
                    await loadJurusan(
                        oldJurusan
                    );
                }

            }

        } catch (error) {

            console.error(error);

            resetSelect(
                tingkat,
                'Gagal memuat tingkat'
            );

        }

    }


    async function loadJurusan(
        selected = ''
    ) {

        resetSelect(
            jurusan,
            'Memuat jurusan...'
        );

        resetSelect(
            kelas,
            'Pilih Jurusan terlebih dahulu'
        );

        resetSelect(
            siswa,
            'Pilih Kelas terlebih dahulu'
        );


        if (
            !tahunAjaran.value ||
            !tingkat.value
        ) {

            resetSelect(
                jurusan,
                'Pilih Tingkat terlebih dahulu'
            );

            return;
        }


        try {

            const response =
                await fetch(
                    `{{ route('dukungan-sistem.jurusan') }}?tahun_ajaran_id=${encodeURIComponent(tahunAjaran.value)}&tingkat=${encodeURIComponent(tingkat.value)}`
                );

            const data =
                await response.json();


            fillSelect(
                jurusan,
                data,
                'Pilih Jurusan',
                'id',
                item => `${item.kode} - ${item.nama}`
            );


            if (selected) {

                jurusan.value =
                    selected;

                if (jurusan.value) {
                    await loadKelas(
                        oldKelas
                    );
                }

            }

        } catch (error) {

            console.error(error);

            resetSelect(
                jurusan,
                'Gagal memuat jurusan'
            );

        }

    }


    async function loadKelas(
        selected = ''
    ) {

        resetSelect(
            kelas,
            'Memuat kelas...'
        );

        resetSelect(
            siswa,
            'Pilih Kelas terlebih dahulu'
        );


        if (
            !tahunAjaran.value ||
            !tingkat.value ||
            !jurusan.value
        ) {

            resetSelect(
                kelas,
                'Pilih Jurusan terlebih dahulu'
            );

            return;
        }


        try {

            const response =
                await fetch(
                    `{{ route('dukungan-sistem.kelas') }}?tahun_ajaran_id=${encodeURIComponent(tahunAjaran.value)}&tingkat=${encodeURIComponent(tingkat.value)}&jurusan_id=${encodeURIComponent(jurusan.value)}`
                );

            const data =
                await response.json();


            fillSelect(
                kelas,
                data,
                'Pilih Kelas',
                'id',
                item => item.nama_kelas
            );


            if (selected) {

                kelas.value =
                    selected;

                if (kelas.value) {
                    await loadSiswa(
                        oldSiswa
                    );
                }

            }

        } catch (error) {

            console.error(error);

            resetSelect(
                kelas,
                'Gagal memuat kelas'
            );

        }

    }


    async function loadSiswa(
        selected = ''
    ) {

        resetSelect(
            siswa,
            'Memuat siswa...'
        );


        if (!kelas.value) {

            resetSelect(
                siswa,
                'Pilih Kelas terlebih dahulu'
            );

            return;
        }


        try {

            const response =
                await fetch(
                    `{{ route('dukungan-sistem.siswa') }}?kelas_id=${encodeURIComponent(kelas.value)}`
                );

            const data =
                await response.json();


            fillSelect(
                siswa,
                data,
                'Tidak terkait siswa tertentu',
                'id',
                item =>
                    `${item.nama_lengkap} - ${item.nis}`
            );


            if (selected) {

                siswa.value =
                    selected;

            }

        } catch (error) {

            console.error(error);

            resetSelect(
                siswa,
                'Gagal memuat siswa'
            );

        }

    }


    /*
     * Tahun Ajaran berubah
     */
    tahunAjaran.addEventListener(
        'change',
        function () {

            loadTingkat();

        }
    );


    /*
     * Tingkat berubah
     */
    tingkat.addEventListener(
        'change',
        function () {

            loadJurusan();

        }
    );


    /*
     * Jurusan berubah
     */
    jurusan.addEventListener(
        'change',
        function () {

            loadKelas();

        }
    );


    /*
     * Kelas berubah
     */
    kelas.addEventListener(
        'change',
        function () {

            loadSiswa();

        }
    );


    /*
     * Restore old input
     * ketika validasi gagal
     */
    if (tahunAjaran.value) {

        loadTingkat(
            oldTingkat
        );

    }

});

</script>

@endsection