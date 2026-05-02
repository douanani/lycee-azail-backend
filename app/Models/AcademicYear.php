<?php
// ============================================================
// app/Models/AcademicYear.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    protected $fillable = ['label', 'start_date', 'end_date', 'is_current'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_current' => 'boolean',
    ];

    public function resources(): HasMany { return $this->hasMany(Resource::class); }
    public function classrooms(): HasMany { return $this->hasMany(Classroom::class); }
    public function timetables(): HasMany { return $this->hasMany(Timetable::class); }

    /**
     * Set this year as current, unset all others.
     */
    public function setCurrent(): void
    {
        static::query()->update(['is_current' => false]);
        $this->update(['is_current' => true]);
    }

    public static function current(): ?static
    {
        return static::where('is_current', true)->first();
    }
}