@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Data Hasil Asesmen Awal
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Perbarui hasil asesmen awal siswa.
        </p>
    </div>

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

    <form action="{{ route('asesmen-awal.update', $asesmenAwal) }}"
          method="POST"
          class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">

        @csrf
        @method('PUT')

        {{-- =========================================================
             TAHUN AJARAN
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Tahun Ajaran <span class="text-red-500">*</span>
            </label>

            <select id="tahun_ajaran_id"
                    name="tahun_ajaran_id"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                <option value="">
                    -- Pilih Tahun Ajaran --
                </option>

                @foreach ($tahunAjaran as $item)
                    <option value="{{ $item->id }}"
                        {{ old('tahun_ajaran_id', $asesmenAwal->tahun_ajaran_id) == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach

            </select>

            <p class="text-sm text-gray-400 mt-2">
                Pilih tahun ajaran untuk melihat tingkat kelas.
            </p>
        </div>


        {{-- =========================================================
             TINGKAT
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Tingkat <span class="text-red-500">*</span>
            </label>

            <select id="tingkat"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                <option value="">
                    -- Pilih Tingkat --
                </option>

            </select>

            <p class="text-sm text-gray-400 mt-2">
                Pilih tingkat kelas.
            </p>
        </div>


        {{-- =========================================================
             JURUSAN
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Jurusan <span class="text-red-500">*</span>
            </label>

            <select id="jurusan_id"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                <option value="">
                    -- Pilih Jurusan --
                </option>

            </select>

            <p class="text-sm text-gray-400 mt-2">
                Pilih jurusan terlebih dahulu untuk melihat kelas.
            </p>
        </div>


        {{-- =========================================================
             KELAS
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Kelas <span class="text-red-500">*</span>
            </label>

            <select id="kelas_id"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                <option value="">
                    -- Pilih Kelas --
                </option>

            </select>

            <p class="text-sm text-gray-400 mt-2">
                Pilih jurusan terlebih dahulu untuk melihat kelas.
            </p>
        </div>


        {{-- =========================================================
             SISWA
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Siswa <span class="text-red-500">*</span>
            </label>

            <select name="siswa_id"
                    id="siswa_id"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                <option value="">
                    -- Pilih Siswa --
                </option>

            </select>

            <p class="text-sm text-gray-400 mt-2">
                Pilih kelas terlebih dahulu untuk melihat siswa.
            </p>
        </div>


        {{-- =========================================================
             GURU BK
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Guru BK <span class="text-red-500">*</span>
            </label>

            <select name="guru_bk_id"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                <option value="">
                    -- Pilih Guru BK --
                </option>

                @foreach ($guruBK as $item)
                    <option value="{{ $item->id }}"
                        {{ old('guru_bk_id', $asesmenAwal->guru_bk_id) == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_lengkap }}
                    </option>
                @endforeach

            </select>
        </div>


        {{-- =========================================================
             TANGGAL ASESMEN
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Tanggal Asesmen <span class="text-red-500">*</span>
            </label>

            <input type="date"
                   name="tanggal_asesmen"
                   value="{{ old('tanggal_asesmen', $asesmenAwal->tanggal_asesmen?->format('Y-m-d')) }}"
                   required
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>


        {{-- =========================================================
             INSTRUMEN
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Instrumen Asesmen <span class="text-red-500">*</span>
            </label>

            <input type="text"
                   name="instrumen"
                   value="{{ old('instrumen', $asesmenAwal->instrumen) }}"
                   required
                   placeholder="Contoh: AKPD / Angket Kebutuhan Siswa"
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>


        {{-- =========================================================
             HASIL ASESMEN
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Hasil Asesmen
            </label>

            <textarea name="hasil"
                      rows="5"
                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('hasil', $asesmenAwal->hasil) }}</textarea>
        </div>


        {{-- =========================================================
             REKOMENDASI
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Rekomendasi
            </label>

            <textarea name="rekomendasi"
                      rows="4"
                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('rekomendasi', $asesmenAwal->rekomendasi) }}</textarea>
        </div>


        {{-- =========================================================
             TINDAK LANJUT
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Tindak Lanjut
            </label>

            <textarea name="tindak_lanjut"
                      rows="4"
                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('tindak_lanjut', $asesmenAwal->tindak_lanjut) }}</textarea>
        </div>


        {{-- =========================================================
             KETERANGAN
        ========================================================== --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Keterangan
            </label>

            <textarea name="keterangan"
                      rows="3"
                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('keterangan', $asesmenAwal->keterangan) }}</textarea>
        </div>


        {{-- =========================================================
             BUTTON
        ========================================================== --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">

            <a href="{{ route('asesmen-awal.index') }}"
               class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-semibold">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold">
                Simpan Perubahan
            </button>

        </div>

    </form>
</div>


{{-- =============================================================
     DATA DARI CONTROLLER + FILTER HIERARKI
============================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const tahunSelect   = document.getElementById('tahun_ajaran_id');
    const tingkatSelect  = document.getElementById('tingkat');
    const jurusanSelect  = document.getElementById('jurusan_id');
    const kelasSelect    = document.getElementById('kelas_id');
    const siswaSelect    = document.getElementById('siswa_id');

    const kelasData = @json($kelasList);
    const siswaData = @json($siswaKelas);

    const siswaLama = "{{ old('siswa_id', $asesmenAwal->siswa_id) }}";
    const tahunLama = "{{ old('tahun_ajaran_id', $asesmenAwal->tahun_ajaran_id) }}";


    /*
    |--------------------------------------------------------------------------
    | Cari kelas siswa yang sedang diedit
    |--------------------------------------------------------------------------
    */

    let kelasLama = null;

    if (siswaLama && tahunLama) {

        kelasLama = kelasData.find(function (kelas) {

            if (String(kelas.tahun_ajaran_id) !== String(tahunLama)) {
                return false;
            }

            return siswaData.some(function (siswa) {
                return String(siswa.siswa_id) === String(siswaLama)
                    && String(siswa.kelas_id) === String(kelas.id);
            });

        });
    }


    /*
    |--------------------------------------------------------------------------
    | TINGKAT
    |--------------------------------------------------------------------------
    */

    function loadTingkat() {

        tingkatSelect.innerHTML = `
            <option value="">
                -- Pilih Tingkat --
            </option>
        `;

        jurusanSelect.innerHTML = `
            <option value="">
                -- Pilih Jurusan --
            </option>
        `;

        kelasSelect.innerHTML = `
            <option value="">
                -- Pilih Kelas --
            </option>
        `;

        siswaSelect.innerHTML = `
            <option value="">
                -- Pilih Siswa --
            </option>
        `;


        const tahunId = tahunSelect.value;

        if (!tahunId) {
            return;
        }


        const tingkatList = [
            ...new Set(
                kelasData
                    .filter(function (kelas) {
                        return String(kelas.tahun_ajaran_id) === String(tahunId);
                    })
                    .map(function (kelas) {
                        return kelas.tingkat;
                    })
            )
        ];


        tingkatList.sort(function (a, b) {
            return Number(a) - Number(b);
        });


        tingkatList.forEach(function (tingkat) {

            const option = document.createElement('option');

            option.value = tingkat;
            option.textContent = tingkat;

            tingkatSelect.appendChild(option);
        });


        if (kelasLama) {
            tingkatSelect.value = kelasLama.tingkat;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | JURUSAN
    |--------------------------------------------------------------------------
    */

    function loadJurusan() {

        jurusanSelect.innerHTML = `
            <option value="">
                -- Pilih Jurusan --
            </option>
        `;

        kelasSelect.innerHTML = `
            <option value="">
                -- Pilih Kelas --
            </option>
        `;

        siswaSelect.innerHTML = `
            <option value="">
                -- Pilih Siswa --
            </option>
        `;


        const tahunId = tahunSelect.value;
        const tingkat  = tingkatSelect.value;

        if (!tahunId || !tingkat) {
            return;
        }


        const jurusanMap = new Map();


        kelasData
            .filter(function (kelas) {

                return String(kelas.tahun_ajaran_id) === String(tahunId)
                    && String(kelas.tingkat) === String(tingkat);

            })
            .forEach(function (kelas) {

                jurusanMap.set(
                    String(kelas.jurusan_id),
                    {
                        id: kelas.jurusan_id,
                        kode: kelas.jurusan_kode,
                        nama: kelas.jurusan_nama
                    }
                );

            });


        Array.from(jurusanMap.values())
            .sort(function (a, b) {
                return String(a.kode).localeCompare(String(b.kode));
            })
            .forEach(function (jurusan) {

                const option = document.createElement('option');

                option.value = jurusan.id;

                option.textContent =
                    jurusan.kode + ' - ' + jurusan.nama;

                jurusanSelect.appendChild(option);
            });


        if (kelasLama) {
            jurusanSelect.value = kelasLama.jurusan_id;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | KELAS
    |--------------------------------------------------------------------------
    */

    function loadKelas() {

        kelasSelect.innerHTML = `
            <option value="">
                -- Pilih Kelas --
            </option>
        `;

        siswaSelect.innerHTML = `
            <option value="">
                -- Pilih Siswa --
            </option>
        `;


        const tahunId = tahunSelect.value;
        const tingkat  = tingkatSelect.value;
        const jurusanId = jurusanSelect.value;


        if (!tahunId || !tingkat || !jurusanId) {
            return;
        }


        const kelasFiltered = kelasData
            .filter(function (kelas) {

                return String(kelas.tahun_ajaran_id) === String(tahunId)
                    && String(kelas.tingkat) === String(tingkat)
                    && String(kelas.jurusan_id) === String(jurusanId);

            })
            .sort(function (a, b) {

                return String(a.nama_kelas)
                    .localeCompare(String(b.nama_kelas));

            });


        kelasFiltered.forEach(function (kelas) {

            const option = document.createElement('option');

            option.value = kelas.id;

            option.textContent =
                kelas.nama_kelas;

            kelasSelect.appendChild(option);
        });


        if (kelasLama) {
            kelasSelect.value = kelasLama.id;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SISWA
    |--------------------------------------------------------------------------
    */

    function loadSiswa() {

        siswaSelect.innerHTML = `
            <option value="">
                -- Pilih Siswa --
            </option>
        `;


        const kelasId = kelasSelect.value;

        if (!kelasId) {
            return;
        }


        const siswaFiltered = siswaData
            .filter(function (siswa) {

                return String(siswa.kelas_id) === String(kelasId);

            })
            .sort(function (a, b) {

                return String(a.nama_lengkap)
                    .localeCompare(String(b.nama_lengkap));

            });


        siswaFiltered.forEach(function (siswa) {

            const option = document.createElement('option');

            option.value = siswa.siswa_id;

            option.textContent =
                (siswa.nis ?? '-') +
                ' - ' +
                siswa.nama_lengkap;

            siswaSelect.appendChild(option);
        });


        /*
        |--------------------------------------------------------------
        | Pilih kembali siswa lama
        |--------------------------------------------------------------
        */

        if (siswaLama) {
            siswaSelect.value = siswaLama;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EVENT
    |--------------------------------------------------------------------------
    */

    tahunSelect.addEventListener('change', function () {

        kelasLama = null;

        loadTingkat();

    });


    tingkatSelect.addEventListener('change', function () {

        loadJurusan();

    });


    jurusanSelect.addEventListener('change', function () {

        loadKelas();

    });


    kelasSelect.addEventListener('change', function () {

        loadSiswa();

    });


    /*
    |--------------------------------------------------------------------------
    | LOAD AWAL SAAT EDIT
    |--------------------------------------------------------------------------
    */

    loadTingkat();

    if (kelasLama) {

        tingkatSelect.value = kelasLama.tingkat;

        loadJurusan();

        jurusanSelect.value = kelasLama.jurusan_id;

        loadKelas();

        kelasSelect.value = kelasLama.id;

        loadSiswa();

        siswaSelect.value = siswaLama;

    }

});
</script>

@endsection