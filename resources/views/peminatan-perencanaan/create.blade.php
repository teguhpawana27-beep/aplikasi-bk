@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Peminatan & Perencanaan Individu
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Tambahkan data kegiatan peminatan dan perencanaan individu.
        </p>
    </div>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="font-semibold text-red-700 mb-2">
                Terjadi kesalahan:
            </div>

            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <form
        action="{{ route('peminatan-perencanaan.store') }}"
        method="POST"
        x-data="peminatanForm()"
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
    >

        @csrf

        {{-- HEADER FORM --}}
        <div class="px-8 py-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">
                Form Tambah Data
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Lengkapi data sesuai pelaksanaan layanan BK.
            </p>
        </div>

        <div class="p-8 space-y-6">

            {{-- ===================================================== --}}
            {{-- GURU BK --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="guru_bk_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Guru BK <span class="text-red-500">*</span>
                </label>

                <select
                    id="guru_bk_id"
                    name="guru_bk_id"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">-- Pilih Guru BK --</option>

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


            {{-- ===================================================== --}}
            {{-- TAHUN AJARAN --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="tahun_ajaran_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>

                <select
                    id="tahun_ajaran_id"
                    name="tahun_ajaran_id"
                    required
                    x-model="tahunAjaran"
                    @change="ubahTahunAjaran()"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">-- Pilih Tahun Ajaran --</option>

                    @foreach ($tahunAjaran as $tahun)
                        <option
                            value="{{ $tahun->id }}"
                            {{ old('tahun_ajaran_id') == $tahun->id ? 'selected' : '' }}
                        >
                            {{ $tahun->nama }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- ===================================================== --}}
            {{-- TINGKAT --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="tingkat"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Tingkat <span class="text-red-500">*</span>
                </label>

                <select
                    id="tingkat"
                    x-model="tingkat"
                    @change="ubahTingkat()"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">-- Pilih Tingkat --</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>


            {{-- ===================================================== --}}
            {{-- JURUSAN --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="jurusan_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Jurusan <span class="text-red-500">*</span>
                </label>

                <select
                    id="jurusan_id"
                    x-model="jurusan"
                    @change="ubahJurusan()"
                    :disabled="!tingkat"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:bg-gray-100 disabled:text-gray-400"
                >
                    <option value="">
                        <span x-text="tingkat ? '-- Pilih Jurusan --' : 'Pilih Tingkat terlebih dahulu'"></span>
                    </option>

                    <template x-for="item in daftarJurusan" :key="item.id">
                        <option
                            :value="item.id"
                            x-text="item.label"
                        ></option>
                    </template>
                </select>
            </div>


            {{-- ===================================================== --}}
            {{-- KELAS --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="kelas_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Kelas
                </label>

                <select
                    id="kelas_id"
                    name="kelas_id"
                    x-model="kelas"
                    @change="ubahKelas()"
                    :disabled="!jurusan"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:bg-gray-100 disabled:text-gray-400"
                >
                    <option value="">
                        Pilih Jurusan terlebih dahulu
                    </option>

                    <template x-for="item in daftarKelas" :key="item.id">
                        <option
                            :value="item.id"
                            x-text="item.nama_kelas"
                        ></option>
                    </template>
                </select>
            </div>


            {{-- ===================================================== --}}
            {{-- SISWA --}}
            {{-- ===================================================== --}}

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Siswa
                </label>

                <div
                    x-show="kelas"
                    x-cloak
                    class="space-y-3"
                >

                    {{-- SEARCH --}}
                    <input
                        type="text"
                        x-model="pencarian"
                        placeholder="Cari nama siswa atau NIS..."
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                    {{-- LIST SISWA --}}
                    <div
                        class="border border-gray-200 rounded-lg
                               max-h-64 overflow-y-auto"
                    >

                        <template x-if="siswaTampil.length === 0">
                            <div class="p-5 text-center text-sm text-gray-500">
                                Tidak ada siswa pada kelas ini.
                            </div>
                        </template>

                        <template
                            x-for="siswa in siswaTampil"
                            :key="siswa.id"
                        >
                            <label
                                class="flex items-center gap-3
                                       px-4 py-3
                                       border-b border-gray-100
                                       last:border-b-0
                                       hover:bg-gray-50
                                       cursor-pointer"
                            >

                                <input
                                    type="checkbox"
                                    name="siswa_id[]"
                                    :value="siswa.id"
                                    :checked="siswaTerpilih.includes(String(siswa.id))"
                                    @change="toggleSiswa(siswa.id)"
                                    class="rounded border-gray-300
                                           text-blue-600
                                           focus:ring-blue-500"
                                >

                                <div>
                                    <div
                                        class="text-sm font-semibold text-gray-800"
                                        x-text="siswa.nama"
                                    ></div>

                                    <div
                                        class="text-xs text-gray-500"
                                        x-text="'NIS: ' + (siswa.nis || '-')"
                                    ></div>
                                </div>

                            </label>
                        </template>

                    </div>

                    {{-- JUMLAH --}}
                    <div class="text-xs text-gray-500">
                        <span x-text="siswaTerpilih.length"></span>
                        siswa dipilih
                    </div>

                </div>

                <div
                    x-show="!kelas"
                    class="border border-gray-200 rounded-lg p-4
                           text-sm text-gray-500"
                >
                    Pilih kelas terlebih dahulu untuk menampilkan siswa.
                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- BIDANG LAYANAN --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="bidang_layanan_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Bidang Layanan <span class="text-red-500">*</span>
                </label>

                <select
                    id="bidang_layanan_id"
                    name="bidang_layanan_id"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">-- Pilih Bidang Layanan --</option>

                    @foreach ($bidangLayanan as $bidang)
                        <option
                            value="{{ $bidang->id }}"
                            {{ old('bidang_layanan_id') == $bidang->id ? 'selected' : '' }}
                        >
                            {{ $bidang->nama }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- ===================================================== --}}
            {{-- JENIS LAYANAN --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="jenis_layanan"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Jenis Layanan <span class="text-red-500">*</span>
                </label>

                <select
                    id="jenis_layanan"
                    name="jenis_layanan"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">-- Pilih Jenis Layanan --</option>

                    <option
                        value="Bimbingan Klasikal"
                        {{ old('jenis_layanan') == 'Bimbingan Klasikal' ? 'selected' : '' }}
                    >
                        Bimbingan Klasikal
                    </option>

                    <option
                        value="Bimbingan Kelas Besar"
                        {{ old('jenis_layanan') == 'Bimbingan Kelas Besar' ? 'selected' : '' }}
                    >
                        Bimbingan Kelas Besar
                    </option>

                    <option
                        value="Bimbingan Kelompok"
                        {{ old('jenis_layanan') == 'Bimbingan Kelompok' ? 'selected' : '' }}
                    >
                        Bimbingan Kelompok
                    </option>

                    <option
                        value="Konseling Individu"
                        {{ old('jenis_layanan') == 'Konseling Individu' ? 'selected' : '' }}
                    >
                        Konseling Individu
                    </option>

                    <option
                        value="Konseling Kelompok"
                        {{ old('jenis_layanan') == 'Konseling Kelompok' ? 'selected' : '' }}
                    >
                        Konseling Kelompok
                    </option>

                    <option
                        value="Konsultasi"
                        {{ old('jenis_layanan') == 'Konsultasi' ? 'selected' : '' }}
                    >
                        Konsultasi
                    </option>

                    <option
                        value="Kolaborasi"
                        {{ old('jenis_layanan') == 'Kolaborasi' ? 'selected' : '' }}
                    >
                        Kolaborasi
                    </option>
                </select>
            </div>


            {{-- ===================================================== --}}
            {{-- SASARAN --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="sasaran"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Sasaran
                </label>

                <input
                    type="text"
                    id="sasaran"
                    name="sasaran"
                    value="{{ old('sasaran') }}"
                    placeholder="Contoh: Siswa kelas X DKV 1"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
            </div>


            {{-- ===================================================== --}}
            {{-- URAIAN KEGIATAN --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="uraian_kegiatan"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Uraian Kegiatan
                </label>

                <textarea
                    id="uraian_kegiatan"
                    name="uraian_kegiatan"
                    rows="4"
                    placeholder="Tuliskan uraian kegiatan..."
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >{{ old('uraian_kegiatan') }}</textarea>
            </div>


            {{-- ===================================================== --}}
            {{-- TINDAK LANJUT --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="tindak_lanjut"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Tindak Lanjut
                </label>

                <textarea
                    id="tindak_lanjut"
                    name="tindak_lanjut"
                    rows="4"
                    placeholder="Tuliskan tindak lanjut..."
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >{{ old('tindak_lanjut') }}</textarea>
            </div>


            {{-- ===================================================== --}}
            {{-- KETERANGAN --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="keterangan"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Keterangan
                </label>

                <textarea
                    id="keterangan"
                    name="keterangan"
                    rows="3"
                    placeholder="Keterangan tambahan..."
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >{{ old('keterangan') }}</textarea>
            </div>


            {{-- ===================================================== --}}
            {{-- TANGGAL --}}
            {{-- ===================================================== --}}

            <div>
                <label
                    for="tanggal"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Tanggal <span class="text-red-500">*</span>
                </label>

                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    value="{{ old('tanggal', date('Y-m-d')) }}"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- FOOTER BUTTON --}}
        {{-- ===================================================== --}}

        <div
            class="px-8 py-5 bg-gray-50 border-t border-gray-200
                   flex items-center justify-end gap-3"
        >

            <a
                href="{{ route('peminatan-perencanaan.index') }}"
                class="inline-flex items-center justify-center
                       px-4 py-2.5
                       bg-white border border-gray-300
                       text-gray-700 text-sm font-semibold
                       rounded-lg hover:bg-gray-50 transition"
            >
                Batal
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center
                       px-4 py-2.5
                       bg-blue-600 text-white text-sm font-semibold
                       rounded-lg hover:bg-blue-700 transition"
            >
                Simpan Data
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>
function peminatanForm() {

    const kelasData = @json($kelasList);

    const siswaData = @json($siswaKelas);

    return {

        tahunAjaran: @json(old('tahun_ajaran_id', '')),

        tingkat: '',

        jurusan: '',

        kelas: @json(old('kelas_id', '')),

        pencarian: '',

        siswaTerpilih: @json(
            old('siswa_id', [])
        ).map(String),

        daftarJurusan: [],

        daftarKelas: [],


        /* ========================================================= */
        /* UBAH TAHUN AJARAN */
        /* ========================================================= */

        ubahTahunAjaran() {

            this.tingkat = '';

            this.jurusan = '';

            this.kelas = '';

            this.daftarJurusan = [];

            this.daftarKelas = [];

            this.pencarian = '';

            this.siswaTerpilih = [];
        },


        /* ========================================================= */
        /* UBAH TINGKAT */
        /* ========================================================= */

        ubahTingkat() {

            this.jurusan = '';

            this.kelas = '';

            this.daftarJurusan = [];

            this.daftarKelas = [];

            this.pencarian = '';

            this.siswaTerpilih = [];

            if (!this.tahunAjaran || !this.tingkat) {
                return;
            }

            const hasil = kelasData.filter(item =>

                String(item.tahun_ajaran_id) ===
                    String(this.tahunAjaran)

                &&

                String(item.tingkat) ===
                    String(this.tingkat)
            );


            const map = new Map();

            hasil.forEach(item => {

                if (!map.has(String(item.jurusan_id))) {

                    map.set(
                        String(item.jurusan_id),
                        {
                            id: item.jurusan_id,

                            label:
                                (
                                    item.jurusan_kode
                                    ? item.jurusan_kode + ' - '
                                    : ''
                                )
                                +
                                (
                                    item.jurusan_nama
                                    ?? 'Jurusan'
                                )
                        }
                    );

                }

            });


            this.daftarJurusan =
                Array.from(map.values());

        },


        /* ========================================================= */
        /* UBAH JURUSAN */
        /* ========================================================= */

        ubahJurusan() {

            this.kelas = '';

            this.daftarKelas = [];

            this.pencarian = '';

            this.siswaTerpilih = [];

            if (
                !this.tahunAjaran ||
                !this.tingkat ||
                !this.jurusan
            ) {
                return;
            }


            this.daftarKelas =
                kelasData.filter(item =>

                    String(item.tahun_ajaran_id) ===
                        String(this.tahunAjaran)

                    &&

                    String(item.tingkat) ===
                        String(this.tingkat)

                    &&

                    String(item.jurusan_id) ===
                        String(this.jurusan)

                ).map(item => ({

                    id: item.id,

                    nama_kelas: item.nama_kelas

                }));

        },


        /* ========================================================= */
        /* UBAH KELAS */
        /* ========================================================= */

        ubahKelas() {

            this.pencarian = '';

            this.siswaTerpilih = [];

        },


        /* ========================================================= */
        /* SISWA */
        /* ========================================================= */

        get siswaKelas() {

            if (!this.kelas) {
                return [];
            }


            return siswaData
                .filter(item =>
                    String(item.kelas_id) ===
                    String(this.kelas)
                )
                .map(item => ({

                    id: item.siswa_id,

                    nama: item.nama_lengkap,

                    nis: item.nis,

                    nisn: item.nisn,

                    jenis_kelamin:
                        item.jenis_kelamin

                }));

        },


        /* ========================================================= */
        /* FILTER SISWA */
        /* ========================================================= */

        get siswaTampil() {

            const keyword =
                this.pencarian
                    .toLowerCase()
                    .trim();


            if (!keyword) {
                return this.siswaKelas;
            }


            return this.siswaKelas.filter(siswa => {

                return (

                    (siswa.nama || '')
                        .toLowerCase()
                        .includes(keyword)

                    ||

                    (siswa.nis || '')
                        .toLowerCase()
                        .includes(keyword)

                    ||

                    (siswa.nisn || '')
                        .toLowerCase()
                        .includes(keyword)

                );

            });

        },


        /* ========================================================= */
        /* PILIH SISWA */
        /* ========================================================= */

        toggleSiswa(id) {

            id = String(id);

            if (this.siswaTerpilih.includes(id)) {

                this.siswaTerpilih =
                    this.siswaTerpilih.filter(
                        item => item !== id
                    );

            } else {

                this.siswaTerpilih.push(id);

            }

        },


        /* ========================================================= */
        /* INIT */
        /* ========================================================= */

        init() {

            if (this.tahunAjaran && this.tingkat) {

                this.ubahTingkat();

            }


            if (this.jurusan) {

                this.ubahJurusan();

            }

        }

    };

}
</script>

<style>
[x-cloak] {
    display: none !important;
}
</style>

@endsection