<?php

namespace App\Providers;

use App\Domains\Api\Models\PersonalAccessToken;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Spatie\Flash\Flash;

/**
 * Class AppServiceProvider
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Paginator::useBootstrap();
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Flash::levels(['success' => 'alert-success', 'warning' => 'alert-warning', 'error' => 'alert-danger']);
    }

    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
