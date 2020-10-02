<?php

namespace App\Domains\Announcements\Events;

use App\Domains\Announcements\Models\Announcement;
use App\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class StatusChanged
 *
 * @package App\Domains\Announcements\Events
 */
class StatusChanged
{
    use SerializesModels;

    public User $user;
    public Announcement $announcement;

    /**
     * StatusChanged constructor.
     *
     * @param  User         $user         The resource entity from the authenticated user
     * @param  Announcement $announcement The resource entity from the given announcement.
     * @return void
     */
    public function __construct(User $user, Announcement $announcement)
    {
        $this->user = $user;
        $this->announcement = $announcement;
    }
}
