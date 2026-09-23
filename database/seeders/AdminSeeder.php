<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->firstOrFail();

        User::updateOrCreate(
            [
                'email' => 'admin@bksekolah.test',
            ],
            [
                'role_id' => $adminRole->id,
                'name' => 'Administrator',
                'password' => 'Admin12345',
                'is_active' => true,
            ]
        );
    }
}