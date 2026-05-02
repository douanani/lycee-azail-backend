<?php
// ============================================================
// app/Models/TeacherSubject.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherSubject extends Model
{
    protected $fillable = ['user_id', 'subject_id', 'grade_level_id', 'academic_year_id'];

    public function teacher(): BelongsTo      { return $this->belongsTo(User::class, 'user_id'); }
    public function subject(): BelongsTo      { return $this->belongsTo(Subject::class); }
    public function gradeLevel(): BelongsTo   { return $this->belongsTo(GradeLevel::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
}