<?php

namespace App\Providers;

use App\Domains\Announcements\Models\Announcement;
use App\Domains\Announcements\Policies\AnnouncementPolicy;
use App\Domains\Roles\Policies\RolePolicy;
use App\Domains\Users\Models\Role;
use App\Domains\Users\Policies\UserPolicy;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Announcement::class => AnnouncementPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::after(static function ($user): bool {
            return $user->user_group === config('spoon.access.superAdmin');
        });
    }
}
