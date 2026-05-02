<?php


// ============================================================
// app/Policies/AnnouncementPolicy.php
// ============================================================
namespace App\Policies;

use App\Models\{Announcement, User};

class AnnouncementPolicy
{
    public function update(User $user, Announcement $announcement): bool
    {
        if ($user->isAdmin()) return true;
        return $announcement->user_id === $user->id;
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        if ($user->isAdmin()) return true;
        return $announcement->user_id === $user->id;
    }
}