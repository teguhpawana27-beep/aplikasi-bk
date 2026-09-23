<x-app-layout>

    <div class="mx-auto max-w-3xl space-y-6">

        <div>
            <p class="text-sm font-medium text-slate-400">
                Pengaturan
            </p>

            <h1 class="text-2xl font-bold text-slate-800">
                Tambah Tahun Ajaran
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Tambahkan periode tahun ajaran baru.
            </p>
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
            action="{{ route('tahun-ajaran.store') }}"
            method="POST"
            class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
        >

            @csrf


            {{-- NAMA --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Tahun Ajaran
                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama') }}"
                    placeholder="Contoh: 2027/2028"
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
                    value="{{ old('tanggal_mulai') }}"
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
                    value="{{ old('tanggal_selesai') }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- AKTIF --}}
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                <label class="flex cursor-pointer items-center gap-3">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                    >

                    <span>

                        <span class="block text-sm font-semibold text-slate-700">
                            Jadikan Tahun Ajaran Aktif
                        </span>

                        <span class="block text-xs text-slate-500">
                            Jika dicentang, tahun ajaran aktif sebelumnya akan otomatis menjadi tidak aktif.
                        </span>

                    </span>

                </label>

            </div>


            {{-- BUTTON --}}
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
                    Simpan
                </button>

            </div>

        </form>

    </div>

</x-app-layout>