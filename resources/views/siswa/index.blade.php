@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-7xl">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Data Siswa
            </h1>

            <p class="mt-1 text-gray-500">
                Kelola data siswa SMKN 1 Majalaya.
            </p>
        </div>


        {{-- TOMBOL --}}

        <div class="flex flex-wrap gap-3">

            {{-- TEMPLATE EXCEL --}}

            <a
                href="{{ route('siswa.template') }}"
                class="inline-flex items-center justify-center rounded-lg border border-green-500 bg-white px-5 py-3 text-sm font-semibold text-green-600 shadow-sm transition hover:bg-green-50"
            >
                ↓ Template Excel
            </a>


            {{-- IMPORT EXCEL --}}

            <button
                type="button"
                onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="inline-flex items-center justify-center rounded-lg border border-indigo-500 bg-white px-5 py-3 text-sm font-semibold text-indigo-600 shadow-sm transition hover:bg-indigo-50"
            >
                ↑ Import Excel
            </button>


            {{-- TAMBAH SISWA --}}

            <a
                href="{{ route('siswa.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
            >
                + Tambah Siswa
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS --}}
    {{-- ========================================================= --}}

    @if (session('success'))

        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERROR --}}
    {{-- ========================================================= --}}

    @if (session('error'))

        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            {{ session('error') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- VALIDATION ERROR --}}
    {{-- ========================================================= --}}

    @if ($errors->any())

        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

            <div class="mb-2 font-semibold">
                Terjadi kesalahan:
            </div>

            <ul class="list-disc pl-5">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FILTER TINGKAT --}}
    {{-- ========================================================= --}}

    <div class="mb-6 overflow-hidden rounded-xl border border-indigo-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-5">

            <h2 class="text-xl font-semibold text-slate-800">
                Pilih Tingkat
            </h2>

            <p class="mt-1 text-gray-500">
                Pilih tingkat kelas untuk melihat jurusan.
            </p>

        </div>


        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-3">

            @foreach ($tingkatList as $item)

                <a
                    href="{{ route('siswa.index', [
                        'tingkat' => $item
                    ]) }}"
                    class="rounded-xl border-2 p-6 text-center transition
                    {{ (string) $tingkat === (string) $item
                        ? 'border-indigo-500 bg-indigo-50'
                        : 'border-indigo-100 bg-indigo-50/30 hover:border-indigo-300 hover:bg-indigo-50'
                    }}"
                >

                    <div class="text-4xl font-bold text-indigo-700">
                        {{ $item }}
                    </div>

                    <div class="mt-2 text-sm text-gray-600">
                        Lihat Jurusan
                    </div>

                </a>

            @endforeach

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- JURUSAN --}}
    {{-- ========================================================= --}}

    @if ($tingkat)

        <div class="mb-6 overflow-hidden rounded-xl border border-indigo-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h2 class="text-xl font-semibold text-slate-800">
                    Jurusan Kelas {{ $tingkat }}
                </h2>

                <p class="mt-1 text-gray-500">
                    Pilih jurusan untuk melihat daftar kelas.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-3">

                @forelse ($jurusanList as $jurusan)

                    <a
                        href="{{ route('siswa.index', [
                            'tingkat' => $tingkat,
                            'jurusan' => $jurusan->id
                        ]) }}"
                        class="rounded-xl border-2 p-6 transition
                        {{ (string) $jurusanId === (string) $jurusan->id
                            ? 'border-indigo-500 bg-indigo-50'
                            : 'border-indigo-100 bg-indigo-50/30 hover:border-indigo-300 hover:bg-indigo-50'
                        }}"
                    >

                        <div class="text-2xl font-bold text-indigo-700">
                            {{ $jurusan->kode }}
                        </div>

                        <div class="mt-2 text-sm font-medium text-gray-700">
                            {{ $jurusan->nama }}
                        </div>

                        <div class="mt-4 text-sm text-gray-500">
                            Lihat Kelas →
                        </div>

                    </a>

                @empty

                    <div class="col-span-full py-10 text-center text-gray-500">
                        Belum ada jurusan untuk tingkat ini.
                    </div>

                @endforelse

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- KELAS --}}
    {{-- ========================================================= --}}

    @if ($tingkat && $jurusanId)

        <div class="mb-6 overflow-hidden rounded-xl border border-purple-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h2 class="text-xl font-semibold text-slate-800">
                    Kelas {{ $tingkat }}
                </h2>

                <p class="mt-1 text-gray-500">
                    Pilih kelas untuk melihat daftar siswa.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-3">

                @forelse ($kelasList as $kelas)

                    <a
                        href="{{ route('siswa.index', [
                            'tingkat' => $tingkat,
                            'jurusan' => $jurusanId,
                            'kelas' => $kelas->id
                        ]) }}"
                        class="rounded-xl border-2 p-6 transition
                        {{ (string) $kelasId === (string) $kelas->id
                            ? 'border-indigo-500 bg-indigo-50'
                            : 'border-indigo-100 bg-indigo-50/30 hover:border-indigo-300 hover:bg-indigo-50'
                        }}"
                    >

                        <div class="text-2xl font-bold text-indigo-700">
                            {{ $kelas->nama_kelas }}
                        </div>

                        <div class="mt-2 text-sm text-gray-600">
                            {{ $kelas->jurusan->nama ?? '-' }}
                        </div>

                        <div class="mt-4 text-sm text-gray-500">
                            Lihat Siswa →
                        </div>

                    </a>

                @empty

                    <div class="col-span-full py-10 text-center text-gray-500">
                        Belum ada kelas untuk jurusan ini.
                    </div>

                @endforelse

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- DAFTAR SISWA --}}
    {{-- ========================================================= --}}

    @if ($kelasTerpilih)

        <div class="overflow-hidden rounded-xl border border-green-200 bg-white shadow-sm">

            <div class="flex flex-col gap-3 border-b border-gray-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-xl font-semibold text-slate-800">
                        Daftar Siswa
                    </h2>

                    <p class="mt-1 text-gray-500">
                        Siswa yang terdaftar pada
                        <span class="font-semibold text-indigo-600">
                            {{ $kelasTerpilih->nama_kelas }}
                        </span>
                    </p>

                </div>

            </div>


            @if ($siswa->count() > 0)

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    NIS
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    NISN
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Nama Lengkap
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Jenis Kelamin
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100 bg-white">

                            @foreach ($siswa as $item)

                                <tr class="hover:bg-gray-50">

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $item->nis }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $item->nisn ?? '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-gray-800">
                                        {{ $item->nama_lengkap }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $item->jenis_kelamin }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">

                                        @if (strtolower($item->status) === 'aktif')

                                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                Aktif
                                            </span>

                                        @elseif (strtolower($item->status) === 'lulus')

                                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Lulus
                                            </span>

                                        @else

                                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                {{ ucfirst($item->status) }}
                                            </span>

                                        @endif

                                    </td>


                                    <td class="whitespace-nowrap px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <a
                                                href="{{ route('siswa.show', $item) }}"
                                                class="text-sm font-medium text-gray-600 hover:text-gray-900"
                                            >
                                                Lihat
                                            </a>


                                            <a
                                                href="{{ route('siswa.edit', $item) }}"
                                                class="rounded-lg bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-100"
                                            >
                                                Edit
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route('siswa.destroy', $item) }}"
                                                onsubmit="return confirm('Yakin ingin menghapus data siswa {{ $item->nama_lengkap }}? Data yang sudah dihapus tidak dapat dikembalikan.')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-sm font-medium text-red-600 hover:text-red-800"
                                                >
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">

                    <div class="mb-4 text-4xl">
                        📚
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800">
                        Belum ada siswa di kelas ini
                    </h3>

                    <p class="mt-1 max-w-xl text-sm text-gray-500">
                        Import file Excel untuk memasukkan siswa ke kelas
                        {{ $kelasTerpilih->nama_kelas }}.
                    </p>

                    <button
                        type="button"
                        onclick="document.getElementById('importModal').classList.remove('hidden')"
                        class="mt-5 rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        ↑ Import Excel
                    </button>

                </div>

            @endif

        </div>

    @elseif (!$tingkat)

        {{-- BELUM PILIH TINGKAT --}}

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">

                <div class="mb-4 text-4xl">
                    🎓
                </div>

                <h3 class="text-lg font-semibold text-gray-800">
                    Pilih Tingkat Terlebih Dahulu
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Pilih kelas 10, 11, atau 12 untuk melihat jurusan dan kelas.
                </p>

            </div>

        </div>

    @endif


