<?php
// ============================================================
// app/Models/Role.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'label', 'description'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Role name constants
    const ADMIN      = 'admin';
    const TEACHER    = 'teacher';
    const COUNSELOR  = 'counselor';
    const ADMIN_STAFF = 'admin_staff';
    const SUPERVISOR = 'supervisor';
}