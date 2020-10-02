<?php

namespace App\Domains\Roles\Listeners;

use App\Domains\Roles\Events\RoleCreated;
use App\Domains\Roles\Events\RoleDeleted;
use App\Domains\Roles\Events\RoleUpdated;
use Illuminate\Events\Dispatcher;

/**
 * Class RoleEventListener
 *
 * @package App\Domains\Roles\Listeners
 */
class RoleEventListener
{
    /**
     * The log name for role related activities.
     *
     * @var string
     */
    public const LogName = 'Role management';

    /**
     * Register the listeners for the event subscriber.
     *
     * @param  Dispatcher $events The event displatching class.
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(RoleCreated::class, [RoleEventListener::class, 'onCreated']);
        $events->listen(RoleUpdated::class, [RoleEventListener::class, 'onUpdated']);
        $events->listen(RoleDeleted::class, [RoleEventListener::class, 'onDeleted']);
    }

    /**
     * The event listener for the event that get gets fired when a new role is created in the application by an admin.
     *
     * @param  RoleCreated $event The event class that gets fired in the service layer.
     * @return void
     */
    public function onCreated(RoleCreated $event): void
    {
        activity(self::LogName)
            ->performedOn($event->role)
            ->log(__('Created the permission role with :role as name.', ['role' => $event->role->name]));
    }

    /**
     * The event listener for the event that gets fired when a role is updated in the application by an admin.
     *
     * @param  RoleUpdated $event The event class that gets fired in the service layer.
     * @return void
     */
    public function onUpdated(RoleUpdated $event): void
    {
        activity(self::LogName)
            ->performedOn($event->role)
            ->log(__('Has updated the permissions from the following role :role', ['role' => $event->role->name]));
    }

    /**
     * The event listener for the event that gets fired when a role is updated in the application by an admin.
     *
     * @param  RoleDeleted $event The event class that gets fired in the service layer.
     * @return void
     */
    public function onDeleted(RoleDeleted $event): void
    {
        activity(self::LogName)
            ->performedOn($event->role)
            ->log(__('Has deleted the :role permission role', ['role' => $event->role->name]));
    }
}
