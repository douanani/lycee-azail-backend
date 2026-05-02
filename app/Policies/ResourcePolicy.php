<?php

// ============================================================
// app/Policies/ResourcePolicy.php
// ============================================================
namespace App\Policies;

use App\Models\{Resource, User};

class ResourcePolicy
{
    /**
     * Admins can do anything.
     * Teachers/others can only update/delete their own resources.
     */
    public function update(User $user, Resource $resource): bool
    {
        if ($user->isAdmin()) return true;
        return $resource->user_id === $user->id;
    }

    public function delete(User $user, Resource $resource): bool
    {
        if ($user->isAdmin()) return true;
        return $resource->user_id === $user->id;
    }
}