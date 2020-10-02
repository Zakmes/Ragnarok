<?php

namespace App\Domains\Announcements\Listeners;

use App\Domains\Announcements\Events\AnnouncementCreated;
use App\Domains\Announcements\Events\AnnouncementDeleted;
use App\Domains\Announcements\Events\StatusChanged;
use Illuminate\Events\Dispatcher;

/**
 * Class AnnouncementListener
 *
 * @package App\Domains\Announcements\Listeners
 */
class AnnouncementListener
{
    /**
     * The log name for announcement related log messages.
     *
     * @var string
     */
    public const LogName = 'Announcements';

    /**
     * Register the listeners for the event subscriber.
     *
     * @param  Dispatcher $events The event dispatching class.
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(StatusChanged::class, [AnnouncementListener::class, 'onStatusChanged']);
        $events->listen(AnnouncementDeleted::class, [AnnouncementListener::class, 'onDeleted']);
        $events->listen(AnnouncementCreated::class, [AnnouncementListener::class, 'onCreated']);
    }

    /**
     * The event listener for the event that gets fired when a status from an announcement is changed.
     *
     * @param  StatusChanged $event The event that gets fired in the service layer.
     * @return void
     */
    public function onStatusChanged(StatusChanged $event): void
    {
        switch ($event->announcement->enabled) {
            case true:  $message = __('Has enabled an announcement in the application.'); break;
            case false: $message = __('Has disabled an announcement in the application'); break;
        }

        activity(self::LogName)
            ->performedOn($event->announcement)
            ->log($message);
    }

    /**
     * The event listener for the event that gets fired when an announcement is deleted.
     *
     * @param  AnnouncementDeleted $event The event that gets fired in the service layer.
     * @return void
     */
    public function onDeleted(AnnouncementDeleted $event): void
    {
        activity(self::LogName)
            ->performedOn($event->announcement)
            ->log(__('Has Deleted an announcement in the application.'));
    }

    /**
     * The event listener for the event that gets fired when an announcement is created.
     *
     * @param  AnnouncementCreated $event The event that gets fired in the service layer.
     * @return void
     */
    public function onCreated(AnnouncementCreated $event): void
    {
        activity(self::LogName)
            ->performedOn($event->announcement)
            ->log(__('Has created an announcement in the application'));
    }
}
