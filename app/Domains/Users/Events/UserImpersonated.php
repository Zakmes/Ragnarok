<?php

namespace App\Domains\Users\Events;

use App\User;

/**
 * Class UserImpersonated
 *
 * @package App\Domains\Users\Events
 */
class UserImpersonated
{
    public User $user;

    /**
     * UserImpersonated constructor.
     *
     * @param  User $user The resource entity from the given user.
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
