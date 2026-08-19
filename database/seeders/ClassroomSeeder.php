<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\GradeLevel;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::where('is_current', true)->firstOrFail();

        // نجيب كل المستويات بالـ code ديالهم
        $levels = GradeLevel::whereIn('code', ['1SC', '1LA', '2EX', '2LP', '3EX', '3LP'])
            ->get()
            ->keyBy('code');

        $classrooms = [
            // قسمين — السنة الأولى جذع مشترك علوم وتكنولوجيا
            ['grade_level_code' => '1SC', 'name' => '1 ع ت 1', 'capacity' => 30],
            ['grade_level_code' => '1SC', 'name' => '1 ع ت 2', 'capacity' => 30],

            // قسم واحد — السنة الأولى جذع مشترك آداب
            ['grade_level_code' => '1LA', 'name' => '1 أد 1', 'capacity' => 30],

            // قسمين — السنة الثانية علوم تجريبية
            ['grade_level_code' => '2EX', 'name' => '2 ع ت 1', 'capacity' => 30],
            ['grade_level_code' => '2EX', 'name' => '2 ع ت 2', 'capacity' => 30],

            // قسم واحد — السنة الثانية آداب وفلسفة
            ['grade_level_code' => '2LP', 'name' => '2 أد 1', 'capacity' => 30],

            // قسم واحد — السنة الثالثة علوم تجريبية
            ['grade_level_code' => '3EX', 'name' => '3 ع ت 1', 'capacity' => 30],

            // قسم واحد — السنة الثالثة آداب وفلسفة
            ['grade_level_code' => '3LP', 'name' => '3 أد 1', 'capacity' => 30],
        ];

        foreach ($classrooms as $data) {
            $level = $levels->get($data['grade_level_code']);

            if (!$level) {
                $this->command->warn("المستوى {$data['grade_level_code']} غير موجود — تم التخطي.");
                continue;
            }

            Classroom::updateOrCreate(
                [
                    'grade_level_id'   => $level->id,
                    'academic_year_id' => $year->id,
                    'name'             => $data['name'],
                ],
                [
                    'capacity' => $data['capacity'],
                ]
            );
        }

        $this->command->info('✅ تم إضافة ' . count($classrooms) . ' قسم بنجاح.');
    }
}
