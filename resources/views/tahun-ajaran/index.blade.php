<x-app-layout>

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <p class="text-sm font-medium text-slate-400">
                    Pengaturan
                </p>

                <h1 class="text-2xl font-bold text-slate-800">
                    Tahun Ajaran
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola tahun ajaran sistem BK tanpa menghapus histori data.
                </p>
            </div>

            <a
                href="{{ route('tahun-ajaran.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
            >
                + Tambah Tahun Ajaran
            </a>

        </div>


        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif


        {{-- ERROR --}}
        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif


        {{-- TABLE --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="bg-slate-50">
                        <tr>

                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                No
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                Tahun Ajaran
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                Periode
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Aksi
                            </th>

                        </tr>
                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($tahunAjaran as $index => $tahun)

                            <tr class="transition hover:bg-slate-50">

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $index + 1 }}
                                </td>


                                <td class="px-6 py-4">

                                    <div class="font-semibold text-slate-800">
                                        {{ $tahun->nama }}
                                    </div>

                                </td>


                                <td class="px-6 py-4 text-sm text-slate-600">

                                    {{ $tahun->tanggal_mulai?->format('d/m/Y') }}
                                    -
                                    {{ $tahun->tanggal_selesai?->format('d/m/Y') }}

                                </td>


                                <td class="px-6 py-4">

                                    @if($tahun->is_active)

                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Aktif
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                            Tidak Aktif
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        @if(!$tahun->is_active)

                                            <form
                                                action="{{ route('tahun-ajaran.activate', $tahun) }}"
                                                method="POST"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Jadikan {{ $tahun->nama }} sebagai tahun ajaran aktif?')"
                                                    class="rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white hover:bg-green-700"
                                                >
                                                    Jadikan Aktif
                                                </button>

                                            </form>

                                        @endif


                                        <a
                                            href="{{ route('tahun-ajaran.edit', $tahun) }}"
                                            class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                                        >
                                            Edit
                                        </a>


                                        @if(!$tahun->is_active && !$tahun->kelas()->exists())

                                            <form
                                                action="{{ route('tahun-ajaran.destroy', $tahun) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Yakin ingin menghapus tahun ajaran {{ $tahun->nama }}?')"
                                                    class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100"
                                                >
                                                    Hapus
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="px-6 py-12 text-center"
                                >

                                    <div class="text-sm text-slate-500">
                                        Belum ada data tahun ajaran.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>