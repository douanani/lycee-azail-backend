<?php
// ============================================================
// app/Models/Subject.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['name', 'name_fr', 'code', 'icon', 'color'];

    public function gradeLevels(): BelongsToMany
    {
        return $this->belongsToMany(GradeLevel::class, 'grade_level_subject');
    }

    public function resources(): HasMany { return $this->hasMany(Resource::class); }
    public function timetables(): HasMany { return $this->hasMany(Timetable::class); }
}