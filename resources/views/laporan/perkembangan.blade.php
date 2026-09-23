@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center
                md:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Laporan Perkembangan Siswa
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Menampilkan riwayat layanan dan perkembangan konseli
                berdasarkan Tahun Ajaran yang sedang dipilih.
            </p>
        </div>

        <a href="{{ route('laporan.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2
                  rounded-xl border border-gray-200 bg-white
                  text-gray-600 hover:bg-gray-50 transition">

            <i class="fas fa-arrow-left"></i>
            Kembali

        </a>

    </div>


    {{-- TAHUN AJARAN AKTIF --}}
    <div class="bg-blue-50 border border-blue-100
                rounded-2xl p-5 mb-6">

        <div class="flex items-center gap-4">

            <div class="w-12 h-12 rounded-xl bg-blue-100
                        flex items-center justify-center shrink-0">

                <i class="fas fa-calendar-alt
                          text-blue-600 text-lg"></i>

            </div>

            <div>

                <p class="text-sm text-blue-600 font-medium">
                    Tahun Ajaran Aktif
                </p>

                <h2 class="text-lg font-bold text-gray-800 mt-1">
                    {{ $tahunAjaran?->nama ?? '-' }}
                </h2>

                @if($tahunAjaran)

                    <p class="text-xs text-gray-500 mt-1">

                        {{ \Carbon\Carbon::parse($tahunAjaran->tanggal_mulai)->format('d M Y') }}

                        -

                        {{ \Carbon\Carbon::parse($tahunAjaran->tanggal_selesai)->format('d M Y') }}

                    </p>

                @endif

            </div>

        </div>

    </div>


    {{-- FILTER SISWA --}}
    <div class="bg-white rounded-2xl border border-gray-100
                shadow-sm p-6 mb-6">

        <div class="mb-5">

            <h2 class="font-semibold text-gray-800">
                Pilih Konseli
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Pilih Tingkat, Jurusan, Kelas, kemudian Siswa
                pada Tahun Ajaran aktif.
            </p>

        </div>


        <form method="GET"
              action="{{ route('laporan.perkembangan') }}"
              id="filterForm">

            <div class="grid grid-cols-1 md:grid-cols-2
                        xl:grid-cols-4 gap-4">


                {{-- TINGKAT --}}
                <div>

                    <label class="block text-sm font-medium
                                  text-gray-700 mb-2">

                        Tingkat

                    </label>

                    <select name="tingkat"
                            id="tingkat"
                            class="w-full rounded-xl border-gray-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                            {{ !$tahunAjaran ? 'disabled' : '' }}>

                        <option value="">
                            Pilih Tingkat
                        </option>

                    </select>

                </div>


                {{-- JURUSAN --}}
                <div>

                    <label class="block text-sm font-medium
                                  text-gray-700 mb-2">

                        Jurusan

                    </label>

                    <select name="jurusan_id"
                            id="jurusan_id"
                            class="w-full rounded-xl border-gray-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                            {{ !$tingkat ? 'disabled' : '' }}>

                        <option value="">
                            Pilih Jurusan
                        </option>

                    </select>

                </div>


                {{-- KELAS --}}
                <div>

                    <label class="block text-sm font-medium
                                  text-gray-700 mb-2">

                        Kelas

                    </label>

                    <select name="kelas_id"
                            id="kelas_id"
                            class="w-full rounded-xl border-gray-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                            {{ !$jurusanId ? 'disabled' : '' }}>

                        <option value="">
                            Pilih Kelas
                        </option>

                    </select>

                </div>


                {{-- SISWA --}}
                <div>

                    <label class="block text-sm font-medium
                                  text-gray-700 mb-2">

                        Siswa

                    </label>

                    <select name="siswa_id"
                            id="siswa_id"
                            class="w-full rounded-xl border-gray-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                            {{ !$kelasId ? 'disabled' : '' }}>

                        <option value="">
                            Pilih Siswa
                        </option>

                        @foreach($siswa as $item)

                            <option value="{{ $item->id }}"
                                {{ $siswaId == $item->id ? 'selected' : '' }}>

                                {{ $item->nis ?? '-' }}
                                -
                                {{ $item->nama_lengkap }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- BUTTON --}}
            <div class="flex justify-end mt-5">

                <button type="submit"
                        class="inline-flex items-center gap-2
                               px-6 py-2.5 rounded-xl
                               bg-blue-600 text-white
                               hover:bg-blue-700 transition">

                    <i class="fas fa-search"></i>

                    Tampilkan

                </button>

            </div>

        </form>

    </div>


    {{-- HASIL --}}
    @if($selectedSiswa)

        {{-- PROFIL SISWA --}}
        <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm p-6 mb-6">

            <div class="flex flex-col md:flex-row
                        md:items-center gap-5">

                <div class="w-16 h-16 rounded-2xl bg-blue-50
                            text-blue-600 flex items-center
                            justify-center">

                    <i class="fas fa-user-graduate text-2xl"></i>

                </div>


                <div>

                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $selectedSiswa->nama_lengkap }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        NIS: {{ $selectedSiswa->nis ?? '-' }}
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Tahun Ajaran:
                        <span class="font-medium text-gray-700">
                            {{ $tahunAjaran?->nama ?? '-' }}
                        </span>
                    </p>

                </div>


                {{-- PDF --}}
                <div class="md:ml-auto">

                    <a href="{{ route(
                        'laporan.perkembangan.download',
                        request()->only([
                            'tingkat',
                            'jurusan_id',
                            'kelas_id',
                            'siswa_id'
                        ])
                    ) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2
                              px-5 py-2.5 rounded-xl
                              bg-red-600 text-white
                              hover:bg-red-700 transition">

                        <i class="fas fa-file-pdf"></i>

                        Cetak PDF

                    </a>

                </div>

            </div>

        </div>


        {{-- RINGKASAN --}}
        <div class="grid grid-cols-1 md:grid-cols-2
                    xl:grid-cols-5 gap-4 mb-6">


            {{-- LAYANAN DASAR --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    Layanan Dasar
                </p>

                <h3 class="text-2xl font-bold text-blue-600 mt-2">
                    {{ $layananDasar->count() }}
                </h3>

            </div>


            {{-- PEMINATAN --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    Peminatan
                </p>

                <h3 class="text-2xl font-bold text-purple-600 mt-2">
                    {{ $peminatan->count() }}
                </h3>

            </div>


            {{-- RESPONSIF --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    Responsif
                </p>

                <h3 class="text-2xl font-bold text-orange-600 mt-2">
                    {{ $responsif->count() }}
                </h3>

            </div>


            {{-- TINDAK LANJUT --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    Tindak Lanjut
                </p>

                <h3 class="text-2xl font-bold text-emerald-600 mt-2">
                    {{ $tindakLanjut->count() }}
                </h3>

            </div>


            {{-- ASESMEN --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    Asesmen Awal
                </p>

                <h3 class="text-2xl font-bold text-pink-600 mt-2">
                    {{ $asesmenAwal->count() }}
                </h3>

            </div>

        </div>


        {{-- LAYANAN DASAR --}}
        <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-800">
                    Riwayat Layanan Dasar
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left">
                                No
                            </th>

                            <th class="px-5 py-3 text-left">
                                Tanggal
                            </th>

                            <th class="px-5 py-3 text-left">
                                Kegiatan
                            </th>

                            <th class="px-5 py-3 text-left">
                                Kelas
                            </th>

                            <th class="px-5 py-3 text-left">
                                Guru BK
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @forelse($layananDasar as $item)

                            <tr class="hover:bg-gray-50">

                                <td class="px-5 py-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->tanggal
                                        ? \Carbon\Carbon::parse(
                                            $item->tanggal
                                        )->format('d/m/Y')
                                        : '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->nama_kegiatan
                                        ?? $item->kegiatan
                                        ?? $item->topik
                                        ?? '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->kelas->nama_kelas ?? '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->guruBK->nama_lengkap ?? '-' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="px-5 py-8 text-center
                                           text-gray-400">

                                    Tidak ada riwayat layanan dasar.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PEMINATAN --}}
        <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-800">
                    Riwayat Peminatan & Perencanaan
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left">
                                No
                            </th>

                            <th class="px-5 py-3 text-left">
                                Tanggal
                            </th>

                            <th class="px-5 py-3 text-left">
                                Kegiatan
                            </th>

                            <th class="px-5 py-3 text-left">
                                Kelas
                            </th>

                            <th class="px-5 py-3 text-left">
                                Guru BK
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @forelse($peminatan as $item)

                            <tr class="hover:bg-gray-50">

                                <td class="px-5 py-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->tanggal
                                        ? \Carbon\Carbon::parse(
                                            $item->tanggal
                                        )->format('d/m/Y')
                                        : '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->nama_kegiatan
                                        ?? $item->kegiatan
                                        ?? $item->topik
                                        ?? '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->kelas->nama_kelas ?? '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->guruBK->nama_lengkap ?? '-' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="px-5 py-8 text-center
                                           text-gray-400">

                                    Tidak ada riwayat peminatan.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- RESPONSIF --}}
        <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-800">
                    Riwayat Layanan Responsif
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left">
                                No
                            </th>

                            <th class="px-5 py-3 text-left">
                                Tanggal
                            </th>

                            <th class="px-5 py-3 text-left">
                                Kegiatan
                            </th>

                            <th class="px-5 py-3 text-left">
                                Kelas
                            </th>

                            <th class="px-5 py-3 text-left">
                                Guru BK
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @forelse($responsif as $item)

                            <tr class="hover:bg-gray-50">

                                <td class="px-5 py-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->tanggal
                                        ? \Carbon\Carbon::parse(
                                            $item->tanggal
                                        )->format('d/m/Y')
                                        : '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->nama_kegiatan
                                        ?? $item->kegiatan
                                        ?? $item->topik
                                        ?? '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->kelas->nama_kelas ?? '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->guruBK->nama_lengkap ?? '-' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="px-5 py-8 text-center
                                           text-gray-400">

                                    Tidak ada riwayat layanan responsif.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- TINDAK LANJUT --}}
        <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-800">
                    Tindak Lanjut
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left">
                                No
                            </th>

                            <th class="px-5 py-3 text-left">
                                Tanggal
                            </th>

                            <th class="px-5 py-3 text-left">
                                Rencana
                            </th>

                            <th class="px-5 py-3 text-left">
                                Guru BK
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @forelse($tindakLanjut as $item)

                            <tr class="hover:bg-gray-50">

                                <td class="px-5 py-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->tanggal_rencana
                                        ? \Carbon\Carbon::parse(
                                            $item->tanggal_rencana
                                        )->format('d/m/Y')
                                        : '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->rencana
                                        ?? $item->keterangan
                                        ?? '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->guruBK->nama_lengkap ?? '-' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4"
                                    class="px-5 py-8 text-center
                                           text-gray-400">

                                    Tidak ada tindak lanjut.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ASESMEN AWAL --}}
        <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-800">
                    Asesmen Awal
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left">
                                No
                            </th>

                            <th class="px-5 py-3 text-left">
                                Tanggal
                            </th>

                            <th class="px-5 py-3 text-left">
                                Guru BK
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @forelse($asesmenAwal as $item)

                            <tr class="hover:bg-gray-50">

                                <td class="px-5 py-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->tanggal_asesmen
                                        ? \Carbon\Carbon::parse(
                                            $item->tanggal_asesmen
                                        )->format('d/m/Y')
                                        : '-' }}

                                </td>

                                <td class="px-5 py-3">

                                    {{ $item->guruBK->nama_lengkap ?? '-' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3"
                                    class="px-5 py-8 text-center
                                           text-gray-400">

                                    Tidak ada data asesmen awal.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


    @else

        {{-- EMPTY STATE --}}
        <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm p-12 text-center">

            <div class="w-16 h-16 mx-auto rounded-2xl
                        bg-gray-100 flex items-center
                        justify-center">

                <i class="fas fa-user-search
                          text-2xl text-gray-400"></i>

            </div>


            <h2 class="text-lg font-semibold text-gray-700 mt-5">
                Belum Ada Siswa Dipilih
            </h2>


            <p class="text-sm text-gray-400 mt-2">
                Silakan pilih Tingkat, Jurusan, Kelas,
                dan Siswa untuk melihat perkembangan
                pada Tahun Ajaran
                {{ $tahunAjaran?->nama ?? '-' }}.
            </p>

        </div>

    @endif

</div>


{{-- JAVASCRIPT HIERARKI --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const tingkat = document.getElementById('tingkat');
    const jurusan = document.getElementById('jurusan_id');
    const kelas = document.getElementById('kelas_id');
    const siswa = document.getElementById('siswa_id');

    const selectedTingkat = @json($tingkat);
    const selectedJurusan = @json($jurusanId);
    const selectedKelas = @json($kelasId);
    const selectedSiswa = @json($siswaId);


    /*
    |--------------------------------------------------------------------------
    | RESET SELECT
    |--------------------------------------------------------------------------
    */

    function resetSelect(select, text) {

        if (!select) return;

        select.innerHTML =
            `<option value="">${text}</option>`;

        select.disabled = true;
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD TINGKAT
    |--------------------------------------------------------------------------
    */

    function loadTingkat(selected = '') {

        resetSelect(
            tingkat,
            'Pilih Tingkat'
        );

        resetSelect(
            jurusan,
            'Pilih Jurusan'
        );

        resetSelect(
            kelas,
            'Pilih Kelas'
        );

        resetSelect(
            siswa,
            'Pilih Siswa'
        );


        fetch(`{{ route('laporan.tingkat') }}`)

            .then(response => {

                if (!response.ok) {
                    throw new Error(
                        'Gagal mengambil data tingkat.'
                    );
                }

                return response.json();

            })

            .then(data => {

                tingkat.disabled = false;

                data.forEach(item => {

                    const option =
                        document.createElement('option');

                    option.value = item;
                    option.textContent = item;

                    if (item == selected) {
                        option.selected = true;
                    }

                    tingkat.appendChild(option);

                });


                if (selected) {

                    loadJurusan(
                        selected,
                        selectedJurusan
                    );

                }

            })

            .catch(error => {

                console.error(error);

            });

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD JURUSAN
    |--------------------------------------------------------------------------
    */

    function loadJurusan(
        tingkatValue,
        selected = ''
    ) {

        resetSelect(
            jurusan,
            'Pilih Jurusan'
        );

        resetSelect(
            kelas,
            'Pilih Kelas'
        );

        resetSelect(
            siswa,
            'Pilih Siswa'
        );


        if (!tingkatValue) return;


        fetch(
            `{{ route('laporan.jurusan') }}?tingkat=${encodeURIComponent(tingkatValue)}`
        )

            .then(response => {

                if (!response.ok) {
                    throw new Error(
                        'Gagal mengambil data jurusan.'
                    );
                }

                return response.json();

            })

            .then(data => {

                jurusan.disabled = false;

                data.forEach(item => {

                    const option =
                        document.createElement('option');

                    option.value = item.id;

                    option.textContent =
                        `${item.kode} - ${item.nama}`;

                    if (item.id == selected) {
                        option.selected = true;
                    }

                    jurusan.appendChild(option);

                });


                if (selected) {

                    loadKelas(
                        tingkatValue,
                        selected,
                        selectedKelas
                    );

                }

            })

            .catch(error => {

                console.error(error);

            });

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD KELAS
    |--------------------------------------------------------------------------
    */

    function loadKelas(
        tingkatValue,
        jurusanId,
        selected = ''
    ) {

        resetSelect(
            kelas,
            'Pilih Kelas'
        );

        resetSelect(
            siswa,
            'Pilih Siswa'
        );


        if (
            !tingkatValue ||
            !jurusanId
        ) {
            return;
        }


        fetch(
            `{{ route('laporan.kelas') }}?tingkat=${encodeURIComponent(tingkatValue)}&jurusan_id=${jurusanId}`
        )

            .then(response => {

                if (!response.ok) {
                    throw new Error(
                        'Gagal mengambil data kelas.'
                    );
                }

                return response.json();

            })

            .then(data => {

                kelas.disabled = false;

                data.forEach(item => {

                    const option =
                        document.createElement('option');

                    option.value = item.id;

                    option.textContent =
                        item.nama_kelas;

                    if (item.id == selected) {
                        option.selected = true;
                    }

                    kelas.appendChild(option);

                });


                if (selected) {

                    loadSiswa(
                        selected,
                        selectedSiswa
                    );

                }

            })

            .catch(error => {

                console.error(error);

            });

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD SISWA
    |--------------------------------------------------------------------------
    */

    function loadSiswa(
        kelasId,
        selected = ''
    ) {

        resetSelect(
            siswa,
            'Pilih Siswa'
        );


        if (!kelasId) return;


        fetch(
            `{{ route('laporan.siswa') }}?kelas_id=${kelasId}`
        )

            .then(response => {

                if (!response.ok) {
                    throw new Error(
                        'Gagal mengambil data siswa.'
                    );
                }

                return response.json();

            })

            .then(data => {

                siswa.disabled = false;

                data.forEach(item => {

                    const option =
                        document.createElement('option');

                    option.value = item.id;

                    option.textContent =
                        `${item.nis ?? '-'} - ${item.nama_lengkap}`;

                    if (item.id == selected) {
                        option.selected = true;
                    }

                    siswa.appendChild(option);

                });

            })

            .catch(error => {

                console.error(error);

            });

    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE TINGKAT
    |--------------------------------------------------------------------------
    */

    tingkat.addEventListener(
        'change',
        function () {

            loadJurusan(
                this.value
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CHANGE JURUSAN
    |--------------------------------------------------------------------------
    */

    jurusan.addEventListener(
        'change',
        function () {

            loadKelas(
                tingkat.value,
                this.value
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CHANGE KELAS
    |--------------------------------------------------------------------------
    */

    kelas.addEventListener(
        'change',
        function () {

            loadSiswa(
                this.value
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | LOAD DATA SAAT HALAMAN DIBUKA
    |--------------------------------------------------------------------------
    */

    if (tingkat) {

        loadTingkat(
            selectedTingkat
        );

    }

});

</script>

@endsection