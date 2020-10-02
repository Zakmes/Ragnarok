<?php

namespace App\Domains\Announcements\Services;

use App\Domains\Announcements\Enums\AnnouncementAreaEnum;
use App\Domains\Announcements\Events\AnnouncementCreated;
use App\Domains\Announcements\Events\AnnouncementDeleted;
use App\Domains\Announcements\Models\Announcement;
use App\Support\Services\BaseService;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class AnnouncementService
 *
 * @package App\Domains\Announcements\Services
 */
class AnnouncementService extends BaseService
{
    /**
     * AnnouncementService constructor.
     *
     * @param Announcement $announcement The database models for the announcements.
     * @return void
     */
    public function __construct(Announcement $announcement)
    {
        $this->model = $announcement;
    }

    /**
     * Method for getting all the announcements for the user overview.
     *
     * @return Collection
     */
    public function getAnnouncementsToDisplay()
    {
        if (auth()->user()->canAccessKiosk()) {
            return $this->getForBackend();
        }

        return $this->getForFrontend();
    }

    /**
     * Method for getting the amount of announcements that the user hasn't read;
     *
     * @return int|null
     */
    public function getUnreadCount(): ?int
    {
        if (auth()->check() && auth()->user()->canAccessKiosk()) {
            return $this->model->inTimeFrame()->unreads()->count();
        }

        return 0;
    }

    /**
     * Method for removing an announcement in the application.
     *
     * @param  User         $user           The resource entity from the authenticated user.
     * @param  Announcement $announcement   The resource entity from the given announcement.
     * @return bool
     */
    public function deleteAnnouncement(User $user, Announcement $announcement): bool
    {
        return DB::transaction(function () use ($user, $announcement): bool {
            $operation = $this->deleteById($announcement->id);
            event(new AnnouncementDeleted($user, $announcement));

            return $operation;
        });
    }

    /**
     * Get all the enabled announcements for the frontend or globally.
     * Where there's either no time frame of
     *
     * if there is a start and end date, make sure the current time is between that of
     * if there is only a start date, make sure the current time is past that or
     * if there is only an end date, make sure the current time is before that.
     *
     * @return Collection
     */
    public function getForFrontend(): Collection
    {
        return $this->model::enabled()
            ->forArea(AnnouncementAreaEnum::FRONTEND)
            ->inTimeFrame()
            ->unreads()
            ->get();
    }

    /**
     * Get all the enabled announcements for the backend or globally
     * Where there's either no time frame or
     *
     * if there is a start and end date, make sure the current time is in between that or
     * if there is only a start date, make sure the current time is past that or
     * if there is only an end date, make sure the current time is before that.
     *
     * @return Collection
     */
    public function getForBackend(): Collection
    {
        return $this->model::enabled()
            ->forArea(AnnouncementAreaEnum::BACKEND)
            ->inTimeFrame()
            ->unreads()
            ->get();
    }

    /**
     * Method for creating a new announcement in the application.
     *
     * @param  array $dataObjectArray The data array from the DTO.
     * @param  Model $creator         The authenticated user that created the announcement.
     * @return Announcement
     */
    public function createAnnouncement(array $dataObjectArray, Model $creator): Announcement
    {
        return DB::transaction(function () use ($dataObjectArray, $creator): Announcement {
            $announcement = $this->model->create($dataObjectArray);
            $announcement->setCreator($creator);

            event(new AnnouncementCreated($announcement, $creator));

            return $announcement;
        });
    }
}
