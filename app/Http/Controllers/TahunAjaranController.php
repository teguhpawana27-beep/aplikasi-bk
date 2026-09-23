<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TahunAjaranController extends Controller
{
    /**
     * Menampilkan semua tahun ajaran.
     */
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderByDesc('tanggal_mulai')->get();

        return view('tahun-ajaran.index', compact('tahunAjaran'));
    }


    /**
     * Form tambah tahun ajaran.
     */
    public function create()
    {
        return view('tahun-ajaran.create');
    }


    /**
     * Simpan tahun ajaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:20',
                'unique:tahun_ajaran,nama',
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after:tanggal_mulai',
            ],
        ], [
            'nama.required' => 'Nama tahun ajaran wajib diisi.',
            'nama.unique' => 'Tahun ajaran tersebut sudah tersedia.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);


        // Tahun ajaran baru secara default tidak aktif
        $validated['is_active'] = false;

        TahunAjaran::create($validated);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with(
                'success',
                'Tahun ajaran berhasil ditambahkan.'
            );
    }


    /**
     * Form edit tahun ajaran.
     */
    public function edit(TahunAjaran $tahunAjaran)
    {
        return view(
            'tahun-ajaran.edit',
            compact('tahunAjaran')
        );
    }


    /**
     * Update tahun ajaran.
     */
    public function update(
        Request $request,
        TahunAjaran $tahunAjaran
    ) {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:20',
                Rule::unique(
                    'tahun_ajaran',
                    'nama'
                )->ignore($tahunAjaran->id),
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after:tanggal_mulai',
            ],
        ], [
            'nama.required' => 'Nama tahun ajaran wajib diisi.',
            'nama.unique' => 'Tahun ajaran tersebut sudah tersedia.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Jangan mengubah status aktif saat edit
        |--------------------------------------------------------------------------
        |
        | Kalau tahun ajaran ini sedang aktif, tetap aktif.
        | Kalau tidak aktif, tetap tidak aktif.
        |
        */

        $tahunAjaran->update($validated);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with(
                'success',
                'Tahun ajaran berhasil diperbarui.'
            );
    }


    /**
     * Aktifkan tahun ajaran.
     *
     * Fungsi ini digunakan untuk menentukan
     * tahun ajaran aktif/default sekolah.
     */
    public function activate(TahunAjaran $tahunAjaran)
    {
        /*
        |--------------------------------------------------------------------------
        | Hanya satu tahun ajaran yang boleh aktif
        |--------------------------------------------------------------------------
        */

        TahunAjaran::query()
            ->where('id', '!=', $tahunAjaran->id)
            ->update([
                'is_active' => false
            ]);


        /*
        |--------------------------------------------------------------------------
        | Aktifkan tahun ajaran yang dipilih
        |--------------------------------------------------------------------------
        */

        $tahunAjaran->update([
            'is_active' => true
        ]);


        return redirect()
            ->route('tahun-ajaran.index')
            ->with(
                'success',
                'Tahun ajaran ' .
                $tahunAjaran->nama .
                ' berhasil diaktifkan.'
            );
    }


    /**
     * Pilih tahun ajaran dari Dashboard.
     *
     * Fungsi ini TIDAK mengubah database data BK.
     * Hanya menyimpan ID tahun ajaran yang sedang
     * dipilih ke dalam session pengguna.
     */
    public function pilih(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => [
                'required',
                'exists:tahun_ajaran,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Simpan tahun ajaran pilihan ke SESSION
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | 2026/2027 → ID 1
        | 2027/2028 → ID 2
        |
        | Data database tidak diubah.
        |
        */

        session([
            'tahun_ajaran_id' => (int) $validated['tahun_ajaran_id'],
        ]);


        return redirect()
            ->back()
            ->with(
                'success',
                'Tahun ajaran berhasil diganti.'
            );
    }


    /**
     * Hapus tahun ajaran.
     */
    public function destroy(TahunAjaran $tahunAjaran)
    {
        /*
        |--------------------------------------------------------------------------
        | Tahun ajaran aktif tidak boleh dihapus
        |--------------------------------------------------------------------------
        */

        if ($tahunAjaran->is_active) {

            return redirect()
                ->route('tahun-ajaran.index')
                ->with(
                    'error',
                    'Tahun ajaran yang sedang aktif tidak dapat dihapus.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Cek apakah tahun ajaran sudah digunakan oleh kelas
        |--------------------------------------------------------------------------
        */

        $digunakanKelas = \App\Models\Kelas::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAjaran->id
            )
            ->exists();


        if ($digunakanKelas) {

            return redirect()
                ->route('tahun-ajaran.index')
                ->with(
                    'error',
                    'Tahun ajaran tidak dapat dihapus karena sudah digunakan oleh data kelas.'
                );
        }


        $tahunAjaran->delete();

        return redirect()
            ->route('tahun-ajaran.index')
            ->with(
                'success',
                'Tahun ajaran berhasil dihapus.'
            );
    }
}