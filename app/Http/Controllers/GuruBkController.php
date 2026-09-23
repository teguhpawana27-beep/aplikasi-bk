<?php

namespace App\Http\Controllers;

use App\Models\GuruBK;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GuruBKController extends Controller
{
    /**
     * Menampilkan daftar Guru BK.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $guruBK = GuruBK::query()
            ->with('user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%")
                        ->orWhere('jabatan', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama_lengkap')
            ->paginate(10)
            ->withQueryString();

        return view('guru-bk.index', compact(
            'guruBK',
            'search'
        ));
    }

    /**
     * Menampilkan form tambah Guru BK.
     */
    public function create()
    {
        return view('guru-bk.create');
    }

    /**
     * Menyimpan Guru BK baru sekaligus akun login.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'nip' => [
                'nullable',
                'string',
                'max:100',
                'unique:guru_bk,nip',
            ],

            'no_hp' => [
                'nullable',
                'string',
                'max:30',
            ],

            'jabatan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::in([
                    'aktif',
                    'nonaktif',
                ]),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'nama_lengkap.required' =>
                'Nama lengkap Guru BK wajib diisi.',

            'nip.unique' =>
                'NIP tersebut sudah digunakan.',

            'email.required' =>
                'Email akun wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email tersebut sudah digunakan.',

            'password.min' =>
                'Password minimal terdiri dari 8 karakter.',

            'password.confirmed' =>
                'Konfirmasi password tidak sesuai.',
        ]);

        DB::beginTransaction();

        try {
            $roleGuruBK = Role::query()
                ->where('name', 'Guru BK')
                ->first();

            if (!$roleGuruBK) {
                DB::rollBack();

                return back()
                    ->withInput()
                    ->withErrors([
                        'email' =>
                            'Role Guru BK belum tersedia di database.',
                    ]);
            }

            $user = User::create([
                'role_id' => $roleGuruBK->id,
                'name' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => Hash::make(
                    $validated['password']
                ),
                'is_active' => true,
            ]);

            GuruBK::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?? null,
                'nama_lengkap' => $validated['nama_lengkap'],
                'no_hp' => $validated['no_hp'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'status' => $validated['status'],
            ]);

            DB::commit();

            return redirect()
                ->route('guru-bk.index')
                ->with(
                    'success',
                    'Data Guru BK dan akun login berhasil ditambahkan.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                        'Data Guru BK gagal disimpan. Silakan coba lagi.',
                ]);
        }
    }

    /**
     * Menampilkan detail Guru BK.
     */
    public function show(GuruBK $guruBK)
    {
        $guruBK->load([
            'user',
            'penugasanBK',
            'layananDasar',
            'layananResponsif',
            'dukunganSistem',
        ]);

        return view('guru-bk.show', compact(
            'guruBK'
        ));
    }

    /**
     * Menampilkan form edit Guru BK.
     */
    public function edit(GuruBK $guruBK)
    {
        $guruBK->load('user');

        return view('guru-bk.edit', compact(
            'guruBK'
        ));
    }

    /**
     * Memperbarui data Guru BK dan akun pengguna.
     */
    public function update(
        Request $request,
        GuruBK $guruBK
    ) {
        $guruBK->load('user');

        $validated = $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'nip' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('guru_bk', 'nip')
                    ->ignore($guruBK->id),
            ],

            'no_hp' => [
                'nullable',
                'string',
                'max:30',
            ],

            'jabatan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::in([
                    'aktif',
                    'nonaktif',
                ]),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($guruBK->user_id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'nama_lengkap.required' =>
                'Nama lengkap Guru BK wajib diisi.',

            'nip.unique' =>
                'NIP tersebut sudah digunakan.',

            'email.unique' =>
                'Email tersebut sudah digunakan.',

            'password.min' =>
                'Password minimal terdiri dari 8 karakter.',

            'password.confirmed' =>
                'Konfirmasi password tidak sesuai.',
        ]);

        DB::beginTransaction();

        try {
            $guruBK->update([
                'nip' => $validated['nip'] ?? null,
                'nama_lengkap' => $validated['nama_lengkap'],
                'no_hp' => $validated['no_hp'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'status' => $validated['status'],
            ]);

            if ($guruBK->user) {
                $userData = [
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'is_active' => $validated['status'] === 'aktif',
                ];

                if (
                    !empty($validated['password'])
                ) {
                    $userData['password'] = Hash::make(
                        $validated['password']
                    );
                }

                $guruBK->user->update($userData);
            }

            DB::commit();

            return redirect()
                ->route('guru-bk.index')
                ->with(
                    'success',
                    'Data Guru BK berhasil diperbarui.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                        'Data Guru BK gagal diperbarui.',
                ]);
        }
    }

    /**
     * Menghapus data Guru BK.
     *
     * Akun pengguna tidak langsung dihapus agar
     * riwayat aktivitas dan relasi pengguna tetap aman.
     */
    public function destroy(GuruBK $guruBK)
    {
        DB::beginTransaction();

        try {
            $user = $guruBK->user;

            $guruBK->delete();

            if ($user) {
                $user->update([
                    'is_active' => false,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('guru-bk.index')
                ->with(
                    'success',
                    'Data Guru BK berhasil dihapus dan akun dinonaktifkan.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withErrors([
                    'error' =>
                        'Data Guru BK gagal dihapus.',
                ]);
        }
    }
}