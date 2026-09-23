<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'Admin',
            'description' => 'Mengelola seluruh data dan sistem aplikasi BK.',
            'is_active' => true,
        ]);

        Role::create([
            'name' => 'Guru BK',
            'description' => 'Mengelola layanan dan data bimbingan konseling.',
            'is_active' => true,
        ]);

        Role::create([
            'name' => 'Kepala Sekolah',
            'description' => 'Melihat monitoring dan laporan kegiatan BK.',
            'is_active' => true,
        ]);
    }
}