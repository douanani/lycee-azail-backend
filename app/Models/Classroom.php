<?php
// ============================================================
// app/Models/Classroom.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = ['grade_level_id', 'academic_year_id', 'name', 'capacity'];

    public function gradeLevel(): BelongsTo  { return $this->belongsTo(GradeLevel::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function timetables(): HasMany    { return $this->hasMany(Timetable::class); }
}