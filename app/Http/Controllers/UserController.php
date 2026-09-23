<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\GuruBK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * =========================================================
     * DAFTAR PENGGUNA
     * =========================================================
     */
    public function index()
    {
        $users = User::with('role')
            ->orderBy('name')
            ->get();

        return view('users.index', compact('users'));
    }


    /**
     * =========================================================
     * FORM TAMBAH
     * =========================================================
     */
    public function create()
    {
        $roles = Role::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('users.create', compact('roles'));
    }


    /**
     * =========================================================
     * SIMPAN PENGGUNA
     * =========================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role_id' => [
                'required',
                Rule::exists('roles', 'id')
                    ->where('is_active', true),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan.',

            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists' => 'Role yang dipilih tidak valid.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);


        DB::transaction(function () use ($validated) {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'is_active' => $validated['is_active'],
            ]);


            /*
             * =====================================================
             * JIKA ROLE GURU BK
             * otomatis buat profil Guru BK
             * =====================================================
             */
            $role = Role::find($validated['role_id']);

            if ($role && $role->name === 'Guru BK') {

                GuruBK::firstOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'nama_lengkap' => $user->name,
                        'status' => $user->is_active
                            ? 'aktif'
                            : 'nonaktif',
                    ]
                );
            }
        });


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Pengguna berhasil ditambahkan.'
            );
    }


    /**
     * =========================================================
     * FORM EDIT
     * =========================================================
     */
    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)
            ->orderBy('name')
            ->get();

        $user->load('role');

        return view(
            'users.edit',
            compact('user', 'roles')
        );
    }


    /**
     * =========================================================
     * UPDATE PENGGUNA
     * =========================================================
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'role_id' => [
                'required',
                Rule::exists('roles', 'id')
                    ->where('is_active', true),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan.',

            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists' => 'Role yang dipilih tidak valid.',

            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);


        /*
         * =====================================================
         * Jangan sampai Admin menonaktifkan dirinya sendiri
         * =====================================================
         */
        if (
            $user->id === auth()->id()
            && ! $validated['is_active']
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Akun yang sedang digunakan tidak dapat dinonaktifkan.'
                );
        }


        DB::transaction(function () use (
            $validated,
            $user
        ) {

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role_id = $validated['role_id'];
            $user->is_active = $validated['is_active'];

            if (! empty($validated['password'])) {
                $user->password = Hash::make(
                    $validated['password']
                );
            }

            $user->save();


            /*
             * =====================================================
             * SINKRONISASI PROFIL GURU BK
             * =====================================================
             */
            $role = Role::find($validated['role_id']);

            if ($role && $role->name === 'Guru BK') {

                $guruBK = GuruBK::firstOrNew([
                    'user_id' => $user->id,
                ]);

                $guruBK->nama_lengkap = $user->name;
                $guruBK->status = $user->is_active
                    ? 'aktif'
                    : 'nonaktif';

                $guruBK->save();

            } else {

                /*
                 * Jika sebelumnya Guru BK lalu role diganti,
                 * profil tidak dihapus.
                 * Hanya dibuat nonaktif agar histori tetap aman.
                 */
                GuruBK::where(
                    'user_id',
                    $user->id
                )->update([
                    'status' => 'nonaktif',
                ]);
            }
        });


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Data pengguna berhasil diperbarui.'
            );
    }


    /**
     * =========================================================
     * HAPUS PENGGUNA
     * =========================================================
     */
    public function destroy(User $user)
    {
        /*
         * Jangan boleh menghapus akun sendiri
         */
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'Akun yang sedang digunakan tidak dapat dihapus.'
                );
        }


        /*
         * Hapus user.
         *
         * Relasi guru_bk menggunakan cascadeOnDelete(),
         * sehingga profil Guru BK terkait ikut terhapus.
         */
        $user->delete();


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Pengguna berhasil dihapus.'
            );
    }
}