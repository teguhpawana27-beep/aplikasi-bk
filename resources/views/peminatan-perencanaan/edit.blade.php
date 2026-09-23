@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Peminatan & Perencanaan Individu
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Perbarui data layanan peminatan dan perencanaan individu siswa.
        </p>
    </div>

    {{-- ERROR --}}
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            <div class="font-semibold mb-2">
                Terjadi kesalahan:
            </div>

            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('peminatan-perencanaan.update', $peminatan) }}"
        method="POST"
        class="bg-white rounded-xl shadow-sm border border-gray-200"
    >

        @csrf
        @method('PUT')

        <div class="p-8 space-y-6">

            {{-- GURU BK --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Guru BK <span class="text-red-500">*</span>
                </label>

                <select
                    name="guru_bk_id"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">
                        -- Pilih Guru BK --
                    </option>

                    @foreach($guruBK as $guru)
                        <option
                            value="{{ $guru->id }}"
                            {{ old('guru_bk_id', $peminatan->guru_bk_id) == $guru->id ? 'selected' : '' }}
                        >
                            {{ $guru->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- TAHUN AJARAN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>

                <select
                    name="tahun_ajaran_id"
                    id="tahun_ajaran_id"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">
                        -- Pilih Tahun Ajaran --
                    </option>

                    @foreach($tahunAjaran as $tahun)
                        <option
                            value="{{ $tahun->id }}"
                            {{ old('tahun_ajaran_id', $peminatan->tahun_ajaran_id) == $tahun->id ? 'selected' : '' }}
                        >
                            {{ $tahun->nama }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- TINGKAT --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tingkat <span class="text-red-500">*</span>
                </label>

                <select
                    id="tingkat"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">
                        -- Pilih Tingkat --
                    </option>

                    <option
                        value="10"
                        {{ old('tingkat', optional($peminatan->kelas)->tingkat) == '10' ? 'selected' : '' }}
                    >
                        10
                    </option>

                    <option
                        value="11"
                        {{ old('tingkat', optional($peminatan->kelas)->tingkat) == '11' ? 'selected' : '' }}
                    >
                        11
                    </option>

                    <option
                        value="12"
                        {{ old('tingkat', optional($peminatan->kelas)->tingkat) == '12' ? 'selected' : '' }}
                    >
                        12
                    </option>
                </select>
            </div>


            {{-- JURUSAN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jurusan <span class="text-red-500">*</span>
                </label>

                <select
                    id="jurusan_id"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">
                        -- Pilih Jurusan --
                    </option>
                </select>
            </div>


            {{-- KELAS --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Kelas
                </label>

                <select
                    name="kelas_id"
                    id="kelas_id"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">
                        -- Pilih Kelas --
                    </option>
                </select>
            </div>


            {{-- SISWA --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Siswa
                </label>

                <div class="mb-3">
                    <input
                        type="text"
                        id="searchSiswa"
                        placeholder="Cari nama siswa atau NIS..."
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>

                <div
                    id="siswaContainer"
                    class="border border-gray-200 rounded-lg overflow-hidden"
                >

                    <div class="p-4 text-sm text-gray-500">
                        Pilih kelas terlebih dahulu untuk menampilkan siswa.
                    </div>

                </div>

                <div class="mt-2 text-sm text-gray-500">
                    <span id="jumlahSiswa">0</span> siswa dipilih
                </div>
            </div>


            {{-- BIDANG LAYANAN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Bidang Layanan <span class="text-red-500">*</span>
                </label>

                <select
                    name="bidang_layanan_id"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">
                        -- Pilih Bidang Layanan --
                    </option>

                    @foreach($bidangLayanan as $bidang)
                        <option
                            value="{{ $bidang->id }}"
                            {{ old('bidang_layanan_id', $peminatan->bidang_layanan_id) == $bidang->id ? 'selected' : '' }}
                        >
                            {{ $bidang->nama }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- JENIS LAYANAN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jenis Layanan <span class="text-red-500">*</span>
                </label>

                <select
                    name="jenis_layanan"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">
                        -- Pilih Jenis Layanan --
                    </option>

                    @php
                        $jenisLayanan = [
                            'Bimbingan Klasikal',
                            'Bimbingan Kelas Besar',
                            'Bimbingan Kelompok',
                            'Konseling Individu',
                            'Konseling Kelompok',
                            'Konsultasi',
                            'Kolaborasi',
                        ];
                    @endphp

                    @foreach($jenisLayanan as $jenis)
                        <option
                            value="{{ $jenis }}"
                            {{ old('jenis_layanan', $peminatan->jenis_layanan) == $jenis ? 'selected' : '' }}
                        >
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- SASARAN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Sasaran
                </label>

                <input
                    type="text"
                    name="sasaran"
                    value="{{ old('sasaran', $peminatan->sasaran) }}"
                    placeholder="Contoh: Siswa kelas X DKV 1"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
            </div>


            {{-- URAIAN KEGIATAN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Uraian Kegiatan
                </label>

                <textarea
                    name="uraian_kegiatan"
                    rows="4"
                    placeholder="Tuliskan uraian kegiatan..."
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >{{ old('uraian_kegiatan', $peminatan->uraian_kegiatan) }}</textarea>
            </div>


            {{-- TINDAK LANJUT --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tindak Lanjut
                </label>

                <textarea
                    name="tindak_lanjut"
                    rows="4"
                    placeholder="Tuliskan tindak lanjut..."
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >{{ old('tindak_lanjut', $peminatan->tindak_lanjut) }}</textarea>
            </div>


            {{-- KETERANGAN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Keterangan
                </label>

                <textarea
                    name="keterangan"
                    rows="3"
                    placeholder="Keterangan tambahan..."
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >{{ old('keterangan', $peminatan->keterangan) }}</textarea>
            </div>


            {{-- TANGGAL --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tanggal
                </label>

                <input
                    type="date"
                    name="tanggal"
                    value="{{ old(
                        'tanggal',
                        $peminatan->tanggal
                            ? $peminatan->tanggal->format('Y-m-d')
                            : ''
                    ) }}"
                    class="w-full rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

        </div>


        {{-- BUTTON --}}
        <div class="px-8 py-5 bg-gray-50 border-t border-gray-200
                    flex items-center justify-between">

            <a
                href="{{ route('peminatan-perencanaan.index') }}"
                class="inline-flex items-center px-4 py-2.5
                       bg-gray-200 text-gray-700 text-sm font-semibold
                       rounded-lg hover:bg-gray-300 transition"
            >
                Kembali
            </a>

            <button
                type="submit"
                class="inline-flex items-center px-5 py-2.5
                       bg-blue-600 text-white text-sm font-semibold
                       rounded-lg hover:bg-blue-700 transition"
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>


{{-- JAVASCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const tahunAjaran = document.getElementById('tahun_ajaran_id');
    const tingkat = document.getElementById('tingkat');
    const jurusan = document.getElementById('jurusan_id');
    const kelas = document.getElementById('kelas_id');
    const siswaContainer = document.getElementById('siswaContainer');
    const searchSiswa = document.getElementById('searchSiswa');
    const jumlahSiswa = document.getElementById('jumlahSiswa');

    const kelasLama = @json($peminatan->kelas_id);

    const pesertaLama = @json(
        $peminatan->peserta->pluck('siswa_id')->values()
    );


    /*
    |--------------------------------------------------------------------------
    | LOAD JURUSAN
    |--------------------------------------------------------------------------
    */

    function loadJurusan() {

        const tahunId = tahunAjaran.value;
        const tingkatValue = tingkat.value;

        jurusan.innerHTML = `
            <option value="">-- Pilih Jurusan --</option>
        `;

        kelas.innerHTML = `
            <option value="">-- Pilih Kelas --</option>
        `;

        siswaContainer.innerHTML = `
            <div class="p-4 text-sm text-gray-500">
                Pilih kelas terlebih dahulu untuk menampilkan siswa.
            </div>
        `;

        jumlahSiswa.textContent = '0';

        if (!tahunId || !tingkatValue) {
            return;
        }

        fetch(
            `{{ url('/peminatan-perencanaan/jurusan') }}?tahun_ajaran_id=${tahunId}&tingkat=${tingkatValue}`
        )
        .then(response => response.json())
        .then(data => {

            data.forEach(item => {

                const option = document.createElement('option');

                option.value = item.id;

                option.textContent =
                    `${item.kode} - ${item.nama}`;

                jurusan.appendChild(option);

            });

            const jurusanLama =
                @json(optional(optional($peminatan->kelas)->jurusan)->id);

            if (jurusanLama) {
                jurusan.value = jurusanLama;
                loadKelas();
            }

        })
        .catch(error => {

            console.error(error);

            jurusan.innerHTML = `
                <option value="">
                    Gagal memuat jurusan
                </option>
            `;

        });
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD KELAS
    |--------------------------------------------------------------------------
    */

    function loadKelas() {

        const tahunId = tahunAjaran.value;
        const tingkatValue = tingkat.value;
        const jurusanId = jurusan.value;

        kelas.innerHTML = `
            <option value="">-- Pilih Kelas --</option>
        `;

        siswaContainer.innerHTML = `
            <div class="p-4 text-sm text-gray-500">
                Pilih kelas terlebih dahulu untuk menampilkan siswa.
            </div>
        `;

        jumlahSiswa.textContent = '0';

        if (!tahunId || !tingkatValue || !jurusanId) {
            return;
        }

        fetch(
            `{{ url('/peminatan-perencanaan/kelas') }}?tahun_ajaran_id=${tahunId}&tingkat=${tingkatValue}&jurusan_id=${jurusanId}`
        )
        .then(response => response.json())
        .then(data => {

            data.forEach(item => {

                const option = document.createElement('option');

                option.value = item.id;

                option.textContent = item.nama_kelas;

                kelas.appendChild(option);

            });

            if (kelasLama) {
                kelas.value = kelasLama;
                loadSiswa();
            }

        })
        .catch(error => {

            console.error(error);

            kelas.innerHTML = `
                <option value="">
                    Gagal memuat kelas
                </option>
            `;

        });
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD SISWA
    |--------------------------------------------------------------------------
    */

    function loadSiswa() {

        const kelasId = kelas.value;

        siswaContainer.innerHTML = `
            <div class="p-4 text-sm text-gray-500">
                Memuat siswa...
            </div>
        `;

        jumlahSiswa.textContent = '0';

        if (!kelasId) {

            siswaContainer.innerHTML = `
                <div class="p-4 text-sm text-gray-500">
                    Pilih kelas terlebih dahulu untuk menampilkan siswa.
                </div>
            `;

            return;
        }

        fetch(
            `{{ url('/peminatan-perencanaan/siswa') }}?kelas_id=${kelasId}`
        )
        .then(response => response.json())
        .then(data => {

            if (!data.length) {

                siswaContainer.innerHTML = `
                    <div class="p-4 text-sm text-gray-500">
                        Tidak ada siswa pada kelas ini.
                    </div>
                `;

                return;
            }

            siswaContainer.innerHTML = '';

            data.forEach(item => {

                const wrapper = document.createElement('label');

                wrapper.className =
                    'flex items-center gap-4 p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer siswa-item';

                wrapper.dataset.nama =
                    (item.nama_lengkap || '').toLowerCase();

                wrapper.dataset.nis =
                    (item.nis || '').toLowerCase();


                const checkbox = document.createElement('input');

                checkbox.type = 'checkbox';

                checkbox.name = 'siswa_id[]';

                checkbox.value = item.id;

                checkbox.className =
                    'siswa-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500';


                if (pesertaLama.includes(Number(item.id))) {
                    checkbox.checked = true;
                }


                const content = document.createElement('div');

                content.innerHTML = `
                    <div class="font-semibold text-gray-800">
                        ${item.nama_lengkap ?? '-'}
                    </div>

                    <div class="text-xs text-gray-500 mt-1">
                        NIS: ${item.nis ?? '-'}
                    </div>
                `;


                wrapper.appendChild(checkbox);

                wrapper.appendChild(content);

                siswaContainer.appendChild(wrapper);

            });

            updateJumlah();

        })
        .catch(error => {

            console.error(error);

            siswaContainer.innerHTML = `
                <div class="p-4 text-sm text-red-600">
                    Gagal memuat data siswa.
                </div>
            `;

        });
    }


    /*
    |--------------------------------------------------------------------------
    | JUMLAH SISWA
    |--------------------------------------------------------------------------
    */

    function updateJumlah() {

        const checked =
            siswaContainer.querySelectorAll(
                '.siswa-checkbox:checked'
            ).length;

        jumlahSiswa.textContent = checked;
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH SISWA
    |--------------------------------------------------------------------------
    */

    searchSiswa.addEventListener('input', function () {

        const keyword =
            this.value.toLowerCase().trim();

        document
            .querySelectorAll('.siswa-item')
            .forEach(item => {

                const nama =
                    item.dataset.nama || '';

                const nis =
                    item.dataset.nis || '';

                item.style.display =
                    (
                        nama.includes(keyword) ||
                        nis.includes(keyword)
                    )
                        ? 'flex'
                        : 'none';

            });

    });


    /*
    |--------------------------------------------------------------------------
    | EVENT
    |--------------------------------------------------------------------------
    */

    tahunAjaran.addEventListener('change', function () {

        loadJurusan();

    });


    tingkat.addEventListener('change', function () {

        loadJurusan();

    });


    jurusan.addEventListener('change', function () {

        loadKelas();

    });


    kelas.addEventListener('change', function () {

        loadSiswa();

    });


    siswaContainer.addEventListener('change', function (event) {

        if (
            event.target.classList.contains('siswa-checkbox')
        ) {
            updateJumlah();
        }

    });


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD EDIT
    |--------------------------------------------------------------------------
    */

    if (
        tahunAjaran.value &&
        tingkat.value
    ) {

        loadJurusan();

    }

});
</script>

@endsection