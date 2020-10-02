<?php

namespace App\Domains\Users\Events;

use App\User;

/**
 * Class UserRestored
 *
 * @package App\Domains\Users\Events
 */
class UserRestored
{
    public User $user;

    /**
     * UserRestored constructor.
     *
     * @param  User $user The resource entity from the user where the handling is performed on.
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
