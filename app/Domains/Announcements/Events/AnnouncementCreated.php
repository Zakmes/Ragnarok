<?php

namespace App\Domains\Announcements\Events;

use App\Domains\Announcements\Models\Announcement;
use App\User;

/**
 * Class AnnouncementCreated
 *
 * @package App\Domains\Announcements\Events\AnnouncementCreated
 */
class AnnouncementCreated
{
    public Announcement $announcement;
    public User $user;

    /**
     * AnnouncementCreated constructor.
     *
     * @param  Announcement $announcement The resource entity from the created announcement
     * @param  User         $user         The resource entity from the authenticated user.
     * @return void
     */
    public function __construct(Announcement $announcement, User $user)
    {
        $this->announcement = $announcement;
        $this->user = $user;
    }
}
