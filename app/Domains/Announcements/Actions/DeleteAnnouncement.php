<?php

namespace App\Domains\Announcements\Actions;

use App\Domains\Announcements\Models\Announcement;
use App\Domains\Users\Actions\BaseAction;

/**
 * Class DeleteAnnouncement
 *
 * @package App\Domains\Announcements\Actions
 */
class DeleteAnnouncement extends BaseAction
{
    /**
     * Method for executing the announcement removal in the application resource.
     *
     * @param  Announcement $announcement The given resource entity from the announcement.
     * @return bool
     */
    public function execute(Announcement $announcement): bool
    {
        $user = auth()->user();

        return $this->announcementService->deleteAnnouncement($user, $announcement);
    }
}
