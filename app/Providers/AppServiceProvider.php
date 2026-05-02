<?php

// ============================================================
// app/Providers/AuthServiceProvider.php — Policy registration
// ============================================================
namespace App\Providers;

use App\Models\{Resource, Announcement};
use App\Policies\{ResourcePolicy, AnnouncementPolicy};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Resource::class     => ResourcePolicy::class,
        Announcement::class => AnnouncementPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