</div>


{{-- ============================================================= --}}
{{-- MODAL IMPORT EXCEL --}}
{{-- ============================================================= --}}

<div
    id="importModal"
    class="fixed inset-0 z-50 hidden overflow-y-auto"
>

    <div class="flex min-h-screen items-center justify-center px-4 py-8">

        {{-- BACKDROP --}}

        <div
            class="fixed inset-0 bg-black/50"
            onclick="document.getElementById('importModal').classList.add('hidden')"
        ></div>


        {{-- MODAL --}}

        <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white shadow-2xl">

            <div class="border-b border-gray-200 px-6 py-5">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">
                            Import Data Siswa
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Upload data siswa melalui Excel.
                        </p>

                    </div>

                    <button
                        type="button"
                        onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="text-2xl text-gray-400 hover:text-gray-700"
                    >
                        ×
                    </button>

                </div>

            </div>


            <form
                method="POST"
                action="{{ route('siswa.import') }}"
                enctype="multipart/form-data"
            >

                @csrf


                {{-- KELAS YANG SEDANG DIPILIH --}}

                @if ($kelasTerpilih)

                    <input
                        type="hidden"
                        name="kelas_id"
                        value="{{ $kelasTerpilih->id }}"
                    >

                    <input
                        type="hidden"
                        name="tingkat"
                        value="{{ $tingkat }}"
                    >

                    <input
                        type="hidden"
                        name="jurusan"
                        value="{{ $jurusanId }}"
                    >

                    <div class="mx-6 mt-6 rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3">

                        <p class="text-sm text-indigo-700">

                            Siswa akan langsung dimasukkan ke:

                            <span class="font-bold">
                                {{ $kelasTerpilih->nama_kelas }}
                            </span>

                        </p>

                    </div>

                @endif


                <div class="space-y-5 px-6 py-6">

                    {{-- FILE --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            File Excel
                        </label>

                        <input
                            type="file"
                            name="file"
                            accept=".xlsx,.xls"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100"
                        >

                        <p class="mt-2 text-xs text-gray-500">
                            Format yang diterima: .xlsx atau .xls. Maksimal 10 MB.
                        </p>

                    </div>


                    {{-- INFO --}}

                    <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">

                        <p class="text-sm font-semibold text-yellow-800">
                            Perhatian
                        </p>

                        <p class="mt-1 text-xs leading-5 text-yellow-700">
                            Pastikan kolom Excel sesuai dengan template.
                            NIS dan NISN tidak boleh sama dengan data yang sudah ada.
                        </p>

                    </div>

                </div>


                {{-- FOOTER --}}

                <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4">

                    <button
                        type="button"
                        onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        Import Data
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection