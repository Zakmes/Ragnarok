<?php

namespace App\Domains\Api\Listeners;

use App\Domains\Api\Events\PersonalAccessTokenRestored;
use App\Domains\Api\Events\PersonalAccessTokenRevoked;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Class PersonalAccessTokenEventListener
 *
 * @package App\Domains\Api\Listeners
 */
class PersonalAccessTokenEventListener
{
    /**
     * The log name for the activities that are related to the personal access tokens.
     *
     * @var string
     */
    public const LogName = 'API management';

    /**
     * Register the listener for the event subscriber.
     *
     * @param  Dispatcher $events The event dispatcher class.
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(PersonalAccessTokenRestored::class, [PersonalAccessTokenEventListener::class, 'onRestored']);
        $events->listen(PersonalAccessTokenRevoked::class, [PersonalAccessTokenEventListener::class, 'onRevoked']);
    }

    /**
     * The event listener for when an personal access token gets restored by an kiosk user.
     *
     * @param  PersonalAccessTokenRestored $event The event that gets fired in the service layer.
     * @return void
     */
    public function onRestored(PersonalAccessTokenRestored $event): void
    {
        $owner = $event->personalAccessToken->tokenable;

        activity(self::LogName)
            ->performedOn($event->personalAccessToken)
            ->log(__('Restored an personal access token from :user', ['user' => $owner->name]));
    }

    /**
     * The event listener for when an personal token gets revoked by an kiosk user.
     *
     * @param  PersonalAccessTokenRevoked $event The event that get fired in the service layer.
     * @return void
     */
    public function onRevoked(PersonalAccessTokenRevoked $event): void
    {
        $owner = $event->personalAccessToken->tokenable;

        activity(self::LogName)
            ->performedOn($event->personalAccessToken)
            ->log(__('Revoked an personal access token form :user', ['user' => $owner->name]));
    }
}
