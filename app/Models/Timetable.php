<?php
// ============================================================
// app/Models/Timetable.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timetable extends Model
{
    protected $fillable = [
        'classroom_id', 'academic_year_id', 'subject_id', 'user_id',
        'day', 'start_time', 'end_time', 'room', 'is_break',
    ];

    protected $casts = ['is_break' => 'boolean'];

    public function classroom(): BelongsTo    { return $this->belongsTo(Classroom::class); }
    public function academicYear(): BelongsTo  { return $this->belongsTo(AcademicYear::class); }
    public function subject(): BelongsTo      { return $this->belongsTo(Subject::class); }
    public function teacher(): BelongsTo      { return $this->belongsTo(User::class, 'user_id'); }
}