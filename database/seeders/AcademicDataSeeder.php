<?php

// ============================================================
// database/seeders/AcademicDataSeeder.php
// ============================================================
namespace Database\Seeders;

use App\Models\{AcademicYear, GradeLevel, Subject};
use Illuminate\Database\Seeder;

class AcademicDataSeeder extends Seeder
{
    public function run(): void
    {
        // Academic Year
        $year = AcademicYear::updateOrCreate(
            ['label' => '2025/2026'],
            [
                'start_date' => '2025-09-01',
                'end_date'   => '2026-06-30',
                'is_current' => true,
            ]
        );

        // Grade Levels
        $levels = [
            ['name' => 'السنة أولى جذع مشترك علوم وتكنولوجيا', 'code' => '1SC', 'year_number' => 1, 'stream' => 'sciences'],
            ['name' => 'السنة أولى جذع مشترك آداب',             'code' => '1LA', 'year_number' => 1, 'stream' => 'literature'],
            ['name' => 'السنة الثانية علوم تجريبية',            'code' => '2EX', 'year_number' => 2, 'stream' => 'sciences'],
            ['name' => 'السنة الثانية آداب وفلسفة',             'code' => '2LP', 'year_number' => 2, 'stream' => 'literature'],
            ['name' => 'السنة الثالثة علوم تجريبية',            'code' => '3EX', 'year_number' => 3, 'stream' => 'sciences'],
            ['name' => 'السنة الثالثة آداب و فلسفة',            'code' => '3LP', 'year_number' => 3, 'stream' => 'literature'],
        ];

        foreach ($levels as $level) {
            GradeLevel::updateOrCreate(['code' => $level['code']], $level);
        }

        // Subjects
        $subjects = [
            ['name' => 'رياضيات',         'code' => 'MATH',  'icon' => 'bi-calculator-fill',       'color' => '#4f46e5'],
            ['name' => 'فيزياء',           'code' => 'PHY',   'icon' => 'bi-lightbulb-fill',        'color' => '#06b6d4'],
            ['name' => 'علوم طبيعية',     'code' => 'BIO',   'icon' => 'bi-flower1',               'color' => '#10b981'],
            ['name' => 'لغة عربية',       'code' => 'ARA',   'icon' => 'bi-chat-right-text-fill',  'color' => '#f59e0b'],
            ['name' => 'فلسفة',           'code' => 'PHI',   'icon' => 'bi-clipboard2',            'color' => '#1f2937'],
            ['name' => 'تاريخ وجغرافيا', 'code' => 'HGO',   'icon' => 'bi-globe2',               'color' => '#ef4444'],
            ['name' => 'اللغة الفرنسية', 'code' => 'FRA',   'icon' => 'bi-translate',             'color' => '#3b82f6'],
            ['name' => 'اللغة الإنجليزية','code' => 'ENG',  'icon' => 'bi-translate',             'color' => '#06b6d4'],
            ['name' => 'علوم إسلامية',   'code' => 'ISL',   'icon' => 'bi-book',                  'color' => '#10b981'],
            ['name' => 'المعلوماتية',     'code' => 'INFO',  'icon' => 'bi-cpu',                   'color' => '#1f2937'],
            ['name' => 'تكنولوجيا',       'code' => 'TECH',  'icon' => 'bi-gear-fill',             'color' => '#6b7280'],
            ['name' => 'التربية البدنية', 'code' => 'PHYS',  'icon' => 'bi-dribbble',              'color' => '#ef4444'],
            ['name' => 'التربية الفنية',  'code' => 'ART',   'icon' => 'bi-palette-fill',          'color' => '#f59e0b'],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(['code' => $subject['code']], $subject);
        }
    }
}