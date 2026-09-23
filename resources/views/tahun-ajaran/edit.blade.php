<x-app-layout>

    <div class="mx-auto max-w-3xl space-y-6">

        <div>
            <p class="text-sm font-medium text-slate-400">
                Pengaturan
            </p>

            <h1 class="text-2xl font-bold text-slate-800">
                Edit Tahun Ajaran
            </h1>
        </div>


        @if($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">

                <ul class="list-disc space-y-1 pl-5 text-sm text-red-600">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('tahun-ajaran.update', $tahunAjaran) }}"
            method="POST"
            class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
        >

            @csrf
            @method('PUT')


            {{-- NAMA --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Tahun Ajaran
                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama', $tahunAjaran->nama) }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- TANGGAL MULAI --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    name="tanggal_mulai"
                    value="{{ old('tanggal_mulai', $tahunAjaran->tanggal_mulai?->format('Y-m-d')) }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- TANGGAL SELESAI --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Tanggal Selesai
                </label>

                <input
                    type="date"
                    name="tanggal_selesai"
                    value="{{ old('tanggal_selesai', $tahunAjaran->tanggal_selesai?->format('Y-m-d')) }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- STATUS --}}
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                @if($tahunAjaran->is_active)

                    <div class="text-sm font-semibold text-green-700">
                        ✓ Tahun ajaran ini sedang aktif.
                    </div>

                    <div class="mt-1 text-xs text-slate-500">
                        Untuk mengganti tahun aktif, gunakan tombol
                        <b>Jadikan Aktif</b> pada halaman daftar tahun ajaran.
                    </div>

                @else

                    <div class="text-sm font-semibold text-slate-700">
                        Tahun ajaran ini tidak aktif.
                    </div>

                @endif

            </div>


            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('tahun-ajaran.index') }}"
                    class="rounded-xl bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</x-app-layout>