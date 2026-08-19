<?php

// ============================================================
// database/seeders/LessonSeeder.php
// ============================================================
namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\GradeLevel;
use App\Models\Resource;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $year    = AcademicYear::where('is_current', true)->firstOrFail();
        $adminId = 1;

        // البيانات مأخوذة من constants.js — LESSONS
        $data = [
            [
                'grade_level_code' => '1SC',
                'subject_code'     => 'INFO',
                'files' => [
                    [
                        'title'     => 'درس التعليمة الشرطية',
                        'file_path' => 'https://drive.google.com/file/d/16Dumx_OAbeDZ_w1glIvQzHolHIOPoRtq/view?usp=drive_link',
                        'file_name' => 'درس_التعليمة_الشرطية.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 139264,   // 136 KB
                        'semester'  => 'الفصل الأول',
                    ],
                    [
                        'title'     => 'درس التعليمة التكرارية',
                        'file_path' => 'https://drive.google.com/file/d/1S4nk6dOQH7W2hG3z7BzlzVfSkowbvJGX/view?usp=drive_link',
                        'file_name' => 'درس_التعليمة_التكرارية.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 135168,   // 132 KB
                        'semester'  => 'الفصل الأول',
                    ],
                    [
                        'title'     => 'درس المتصفح',
                        'file_path' => 'https://drive.google.com/file/d/1bn8SZ88T-hjDUqZemtsBs6xI4a-7gcUr/view?usp=drive_link',
                        'file_name' => 'درس_المتصفح.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 295936,   // 289 KB
                        'semester'  => 'الفصل الثاني',
                    ],
                    [
                        'title'     => 'درس هيكل صفحة الويب',
                        'file_path' => 'https://drive.google.com/file/d/1dA9MdZhqBfnBDuQK2_OX46O8yADtJCNM/view?usp=drive_link',
                        'file_name' => 'درس_هيكل_صفحة_الويب.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 157696,   // 154 KB
                        'semester'  => 'الفصل الثاني',
                    ],
                    [
                        'title'     => 'درس الفقرات والعناوين وتنسيقات النص',
                        'file_path' => 'https://drive.google.com/file/d/1tf6IgEJiDBQHQyl7eNN15F_vaaI29zVI/view?usp=sharing',
                        'file_name' => 'درس_الفقرات_والعناوين_وتنسيقات_النص.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 92160,    // 90 KB
                        'semester'  => 'الفصل الثاني',
                    ],
                    [
                        'title'     => 'ملخص الوسوم الأساسية في لغة HTML',
                        'file_path' => 'https://drive.google.com/file/d/15coF9v_uMoF-gLrJmad-Pi81o_Nd9VKg/view?usp=drive_link',
                        'file_name' => 'ملخص_وسوم_HTML.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 1433600,  // 1.4 MB
                        'semester'  => 'الفصل الثاني',
                    ],
                    [
                        'title'     => 'تمارين حول لغة HTML',
                        'file_path' => 'https://drive.google.com/file/d/1GZR3h56yYpkAC6cOw2et53_M38at6D0r/view?usp=drive_link',
                        'file_name' => 'تمارين_HTML.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 107520,   // 105 KB
                        'semester'  => 'الفصل الثاني',
                    ],
                ],
            ],
            [
                'grade_level_code' => '3EX',
                'subject_code'     => 'HGO',
                'files' => [
                    [
                        'title'     => 'شخصيات على شكل مخطط',
                        'file_path' => 'https://drive.google.com/file/d/1Qj9pD2tE8r_Zib2-ithaLa1yUcseFgaC/view?usp=drive_link',
                        'file_name' => 'شخصيات_مخطط.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 10174464, // 9.7 MB
                        'semester'  => 'الفصل الأول',
                    ],
                    [
                        'title'     => 'شخصيات ومصطلحات التاريخ الفصل الأول',
                        'file_path' => 'https://drive.google.com/file/d/1JoaAP_Tp0vgYUqP1i7Bohz_lVovwtTOz/view?usp=drive_link',
                        'file_name' => 'شخصيات_ومصطلحات_التاريخ_ف1.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 34951987, // 33.33 MB
                        'semester'  => 'الفصل الأول',
                    ],
                    [
                        'title'     => 'مصطلحات الجغرافيا الفصل الأول بكالوريا',
                        'file_path' => 'https://drive.google.com/file/d/1MKWAKKeKMSSVM9jTrrVc0-VLL57oREtY/view?usp=drive_link',
                        'file_name' => 'مصطلحات_الجغرافيا_ف1.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 4505600,  // 4.3 MB
                        'semester'  => 'الفصل الأول',
                    ],
                    [
                        'title'     => 'خرائط رقم 1',
                        'file_path' => 'https://drive.google.com/file/d/1ImKfZBNhimvMwdKTEgKfKKP19mVWU0pG/view?usp=sharing',
                        'file_name' => 'خرائط_1.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 113246208, // 108 MB
                        'semester'  => 'الفصل الأول',
                    ],
                    [
                        'title'     => 'أسئلة استنتاجية تاريخ',
                        'file_path' => 'https://drive.google.com/file/d/1H5wjFHIjGYEZj2TaG2DsiqkUub0REmN_/view?usp=drive_link',
                        'file_name' => 'أسئلة_استنتاجية_تاريخ.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 5558067,  // 5.3 MB
                        'semester'  => 'الفصل الثاني',
                    ],
                    [
                        'title'     => 'مصطلحات الفصول الثلاثة المهمة',
                        'file_path' => 'https://drive.google.com/file/d/1AsykS724bUXyN0r9tAjg7sMrpgPZktnZ/view?usp=drive_link',
                        'file_name' => 'مصطلحات_الفصول_الثلاثة.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 9228083,  // 8.8 MB
                        'semester'  => 'الفصل الثالث',
                    ],
                    [
                        'title'     => 'مصطلحات الفصل الثالث تاريخ وجغرافيا',
                        'file_path' => 'https://drive.google.com/file/d/1pD-mZEvQsVxEJ8ylX0tt8InBftT6T2I4/view?usp=drive_link',
                        'file_name' => 'مصطلحات_الفصل_الثالث.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 6396313,  // 6.1 MB
                        'semester'  => 'الفصل الثالث',
                    ],
                ],
            ],
            [
                'grade_level_code' => '3LP',
                'subject_code'     => 'HGO',
                'files' => [
                    // نفس ملفات تاريخ وجغرافيا — مشتركة مع 3EX
                    [
                        'title'     => 'شخصيات على شكل مخطط',
                        'file_path' => 'https://drive.google.com/file/d/1Qj9pD2tE8r_Zib2-ithaLa1yUcseFgaC/view?usp=drive_link',
                        'file_name' => 'شخصيات_مخطط.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 10174464,
                        'semester'  => 'الفصل الأول',
                    ],
                    [
                        'title'     => 'شخصيات ومصطلحات التاريخ الفصل الأول',
                        'file_path' => 'https://drive.google.com/file/d/1JoaAP_Tp0vgYUqP1i7Bohz_lVovwtTOz/view?usp=drive_link',
                        'file_name' => 'شخصيات_ومصطلحات_التاريخ_ف1.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 34951987,
                        'semester'  => 'الفصل الأول',
                    ],
                    [
                        'title'     => 'مصطلحات الجغرافيا الفصل الأول بكالوريا',
                        'file_path' => 'https://drive.google.com/file/d/1MKWAKKeKMSSVM9jTrrVc0-VLL57oREtY/view?usp=drive_link',
                        'file_name' => 'مصطلحات_الجغرافيا_ف1.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 4505600,
                        'semester'  => 'الفصل الأول',
                    ],
                    [
                        'title'     => 'خرائط رقم 1',
                        'file_path' => 'https://drive.google.com/file/d/1ImKfZBNhimvMwdKTEgKfKKP19mVWU0pG/view?usp=sharing',
                        'file_name' => 'خرائط_1.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 113246208,
                        'semester'  => 'الفصل الأول',
                    ],
                    [
                        'title'     => 'أسئلة استنتاجية تاريخ',
                        'file_path' => 'https://drive.google.com/file/d/1H5wjFHIjGYEZj2TaG2DsiqkUub0REmN_/view?usp=drive_link',
                        'file_name' => 'أسئلة_استنتاجية_تاريخ.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 5558067,
                        'semester'  => 'الفصل الثاني',
                    ],
                    [
                        'title'     => 'مصطلحات الفصول الثلاثة المهمة',
                        'file_path' => 'https://drive.google.com/file/d/1AsykS724bUXyN0r9tAjg7sMrpgPZktnZ/view?usp=drive_link',
                        'file_name' => 'مصطلحات_الفصول_الثلاثة.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 9228083,
                        'semester'  => 'الفصل الثالث',
                    ],
                    [
                        'title'     => 'مصطلحات الفصل الثالث تاريخ وجغرافيا',
                        'file_path' => 'https://drive.google.com/file/d/1pD-mZEvQsVxEJ8ylX0tt8InBftT6T2I4/view?usp=drive_link',
                        'file_name' => 'مصطلحات_الفصل_الثالث.pdf',
                        'file_type' => 'pdf',
                        'file_size' => 6396313,
                        'semester'  => 'الفصل الثالث',
                    ],
                ],
            ],
        ];

        $this->seedResources($data, 'lesson', $year->id, $adminId);
    }

    private function seedResources(array $data, string $type, int $yearId, int $userId): void
    {
        $levels   = GradeLevel::all()->keyBy('code');
        $subjects = Subject::all()->keyBy('code');
        $count    = 0;

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
                        'type'             => $type,
                        'title'            => $file['title'],
                        'grade_level_id'   => $level->id,
                        'subject_id'       => $subject->id,
                        'academic_year_id' => $yearId,
                    ],
                    [
                        'user_id'      => $userId,
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

        $this->command->info("✅ LessonSeeder: تم إضافة {$count} درس.");
    }
}