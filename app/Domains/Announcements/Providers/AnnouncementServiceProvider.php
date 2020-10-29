<?php

namespace App\Domains\Announcements\Providers;

use App\Domains\Announcements\Services\AnnouncementService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Class AnnouncementServiceProvider
 *
 * @package App\Domains\Announcements\Providers
 */
class AnnouncementServiceProvider extends ServiceProvider
{
    /**
     * Register bindings to the container
     *
     * @param  AnnouncementService $announcementService The business logic layer for the announcements.
     * @return void
     */
    public function boot(AnnouncementService $announcementService): void
    {
        $this->registerViewComposers($announcementService);
        $this->registerBladeComponents();
    }

    /**
     * Method for registering the view composers in the application.
     *
     * @param  AnnouncementService $announcementService
     * @return void
     */
    private function registerViewComposers(AnnouncementService $announcementService): void
    {
        if ($this->announcementModuleEnabled()) {
            View::composer('layouts._announcements', static function ($view) use ($announcementService): void {
                (auth()->check() && auth()->user()->canAccessKiosk())
                    ? $view->with('announcements', $announcementService->getForBackend())
                    : $view->with('announcements', $announcementService->getForFrontend());
            });

            View::composer(['layouts.app', 'layouts.auth'], static function ($view) use ($announcementService): void {
                $view->with('announcementsUnread', $announcementService->getUnreadCount());
            });
        }
    }

    /**
     * Method for reading the config variable and determining is the announcement module is enabled.
     *
     * @return bool
     */
    private function announcementModuleEnabled(): bool
    {
        return config('spoon.modules.announcements');
    }

    /**
     * Method for registering the announcement relation blade components.
     *
     * @return void
     */
    private function registerBladeComponents(): void
    {
        Blade::component('announcements.partials._sidenav', 'announcement-sidenav');
    }
}
