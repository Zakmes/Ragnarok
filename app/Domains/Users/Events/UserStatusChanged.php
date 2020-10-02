<?php

namespace App\Domains\Users\Events;

use App\User;

/**
 * Class UserStatusChanged
 *
 * @package App\Domains\Users\Events
 */
class UserStatusChanged
{
    public User $user;
    public string $message;

    /**
     * UserStatusChanged constructor.
     *
     * @param  User   $user    The resource entity from the user where the handling is performed on.
     * @param  string $message The message that needs to be logged.
     * @return void
     */
    public function __construct(User $user, string $message)
    {
        $this->user = $user;
        $this->message = $message;
    }
}
