<?php

namespace App\Domains\Users\Events;

use App\User;

/**
 * Class UserUpdated
 *
 * @package App\Domains\Users\Events
 */
class UserUpdated
{
    public User $user;

    /**
     * UserUpdated constructor.
     *
     * @param  User $user The resource entity from the user where the handling is performed on.
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
