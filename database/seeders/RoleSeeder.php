<?php

// ============================================================
// database/seeders/RoleSeeder.php
// ============================================================
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin',       'label' => 'مدير النظام',           'description' => 'Full system access'],
            ['name' => 'teacher',     'label' => 'أستاذ',                 'description' => 'Upload and manage own lessons/exams'],
            ['name' => 'counselor',   'label' => 'مستشار التوجيه',        'description' => 'Publish guidance announcements'],
            ['name' => 'admin_staff', 'label' => 'الإدارة',               'description' => 'Publish administrative announcements'],
            ['name' => 'supervisor',  'label' => 'مستشار التربية',        'description' => 'Manage timetables and student notifications'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}