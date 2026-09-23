@extends('layouts.app')

@section('content')

<div
    x-data="layananDasarForm()"
    x-init="init()"
    class="mx-auto max-w-5xl space-y-6"
>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}
    <div>
        <a
            href="{{ route('layanan-dasar.index') }}"
            class="text-sm font-medium text-slate-500 hover:text-slate-800"
        >
            ← Kembali ke Layanan Dasar
        </a>

        <h2 class="mt-3 text-2xl font-bold text-gray-800">
            Tambah Layanan Dasar
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Catat kegiatan layanan dasar Bimbingan dan Konseling.
        </p>
    </div>


    {{-- ========================================================= --}}
    {{-- ERROR --}}
    {{-- ========================================================= --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">

            <div class="font-semibold text-red-700">
                Periksa kembali data yang dimasukkan.
            </div>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-600">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('layanan-dasar.store') }}"
        method="POST"
        class="space-y-6"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- INFORMASI LAYANAN --}}
        {{-- ===================================================== --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">

            <h3 class="text-lg font-semibold text-gray-800">
                Informasi Layanan
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Tentukan jenis dan waktu pelaksanaan layanan.
            </p>


            <div class="mt-6 grid gap-5 md:grid-cols-2">


                {{-- JENIS LAYANAN --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Jenis Layanan <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="jenis_layanan"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >

                        <option value="">
                            Pilih jenis layanan
                        </option>

                        <option
                            value="Bimbingan Klasikal"
                            @selected(old('jenis_layanan') === 'Bimbingan Klasikal')
                        >
                            Bimbingan Klasikal
                        </option>

                        <option
                            value="Bimbingan Kelompok"
                            @selected(old('jenis_layanan') === 'Bimbingan Kelompok')
                        >
                            Bimbingan Kelompok
                        </option>

                        <option
                            value="Bimbingan Kelas Besar / Lintas Kelas"
                            @selected(old('jenis_layanan') === 'Bimbingan Kelas Besar / Lintas Kelas')
                        >
                            Bimbingan Kelas Besar / Lintas Kelas
                        </option>

                        <option
                            value="Pengembangan Media BK"
                            @selected(old('jenis_layanan') === 'Pengembangan Media BK')
                        >
                            Pengembangan Media BK
                        </option>

                    </select>

                </div>


                {{-- TANGGAL --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Tanggal <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        value="{{ old('tanggal', date('Y-m-d')) }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >

                </div>


                {{-- GURU BK --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Guru BK <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="guru_bk_id"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >

                        <option value="">
                            Pilih Guru BK
                        </option>

                        @foreach ($guruBK as $guru)

                            <option
                                value="{{ $guru->id }}"
                                @selected(old('guru_bk_id') == $guru->id)
                            >
                                {{ $guru->nama_lengkap }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- TAHUN AJARAN --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Tahun Ajaran <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="tahun_ajaran_id"
                        x-model="tahunAjaranId"
                        @change="ubahTahunAjaran()"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >

                        <option value="">
                            Pilih Tahun Ajaran
                        </option>

                        @foreach ($tahunAjaran as $tahun)

                            <option
                                value="{{ $tahun->id }}"
                                @selected(old('tahun_ajaran_id', $tahun->is_active ? $tahun->id : '') == $tahun->id)
                            >
                                {{ $tahun->nama }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- TINGKAT --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Tingkat
                    </label>

                    <select
                        x-model="tingkat"
                        @change="ubahTingkat()"
                        :disabled="!tahunAjaranId"
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600 disabled:bg-gray-100 disabled:text-gray-400"
                    >

                        <option value="">
                            Pilih Tingkat
                        </option>

                        <template
                            x-for="item in daftarTingkat"
                            :key="item"
                        >

                            <option
                                :value="item"
                                x-text="item"
                            ></option>

                        </template>

                    </select>

                </div>


                {{-- JURUSAN --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Jurusan
                    </label>

                    <select
                        x-model="jurusanId"
                        @change="ubahJurusan()"
                        :disabled="!tingkat"
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600 disabled:bg-gray-100 disabled:text-gray-400"
                    >

                        <option value="">
                            Pilih Jurusan
                        </option>

                        <template
                            x-for="item in daftarJurusan"
                            :key="item.id"
                        >

                            <option
                                :value="String(item.id)"
                                x-text="item.kode + ' — ' + item.nama"
                            ></option>

                        </template>

                    </select>

                </div>


                {{-- KELAS --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Kelas
                    </label>

                    <select
                        name="kelas_id"
                        x-model="kelasId"
                        @change="ubahKelas()"
                        :disabled="!jurusanId"
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600 disabled:bg-gray-100 disabled:text-gray-400"
                    >

                        <option value="">
                            Pilih Kelas
                        </option>

                        <template
                            x-for="item in daftarKelas"
                            :key="item.id"
                        >

                            <option
                                :value="String(item.id)"
                                x-text="item.nama_kelas"
                            ></option>

                        </template>

                    </select>

                </div>


                {{-- SKKPD --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        SKKPD <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="skkpd_id"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >

                        <option value="">
                            Pilih SKKPD
                        </option>

                        @foreach ($skkpd as $item)

                            <option
                                value="{{ $item->id }}"
                                @selected(old('skkpd_id') == $item->id)
                            >
                                {{ $item->kode }} — {{ $item->nama }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- METODE --}}
                <div class="md:col-span-2">

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Metode BK <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="metode_id"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >

                        <option value="">
                            Pilih metode
                        </option>

                        @foreach ($metode as $item)

                            <option
                                value="{{ $item->id }}"
                                @selected(old('metode_id') == $item->id)
                            >
                                {{ $item->nama }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- MATERI DAN PELAKSANAAN --}}
        {{-- ===================================================== --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">

            <h3 class="text-lg font-semibold text-gray-800">
                Materi dan Pelaksanaan
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Isi materi dan pelaksanaan kegiatan layanan.
            </p>


            <div class="mt-6 space-y-5">


                {{-- TOPIK --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Topik <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="topik"
                        value="{{ old('topik') }}"
                        required
                        placeholder="Contoh: Perencanaan Karier Siswa"
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >

                </div>


                {{-- SASARAN --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Sasaran
                    </label>

                    <input
                        type="text"
                        name="sasaran"
                        value="{{ old('sasaran') }}"
                        placeholder="Contoh: Siswa kelas X DKV 1"
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >

                </div>


                {{-- URAIAN --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Uraian Kegiatan
                    </label>

                    <textarea
                        name="uraian_kegiatan"
                        rows="4"
                        placeholder="Jelaskan pelaksanaan kegiatan..."
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >{{ old('uraian_kegiatan') }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PESERTA LAYANAN --}}
        {{-- ===================================================== --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <h3 class="text-lg font-semibold text-gray-800">
                        Peserta Layanan
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Pilih siswa yang mengikuti layanan.
                    </p>

                </div>


                <div
                    class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600"
                >
                    <span x-text="siswaTersedia.length"></span>
                    siswa tersedia
                </div>

            </div>


            {{-- PESAN JIKA KELAS BELUM DIPILIH --}}
            <div
                x-show="!kelasId"
                class="mt-5 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center"
            >

                <div class="text-sm font-medium text-slate-600">
                    Pilih kelas terlebih dahulu
                </div>

                <div class="mt-1 text-xs text-slate-400">
                    Daftar siswa akan muncul setelah kelas dipilih.
                </div>

            </div>


            {{-- AREA SISWA --}}
            <div
                x-show="kelasId"
                x-cloak
                class="mt-5"
            >

                {{-- SEARCH --}}
                <div class="relative">

                    <input
                        type="text"
                        x-model="searchSiswa"
                        placeholder="Cari nama, NIS, atau NISN siswa..."
                        class="w-full rounded-lg border-gray-300 py-3 pl-4 pr-4 focus:border-slate-600 focus:ring-slate-600"
                    >

                </div>


                {{-- PILIH SEMUA --}}
                <div
                    class="mt-4 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-4"
                >

                    <label class="flex cursor-pointer items-center gap-3">

                        <input
                            type="checkbox"
                            :checked="semuaSiswaDipilih"
                            @change="toggleSemuaSiswa()"
                            class="h-5 w-5 rounded border-gray-300 text-slate-700 focus:ring-slate-500"
                        >

                        <span class="text-sm font-semibold text-slate-700">
                            Pilih semua siswa di kelas
                        </span>

                    </label>


                    <span
                        class="text-sm text-slate-500"
                        x-text="selectedStudents.length + ' dipilih'"
                    ></span>

                </div>


                {{-- LIST SISWA --}}
                <div class="mt-3 space-y-2">

                    <template x-if="siswaTersaring.length === 0">

                        <div
                            class="rounded-xl border border-dashed border-slate-300 px-5 py-8 text-center"
                        >

                            <div class="text-sm font-medium text-slate-600">
                                Siswa tidak ditemukan
                            </div>

                            <div class="mt-1 text-xs text-slate-400">
                                Coba gunakan nama, NIS, atau NISN.
                            </div>

                        </div>

                    </template>


                    <template
                        x-for="siswa in siswaTersaring"
                        :key="siswa.siswa_id"
                    >

                        <label
                            class="flex cursor-pointer items-center gap-4 rounded-xl border border-slate-200 bg-white px-4 py-4 transition hover:border-slate-300 hover:bg-slate-50"
                        >

                            <input
                                type="checkbox"
                                name="siswa_id[]"
                                :value="siswa.siswa_id"
                                x-model="selectedStudents"
                                @change="sinkronkanPilihSemua()"
                                class="h-5 w-5 rounded border-gray-300 text-slate-700 focus:ring-slate-500"
                            >


                            <div class="min-w-0 flex-1">

                                <div
                                    class="font-medium text-slate-800"
                                    x-text="siswa.nama_lengkap"
                                ></div>

                                <div class="mt-1 text-sm text-slate-500">

                                    NIS:
                                    <span x-text="siswa.nis || '-'"></span>

                                    <span class="mx-1">•</span>

                                    NISN:
                                    <span x-text="siswa.nisn || '-'"></span>

                                </div>

                            </div>

                        </label>

                    </template>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- HASIL DAN EVALUASI --}}
        {{-- ===================================================== --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">

            <h3 class="text-lg font-semibold text-gray-800">
                Hasil dan Evaluasi
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Catat hasil dan evaluasi kegiatan.
            </p>


            <div class="mt-6 space-y-5">


                {{-- HASIL --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Hasil
                    </label>

                    <textarea
                        name="hasil"
                        rows="4"
                        placeholder="Tuliskan hasil kegiatan..."
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >{{ old('hasil') }}</textarea>

                </div>


                {{-- EVALUASI --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Evaluasi
                    </label>

                    <textarea
                        name="evaluasi"
                        rows="4"
                        placeholder="Tuliskan evaluasi kegiatan..."
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >{{ old('evaluasi') }}</textarea>

                </div>


                {{-- KETERANGAN --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        rows="3"
                        placeholder="Keterangan tambahan..."
                        class="w-full rounded-lg border-gray-300 focus:border-slate-600 focus:ring-slate-600"
                    >{{ old('keterangan') }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- BUTTON --}}
        {{-- ===================================================== --}}
        <div class="flex justify-end gap-3">

            <a
                href="{{ route('layanan-dasar.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
            >
                Batal
            </a>

            <button
                type="submit"
                class="rounded-lg bg-slate-800 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-700"
            >
                Simpan Layanan
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- ALPINE JS --}}
{{-- ============================================================= --}}
<script>

function layananDasarForm() {

    return {

        tahunAjaranId: @json(old('tahun_ajaran_id', '')),

        tingkat: '',

        jurusanId: '',

        kelasId: @json(old('kelas_id', '')),

        searchSiswa: '',

        selectedStudents: @json(
            array_map(
                'strval',
                old('siswa_id', [])
            )
        ),

        kelasData: @json($kelasList),

        siswaData: @json($siswaKelas),


        init() {

            this.tahunAjaranId = String(this.tahunAjaranId || '');

            this.kelasId = String(this.kelasId || '');

            this.selectedStudents =
                (this.selectedStudents || []).map(String);


            // Jika sebelumnya ada kelas dari validation error
            if (this.kelasId) {

                const kelas = this.kelasData.find(
                    item => String(item.id) === this.kelasId
                );

                if (kelas) {

                    this.tingkat = String(kelas.tingkat);

                    this.jurusanId = String(kelas.jurusan_id);

                }

            }

        },


        get daftarTingkat() {

            if (!this.tahunAjaranId) {
                return [];
            }

            return [
                ...new Set(

                    this.kelasData

                        .filter(
                            item =>
                                String(item.tahun_ajaran_id) ===
                                String(this.tahunAjaranId)
                        )

                        .map(item => String(item.tingkat))

                )
            ];

        },


        get daftarJurusan() {

            if (!this.tahunAjaranId || !this.tingkat) {
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

                    if (
                        !hasil.some(
                            jurusan =>
                                String(jurusan.id) ===
                                String(item.jurusan_id)
                        )
                    ) {

                        hasil.push({

                            id: item.jurusan_id,

                            kode: item.jurusan_kode,

                            nama: item.jurusan_nama

                        });

                    }

                });

            return hasil;

        },


        get daftarKelas() {

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


        get siswaTersedia() {

            if (!this.kelasId) {
                return [];
            }

            return this.siswaData.filter(item =>

                String(item.kelas_id) ===
                String(this.kelasId)

                &&

                String(item.tahun_ajaran_id) ===
                String(this.tahunAjaranId)

            );

        },


        get siswaTersaring() {

            const keyword =
                this.searchSiswa
                    .toLowerCase()
                    .trim();

            if (!keyword) {
                return this.siswaTersedia;
            }

            return this.siswaTersedia.filter(siswa => {

                const nama =
                    String(siswa.nama_lengkap || '')
                        .toLowerCase();

                const nis =
                    String(siswa.nis || '')
                        .toLowerCase();

                const nisn =
                    String(siswa.nisn || '')
                        .toLowerCase();

                return (
                    nama.includes(keyword) ||
                    nis.includes(keyword) ||
                    nisn.includes(keyword)
                );

            });

        },


        get semuaSiswaDipilih() {

            if (this.siswaTersedia.length === 0) {
                return false;
            }

            return this.siswaTersedia.every(
                siswa =>
                    this.selectedStudents.includes(
                        String(siswa.siswa_id)
                    )
            );

        },


        ubahTahunAjaran() {

            this.tahunAjaranId =
                String(this.tahunAjaranId || '');

            this.tingkat = '';

            this.jurusanId = '';

            this.kelasId = '';

            this.searchSiswa = '';

            this.selectedStudents = [];

        },


        ubahTingkat() {

            this.jurusanId = '';

            this.kelasId = '';

            this.searchSiswa = '';

            this.selectedStudents = [];

        },


        ubahJurusan() {

            this.kelasId = '';

            this.searchSiswa = '';

            this.selectedStudents = [];

        },


        ubahKelas() {

            this.kelasId =
                String(this.kelasId || '');

            this.searchSiswa = '';

            this.selectedStudents = [];

        },


        toggleSemuaSiswa() {

            if (this.semuaSiswaDipilih) {

                const idsKelas =
                    this.siswaTersedia.map(
                        siswa =>
                            String(siswa.siswa_id)
                    );

                this.selectedStudents =
                    this.selectedStudents.filter(
                        id => !idsKelas.includes(String(id))
                    );

            } else {

                const idsKelas =
                    this.siswaTersedia.map(
                        siswa =>
                            String(siswa.siswa_id)
                    );

                this.selectedStudents = [
                    ...new Set([
                        ...this.selectedStudents.map(String),
                        ...idsKelas
                    ])
                ];

            }

        },


        sinkronkanPilihSemua() {

            this.selectedStudents =
                this.selectedStudents.map(String);

        }

    };

}

</script>

@endsection