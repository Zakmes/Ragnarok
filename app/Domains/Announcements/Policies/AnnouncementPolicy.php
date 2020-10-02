<?php

namespace App\Domains\Announcements\Policies;

use App\Domains\Announcements\Models\Announcement;
use App\Domains\Users\Enums\GroupEnum;
use App\User;

/**
 * Class AnnouncementPolicy
 *
 * @package App\Domains\Announcements\Policies
 */
class AnnouncementPolicy
{
    /**
     * Method for determining if the current user can view the announcements overview.
     *
     * @param  User $user The resource entity from the authenticated user.
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return config('spoon.modules.announcements')
            && $user->canAccessKiosk()
            && $user->hasAnyRole([GroupEnum::WEBMASTER, GroupEnum::DEVELOPER]);
    }

    /**
     * Determine whether the user can view the announcement.
     *
     * @param  User         $user         The resource entity from the authenticated user.
     * @param  Announcement $announcement The given resource entity from the announcement.
     * @return bool
     */
    public function view(User $user, Announcement $announcement): bool
    {
        return config('spoon.modules.announcements')
            && $user->canAccessKiosk()
            && $user->hasAnyRole([GroupEnum::WEBMASTER, GroupEnum::DEVELOPER])
            || $user->is($announcement->creator);
    }

    /**
     * Determine whether the authenticated user can create a new announcement or not.
     *
     * @param  User $user The resource entity from the authenticated user.
     * @return bool
     */
    public function create(User $user): bool
    {
        return config('spoon.modules.announcements')
            && $user->canAccessKiosk()
            && $user->hasRole(GroupEnum::WEBMASTER);
    }

    /**
     * Method for determining whether the authenticated user can disable an announcement or not.
     *
     * @param  User         $user         The resource entity from the authenticated user
     * @param  Announcement $announcement The resource entity from the given announcement.
     * @return bool
     */
    public function disableAnnouncement(User $user, Announcement $announcement): bool
    {
        return config('spoon.modules.announcements')
            && $user->canAccessKiosk()
            && $user->hasRole(GroupEnum::WEBMASTER)
            || $user->is($announcement->creator);
    }

    /**
     * Method for determining whether the authenticated user can enable an announcement or not.
     *
     * @param  User         $user         The resource entity from the authenticated user.
     * @param  Announcement $announcement The resource entity from the given announcement
     * @return bool
     */
    public function enableAnnouncement(User $user, Announcement $announcement): bool
    {
        return config('spoon.modules.announcements')
            && $user->canAccessKiosk()
            && $user->hasRole(GroupEnum::WEBMASTER)
            || $user->is($announcement->creator);
    }

    /**
     * Method for determining whether the authenticated user can delete an announcement or not.
     *
     * @param  User         $user         The resource entity from the authenticated user.
     * @param  Announcement $announcement The resource entity from the given announcement
     * @return bool
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        return config('spoon.modules.announcements')
            && $user->canAccessKiosk()
            && $user->hasRole(GroupEnum::WEBMASTER)
            || $user->is($announcement->creator);
    }
}
