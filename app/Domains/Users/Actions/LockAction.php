<?php

namespace App\Domains\Users\Actions;

use App\User;
use Cog\Contracts\Ban\Ban;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class LockAction
 *
 * @package App\Domains\Users\Actions
 */
class LockAction extends BaseAction
{
    /**
     * Method for executing the business logic that is needed for the account deactivation.
     *
     * @param  User               $user                 The resource entity from the given user that needs the lock.
     * @param  DataTransferObject $dataTransferObject   The data transfer object from the user data.
     * @return Ban
     */
    public function execute(User $user, DataTransferObject $dataTransferObject): Ban
    {
        return $user->ban($dataTransferObject->toArray());
    }
}
