<?php

namespace App\Domains\Users\Listeners;

use App\Domains\Users\Events\UserCreated;
use App\Domains\Users\Events\UserDeleted;
use App\Domains\Users\Events\UserImpersonated;
use App\Domains\Users\Events\UserRestored;
use App\Domains\Users\Events\UserStatusChanged;
use App\Domains\Users\Events\UserUpdated;
use Illuminate\Events\Dispatcher;

/**
 * Class UserEventListener
 *
 * @package App\Domains\Users\Listeners
 */
class UserEventListener
{
    /**
     * The log name for user related activities;
     *
     * @var string
     */
    public const LogName = 'Users';

    /**
     * Register the listeners for the event subscriber.
     *
     * @param  Dispatcher $events The event dispatching class.
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(UserCreated::class, [UserEventListener::class, 'onCreated']);
        $events->listen(UserDeleted::class, [UserEventListener::class, 'onDeleted']);
        $events->listen(UserUpdated::class, [UserEventListener::class, 'onUpdated']);
        $events->listen(UserRestored::class, [UserEventListener::class, 'onRestored']);
        $events->listen(UserStatusChanged::class, [UserEventListener::class, 'onStatusChange']);
        $events->listen(UserImpersonated::class, [UserEventListener::class, 'onImpersonated']);
    }

    /**
     * The event listener for the event that get fired when a new user is created in the application by an admin.
     *
     * @param  UserCreated $event The event that get fired in the service layer.
     * @return void
     */
    public function onCreated(UserCreated $event): void
    {
        activity(self::LogName)
            ->performedOn($event->user)
            ->withProperties(['roles' => $event->user->roles->count() ? $event->user->roles->pluck('name')->implode(', ') : 'None'])
            ->log(__('Created user :user in the :userGroup group with the following roles: :properties.roles', [
                'user' => $event->user->name, 'userGroup' => $event->user->user_group
        ]));
    }

    /**
     * The event listener for the the event that get fired when a user is deleted in the application.
     *
     * @param  UserDeleted $event The event that gets fired in the service layer.
     * @return void
     */
    public function onDeleted(UserDeleted $event): void
    {
        activity(self::LogName)
            ->performedOn($event->user)
            ->log(__('Marked :user for deletion in the application, will be deleted at :time', [
                'user' => $event->user->name, 'time' => now()->addWeeks(2)->format('d/m/Y')
            ]));
    }

    /**
     * The event listener for the event that gets fired when a user account is restored in the application.
     *
     * @param  UserRestored $event The event that get fired in the service layer.
     * @return void
     */
    public function onRestored(UserRestored $event): void
    {
        activity(self::LogName)
            ->performedOn($event->user)
            ->log(__('Restored the user account for :user in the application.', ['user' => $event->user->name]));
    }

    /**
     * The event listener for the event that gets fired when a user is updated in the application.
     *
     * @param  UserUpdated $event The event that get fired in the service layer.
     * @return void
     */
    public function onUpdated(UserUpdated $event): void
    {
        activity(self::LogName)
            ->performedOn($event->user)
            ->withProperties(['roles' => $event->user->roles->count() ? $event->user->roles->pluck('name')->implode(', ') : 'None'])
            ->log(__('Update the account from :user in the :userGroup group with the following roles: :properties.roles', [
                'user' => $event->user->name, 'userGroup' => $event->user->user_group
            ]));
    }

    /**
     * The event listener for the event that gets fired when a user is impersonated in the application.
     *
     * @param  UserImpersonated $event The event that gets fired when during the impersonation process.
     * @return void
     */
    public function onImpersonated(UserImpersonated $event): void
    {
        activity(self::LogName)
            ->performedOn($event->user)
            ->log(__(':impersonator is starting with an impersonation session for the user :user', [
                'impersonator' => $event->user->name, 'user' => auth()->user()->name
            ]));
    }

    /**
     * The event listener for the event that gets fired when a user is locked or unlocked in the application.
     *
     * @param  UserStatusChanged $event The event that get fired in the service layer.
     * @return void
     */
    public function onStatusChange(UserStatusChanged $event): void
    {
        activity(self::LogName)
            ->performedOn($event->user)
            ->log($event->message);
    }
}
