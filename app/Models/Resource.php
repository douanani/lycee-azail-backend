<?php
// ============================================================
// app/Models/Resource.php
// (Unified model for lessons, exams, homework, guides)
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'academic_year_id', 'grade_level_id', 'subject_id',
        'type', 'title', 'description', 'file_path', 'file_name',
        'file_type', 'file_size', 'semester', 'is_published', 'download_count',
    ];

    protected $casts = [
        'is_published'   => 'boolean',
        'download_count' => 'integer',
        'file_size'      => 'integer',
    ];

    // ── Type constants ─────────────────────────────────────
    const TYPE_LESSON   = 'lesson';
    const TYPE_EXAM     = 'exam';
    const TYPE_HOMEWORK = 'homework';
    const TYPE_GUIDE    = 'guide';

    // ── Relationships ──────────────────────────────────────
    public function uploader(): BelongsTo    { return $this->belongsTo(User::class, 'user_id'); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function gradeLevel(): BelongsTo  { return $this->belongsTo(GradeLevel::class); }
    public function subject(): BelongsTo     { return $this->belongsTo(Subject::class); }

    // ── Scopes ─────────────────────────────────────────────
    public function scopePublished($query)  { return $query->where('is_published', true); }
    public function scopeType($query, string $type) { return $query->where('type', $type); }
    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where('title', 'like', "%{$term}%")
                     ->orWhere('description', 'like', "%{$term}%");
    }

    // ── Accessors ──────────────────────────────────────────
    public function getFileUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function incrementDownload(): void
    {
        $this->increment('download_count');
    }
}