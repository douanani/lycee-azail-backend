<?php

// ============================================================
// app/Models/User.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id', 'name', 'email', 'phone', 'password',
        'is_active', 'avatar', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
    ];

    // ── Relationships ──────────────────────────────────────
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class);
    }

    // ── Role helpers ───────────────────────────────────────
    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;
        return in_array($this->role->name, $roles);
    }

    public function isAdmin(): bool      { return $this->hasRole(Role::ADMIN); }
    public function isTeacher(): bool    { return $this->hasRole(Role::TEACHER); }
    public function isCounselor(): bool  { return $this->hasRole(Role::COUNSELOR); }
    public function isAdminStaff(): bool { return $this->hasRole(Role::ADMIN_STAFF); }
    public function isSupervisor(): bool { return $this->hasRole(Role::SUPERVISOR); }
}