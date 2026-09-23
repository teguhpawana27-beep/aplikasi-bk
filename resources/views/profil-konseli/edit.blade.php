@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Edit Profil Konseli
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Perbarui data profil konseli siswa.
            </p>
        </div>

        <a href="{{ route('profil-konseli.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>

            Kembali
        </a>
    </div>


    {{-- FORM CARD --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- CARD HEADER --}}
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Data Profil Konseli
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Silakan perbarui informasi profil konseli.
            </p>
        </div>


        {{-- FORM --}}
        <form action="{{ route('profil-konseli.update', $profilKonseli) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="space-y-6 px-6 py-6">

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4">

                        <div class="flex gap-3">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-500"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                            </svg>

                            <div>
                                <p class="text-sm font-semibold text-red-700">
                                    Terdapat kesalahan:
                                </p>

                                <ul class="mt-1 list-disc pl-5 text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>

                    </div>
                @endif


                {{-- ====================================================== --}}
                {{-- DATA SISWA --}}
                {{-- ====================================================== --}}

                <div>

                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-700">
                        Data Siswa
                    </h3>


                    <div
                        x-data="profilKonseliEdit"
                        x-init="init()"
                        class="grid grid-cols-1 gap-5 md:grid-cols-2"
                    >

                        {{-- TAHUN AJARAN --}}
                        <div>

                            <label for="tahun_ajaran_id"
                                   class="mb-2 block text-sm font-medium text-slate-700">
                                Tahun Ajaran
                            </label>

                            <select
                                id="tahun_ajaran_id"
                                x-model="tahunAjaran"
                                @change="resetTingkat()"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            >

                                <option value="">
                                    Pilih Tahun Ajaran
                                </option>

                                @foreach ($tahunAjaran as $item)

                                    <option value="{{ $item->id }}">
                                        {{ $item->nama }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- TINGKAT --}}
                        <div>

                            <label for="tingkat"
                                   class="mb-2 block text-sm font-medium text-slate-700">
                                Tingkat
                            </label>

                            <select
                                id="tingkat"
                                x-model="tingkat"
                                @change="resetJurusan()"
                                :disabled="!tahunAjaran"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:cursor-not-allowed disabled:bg-slate-100"
                            >

                                <option value="">
                                    Pilih Tingkat
                                </option>

                                <template x-for="item in tingkatList" :key="item">

                                    <option
                                        :value="item"
                                        x-text="item">
                                    </option>

                                </template>

                            </select>

                        </div>


                        {{-- JURUSAN --}}
                        <div>

                            <label for="jurusan_id"
                                   class="mb-2 block text-sm font-medium text-slate-700">
                                Jurusan
                            </label>

                            <select
                                id="jurusan_id"
                                x-model="jurusan"
                                @change="resetKelas()"
                                :disabled="!tingkat"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:cursor-not-allowed disabled:bg-slate-100"
                            >

                                <option value="">
                                    Pilih Jurusan
                                </option>

                                <template
                                    x-for="item in jurusanList"
                                    :key="item.id"
                                >

                                    <option
                                        :value="item.id"
                                        x-text="item.kode ? (item.kode + ' - ' + item.nama) : item.nama">
                                    </option>

                                </template>

                            </select>

                        </div>


                        {{-- KELAS --}}
                        <div>

                            <label for="kelas_id"
                                   class="mb-2 block text-sm font-medium text-slate-700">
                                Kelas
                            </label>

                            <select
                                id="kelas_id"
                                x-model="kelas"
                                @change="siswaId = ''"
                                :disabled="!jurusan"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:cursor-not-allowed disabled:bg-slate-100"
                            >

                                <option value="">
                                    Pilih Kelas
                                </option>

                                <template
                                    x-for="item in kelasFiltered"
                                    :key="item.id"
                                >

                                    <option
                                        :value="item.id"
                                        x-text="item.nama_kelas">
                                    </option>

                                </template>

                            </select>

                        </div>


                        {{-- SISWA --}}
                        <div class="md:col-span-2">

                            <label for="siswa_id"
                                   class="mb-2 block text-sm font-medium text-slate-700">
                                Siswa
                            </label>

                            <select
                                name="siswa_id"
                                id="siswa_id"
                                x-model="siswaId"
                                required
                                :disabled="!kelas"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:cursor-not-allowed disabled:bg-slate-100"
                            >

                                <option value="">
                                    Pilih Siswa
                                </option>

                                <template
                                    x-for="item in siswaFiltered"
                                    :key="item.id"
                                >

                                    <option
                                        :value="item.id"
                                        x-text="(item.nis ? item.nis + ' — ' : '') + item.nama_lengkap">
                                    </option>

                                </template>

                            </select>

                            <p class="mt-2 text-xs text-slate-500">
                                Pilih Tahun Ajaran → Tingkat → Jurusan → Kelas untuk menampilkan daftar siswa.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ====================================================== --}}
                {{-- KONDISI PRIBADI --}}
                {{-- ====================================================== --}}

                <div>

                    <label for="kondisi_pribadi"
                           class="mb-2 block text-sm font-medium text-slate-700">
                        Kondisi Pribadi
                    </label>

                    <textarea
                        name="kondisi_pribadi"
                        id="kondisi_pribadi"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="Tuliskan kondisi pribadi konseli..."
                    >{{ old('kondisi_pribadi', $profilKonseli->kondisi_pribadi) }}</textarea>

                </div>


                {{-- KONDISI SOSIAL --}}
                <div>

                    <label for="kondisi_sosial"
                           class="mb-2 block text-sm font-medium text-slate-700">
                        Kondisi Sosial
                    </label>

                    <textarea
                        name="kondisi_sosial"
                        id="kondisi_sosial"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="Tuliskan kondisi sosial konseli..."
                    >{{ old('kondisi_sosial', $profilKonseli->kondisi_sosial) }}</textarea>

                </div>


                {{-- KONDISI BELAJAR --}}
                <div>

                    <label for="kondisi_belajar"
                           class="mb-2 block text-sm font-medium text-slate-700">
                        Kondisi Belajar
                    </label>

                    <textarea
                        name="kondisi_belajar"
                        id="kondisi_belajar"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="Tuliskan kondisi belajar konseli..."
                    >{{ old('kondisi_belajar', $profilKonseli->kondisi_belajar) }}</textarea>

                </div>


                {{-- KONDISI KARIR --}}
                <div>

                    <label for="kondisi_karir"
                           class="mb-2 block text-sm font-medium text-slate-700">
                        Kondisi Karir
                    </label>

                    <textarea
                        name="kondisi_karir"
                        id="kondisi_karir"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="Tuliskan kondisi karir konseli..."
                    >{{ old('kondisi_karir', $profilKonseli->kondisi_karir) }}</textarea>

                </div>


                {{-- KONDISI KELUARGA --}}
                <div>

                    <label for="kondisi_keluarga"
                           class="mb-2 block text-sm font-medium text-slate-700">
                        Kondisi Keluarga
                    </label>

                    <textarea
                        name="kondisi_keluarga"
                        id="kondisi_keluarga"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="Tuliskan kondisi keluarga konseli..."
                    >{{ old('kondisi_keluarga', $profilKonseli->kondisi_keluarga) }}</textarea>

                </div>


                {{-- CATATAN --}}
                <div>

                    <label for="catatan"
                           class="mb-2 block text-sm font-medium text-slate-700">
                        Catatan
                    </label>

                    <textarea
                        name="catatan"
                        id="catatan"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="Tambahkan catatan jika diperlukan..."
                    >{{ old('catatan', $profilKonseli->catatan) }}</textarea>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">

                <a href="{{ route('profil-konseli.index') }}"
                   class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('profilKonseliEdit', () => ({
        tahunAjaran: @js(old(
            'tahun_ajaran_id',
            optional($siswa->firstWhere('id', $profilKonseli->siswa_id))->tahun_ajaran_id ?? ''
        )),

        tingkat: @js(old(
            'tingkat',
            optional($siswa->firstWhere('id', $profilKonseli->siswa_id))->tingkat ?? ''
        )),

        jurusan: @js(old(
            'jurusan_id',
            optional($siswa->firstWhere('id', $profilKonseli->siswa_id))->jurusan_id ?? ''
        )),

        kelas: @js(old(
            'kelas_id',
            optional($siswa->firstWhere('id', $profilKonseli->siswa_id))->kelas_id ?? ''
        )),

        siswaId: @js(old('siswa_id', $profilKonseli->siswa_id)),

        kelasData: @js($kelasList),
        jurusanData: @js($jurusans),
        siswaData: @js($siswa),

        get tingkatList() {
            const data = this.kelasData.filter(item =>
                String(item.tahun_ajaran_id) === String(this.tahunAjaran)
            );

            return [...new Set(
                data.map(item => item.tingkat)
            )].sort((a, b) => Number(a) - Number(b));
        },

        get jurusanList() {
            const data = this.kelasData.filter(item =>
                String(item.tahun_ajaran_id) === String(this.tahunAjaran) &&
                String(item.tingkat) === String(this.tingkat)
            );

            const ids = [...new Set(
                data
                    .map(item => item.jurusan_id)
                    .filter(id => id !== null && id !== undefined)
                    .map(id => String(id))
            )];

            return this.jurusanData
                .filter(jurusan => ids.includes(String(jurusan.id)))
                .map(jurusan => ({
                    id: jurusan.id,
                    kode: jurusan.kode ?? '',
                    nama: jurusan.nama ?? ''
                }));
        },

        get kelasFiltered() {
            return this.kelasData.filter(item =>
                String(item.tahun_ajaran_id) === String(this.tahunAjaran) &&
                String(item.tingkat) === String(this.tingkat) &&
                String(item.jurusan_id) === String(this.jurusan)
            );
        },

        /*
         * Ambil konteks kelas siswa.
         *
         * Controller bisa mengirim data siswa dalam bentuk:
         * 1. langsung: kelas_id, tahun_ajaran_id, tingkat, jurusan_id
         * 2. melalui relasi: riwayat_kelas -> kelas
         *
         * Fungsi ini menangani keduanya agar dropdown siswa tetap muncul.
         */
        getSiswaContext(item) {
            const direct = {
                tahun_ajaran_id: item.tahun_ajaran_id ?? null,
                tingkat: item.tingkat ?? null,
                jurusan_id: item.jurusan_id ?? null,
                kelas_id: item.kelas_id ?? null,
            };

            if (
                direct.tahun_ajaran_id &&
                direct.tingkat &&
                direct.jurusan_id &&
                direct.kelas_id
            ) {
                return direct;
            }

            const riwayat = item.riwayat_kelas ?? item.riwayatKelas ?? [];

            const history = Array.isArray(riwayat)
                ? riwayat
                : Object.values(riwayat || {});

            const match = history.find(row => {
                const kelas = row.kelas ?? {};
                return String(kelas.tahun_ajaran_id ?? '') === String(this.tahunAjaran ?? '');
            });

            if (!match) {
                return direct;
            }

            const kelas = match.kelas ?? {};
            const jurusan = kelas.jurusan ?? {};

            return {
                tahun_ajaran_id: kelas.tahun_ajaran_id ?? direct.tahun_ajaran_id,
                tingkat: kelas.tingkat ?? direct.tingkat,
                jurusan_id: kelas.jurusan_id ?? direct.jurusan_id ?? jurusan.id,
                kelas_id: kelas.id ?? direct.kelas_id,
            };
        },

        get siswaFiltered() {
            if (!this.kelas) {
                return [];
            }

            return this.siswaData.filter(item => {
                const context = this.getSiswaContext(item);

                return String(context.kelas_id ?? '') === String(this.kelas);
            });
        },

        init() {
            const current = this.siswaData.find(item =>
                String(item.id) === String(this.siswaId)
            );

            if (current) {
                const context = this.getSiswaContext(current);

                if (!this.tahunAjaran && context.tahun_ajaran_id) {
                    this.tahunAjaran = context.tahun_ajaran_id;
                }

                if (!this.tingkat && context.tingkat) {
                    this.tingkat = context.tingkat;
                }

                if (!this.jurusan && context.jurusan_id) {
                    this.jurusan = context.jurusan_id;
                }

                if (!this.kelas && context.kelas_id) {
                    this.kelas = context.kelas_id;
                }
            }
        },

        resetTingkat() {
            this.tingkat = '';
            this.jurusan = '';
            this.kelas = '';
            this.siswaId = '';
        },

        resetJurusan() {
            this.jurusan = '';
            this.kelas = '';
            this.siswaId = '';
        },

        resetKelas() {
            this.kelas = '';
            this.siswaId = '';
        }
    }));
});
</script>

@endsection