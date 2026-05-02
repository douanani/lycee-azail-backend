<?php
// ============================================================
// app/Models/GradeLevel.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeLevel extends Model
{
    protected $fillable = ['name', 'code', 'year_number', 'stream'];

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'grade_level_subject');
    }

    public function resources(): HasMany { return $this->hasMany(Resource::class); }
    public function classrooms(): HasMany { return $this->hasMany(Classroom::class); }
}
