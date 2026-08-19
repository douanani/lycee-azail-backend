<?php


// ============================================================
// database/seeders/ExamSeeder.php
// ============================================================
namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\GradeLevel;
use App\Models\Resource;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $year    = AcademicYear::where('is_current', true)->firstOrFail();
        $adminId = 1;

        // من constants.js — EXAMS — type = exam
        // حالياً ما كانش فيها ملفات exam في constants.js
        // هذا seeder جاهز باش تضيف فيه لما تعندهم الملفات
        $data = [
            // مثال للبنية — أضف هنا ملفاتك لما تكون جاهزة:
            /*
            [
                'grade_level_code' => '1SC',
                'subject_code'     => 'MATH',
                'files' => [
                    [
                        'title'     => 'اختبار الفصل الأول رياضيات أولى علوم',
                        'file_path' => 'https://drive.google.com/file/d/XXXX/view',
                        'file_name' => 'اختبار_رياضيات_ف1.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 256000,
                        'semester'  => 'الفصل الأول',
                    ],
                ],
            ],
            */
        ];

        $count = 0;
        $levels   = GradeLevel::all()->keyBy('code');
        $subjects = Subject::all()->keyBy('code');

        foreach ($data as $entry) {
            $level   = $levels->get($entry['grade_level_code']);
            $subject = $subjects->get($entry['subject_code']);

            if (!$level || !$subject) {
                $this->command->warn("⚠️  مستوى أو مادة غير موجودة — تم التخطي.");
                continue;
            }

            foreach ($entry['files'] as $file) {
                Resource::updateOrCreate(
                    [
                        'type'             => 'exam',
                        'title'            => $file['title'],
                        'grade_level_id'   => $level->id,
                        'subject_id'       => $subject->id,
                        'academic_year_id' => $year->id,
                    ],
                    [
                        'user_id'      => $adminId,
                        'description'  => null,
                        'file_path'    => $file['file_path'],
                        'file_name'    => $file['file_name'],
                        'file_type'    => $file['file_type'],
                        'file_size'    => $file['file_size'],
                        'semester'     => $file['semester'],
                        'is_published' => true,
                    ]
                );
                $count++;
                $this->command->line("  ✔  [{$level->code}] {$subject->name} — {$file['title']}");
            }
        }

        $this->command->info("✅ ExamSeeder: تم إضافة {$count} اختبار.");
    }
}