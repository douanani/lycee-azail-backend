<?php

namespace Database\Seeders;

use App\Models\GradeLevel;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class GradeLevelSubjectSeeder extends Seeder
{
    public function run(): void
    {
        // الخريطة: code المستوى => أكواد المواد
        $map = [
            '1SC' => ['ARA', 'MATH', 'PHY', 'BIO', 'ISL', 'HGO', 'FRA', 'ENG', 'INFO', 'TECH', 'ART'],
            '1LA' => ['ARA', 'MATH', 'PHY', 'BIO', 'ISL', 'HGO', 'FRA', 'ENG', 'INFO', 'ART'],
            '2EX' => ['ARA', 'MATH', 'PHY', 'BIO', 'ISL', 'HGO', 'FRA', 'ENG', 'ART'],
            '2LP' => ['ARA', 'PHI', 'MATH', 'PHY', 'BIO', 'ISL', 'HGO', 'FRA', 'ENG', 'ART'],
            '3EX' => ['ARA', 'MATH', 'PHY', 'BIO', 'ISL', 'HGO', 'FRA', 'ENG'],
            '3LP' => ['ARA', 'PHI', 'MATH', 'ISL', 'HGO', 'FRA', 'ENG'],
        ];

        $levels   = GradeLevel::whereIn('code', array_keys($map))->get()->keyBy('code');
        $subjects = Subject::whereIn('code', array_unique(array_merge(...array_values($map))))->get()->keyBy('code');

        $attached = 0;
        $skipped  = 0;

        foreach ($map as $levelCode => $subjectCodes) {
            $level = $levels->get($levelCode);

            if (!$level) {
                $this->command->warn("⚠️  المستوى [{$levelCode}] غير موجود — تم التخطي.");
                $skipped++;
                continue;
            }

            $ids = [];
            foreach ($subjectCodes as $subjectCode) {
                $subject = $subjects->get($subjectCode);
                if (!$subject) {
                    $this->command->warn("⚠️  المادة [{$subjectCode}] غير موجودة — تم التخطي.");
                    $skipped++;
                    continue;
                }
                $ids[] = $subject->id;
            }

            // syncWithoutDetaching حتى لا يحذف روابط موجودة مسبقاً
            $level->subjects()->syncWithoutDetaching($ids);
            $attached += count($ids);

            $this->command->line("  ✔  {$level->name} ← " . count($ids) . " مادة");
        }

        $this->command->info("✅ تم ربط {$attached} مادة بالمستويات. تخطي: {$skipped}.");
    }
}
