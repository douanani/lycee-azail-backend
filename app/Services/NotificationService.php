<?php


// ============================================================
// app/Services/NotificationService.php
// ============================================================
namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NotificationService
{
    /**
     * Send a notification to all users of specified roles.
     */
    public function notifyRoles(Notification $notification, array $roles): void
    {
        User::whereHas('role', fn($q) => $q->whereIn('name', $roles))
            ->where('is_active', true)
            ->each(fn($user) => $user->notify($notification));
    }

    /**
     * Send a notification to a specific user.
     */
    public function notifyUser(User $user, Notification $notification): void
    {
        $user->notify($notification);
    }

    /**
     * Broadcast to all active users.
     */
    public function notifyAll(Notification $notification): void
    {
        User::where('is_active', true)->each(fn($u) => $u->notify($notification));
    }
}
