<?php

namespace App\Domains\Api\Actions;

use App\User;
use Laravel\Sanctum\NewAccessToken;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class CreateTokenAction
 *
 * @package App\Domains\Api\Actions
 */
class CreateTokenAction
{
    /**
     * Method for creating a new api token in the database storage.
     *
     * @param  User               $user               The database entity from the given user
     * @param  DataTransferObject $dataTransferObject The DataTransferObject that holds the token name
     * @return NewAccessToken
     */
    public function execute(User $user, DataTransferObject $dataTransferObject): NewAccessToken
    {
        return $user->createToken($dataTransferObject->name);
    }
}
