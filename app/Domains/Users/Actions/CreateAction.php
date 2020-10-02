<?php

namespace App\Domains\Users\Actions;

use App\User;
use Illuminate\Support\Str;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class CreateAction
 *
 * @package App\Domains\Users\Actions
 */
class CreateAction extends BaseAction
{
    /**
     * All the needed business logic for creating the new user in the application.
     *
     * @todo Implement Notification mail for the newly created user.
     *
     * @param  DataTransferObject $dataTransferObject The mapped data object for the user.
     * @param  string|null        $role               The permission group for the user.
     * @return User
     */
    public function execute(DataTransferObject $dataTransferObject, ?string $role): User
    {
        $password = $this->generatePassword();
        $userInformation = array_merge($dataTransferObject->except('role')->toArray(), $password);

        return $this->userService->registerUser($userInformation, $role);
    }

    /**
     * Method for generating a random password for the user.
     *
     * @return array
     */
    private function generatePassword(): array
    {
        return ['password' => Str::random(config('spoon.auth.password_length', 16))];
    }
}
