<?php

namespace App\Domains\Users\Events;

use App\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserCreated
 *
 * @package App\Domains\Users\Events
 */
class UserCreated
{
    use SerializesModels;

    public User $user;

    /**
     * UserCreated constructor.
     *
     * @param  User $user The resource entity from the user where the handling is performed on.
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
