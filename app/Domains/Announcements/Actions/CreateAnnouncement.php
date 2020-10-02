<?php

namespace App\Domains\Announcements\Actions;

use App\Domains\Announcements\Models\Announcement;
use App\Domains\Users\Actions\BaseAction;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class CreateAnnouncement
 *
 * @package App\Domains\Announcements\Actions
 */
class CreateAnnouncement extends BaseAction
{
    /**
     * Method for creating an announcement in the application.
     *
     * @param  DataTransferObject $dataTransferObject The mapped array of data that needs to be stored.
     * @param  Model              $creator            The authenticated user that created the announcement.
     * @return Announcement
     */
    public function execute(DataTransferObject $dataTransferObject, Model $creator): Announcement
    {
        return $this->announcementService->createAnnouncement($dataTransferObject->toArray(), $creator);
    }
}
