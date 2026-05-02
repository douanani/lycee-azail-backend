<?php

// ============================================================
// database/seeders/AdminSeeder.php
// ============================================================
namespace Database\Seeders;

use App\Models\{Role, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();

        User::updateOrCreate(
            ['email' => 'admin@lycee-azail.dz'],
            [
                'role_id'  => $adminRole->id,
                'name'     => 'مدير النظام',
                'password' => Hash::make('Admin@123456'),
                'is_active'=> true,
            ]
        );
    }
}
