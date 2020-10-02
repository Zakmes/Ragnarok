<?php

namespace App\Domains\Users\Actions;

use App\User;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class UpdateAction
 *
 * @package App\Domains\Users\Actions
 */
class UpdateAction extends BaseAction
{
    /**
     * Execute all the business logic for an user update.
     *
     * @todo Check that user in the normal user group cannot assign roles.
     * @todo When the user is going back to normal user group de roles needed to be detached.
     *
     * @param  User               $user               The resource entity from the user that needs the update.
     * @param  DataTransferObject $dataTransferObject The data transfer object from the user data
     * @return bool
     */
    public function execute(User $user, DataTransferObject $dataTransferObject): bool
    {
        if ($this->isExcludedRoute() && $dataTransferObject->role !== null) {
            $user->syncRoles($dataTransferObject->role);
        }

        return $this->userService->update($user, $dataTransferObject->toArray());
    }

    /**
     * The excluded routes for the role sync method.
     *
     * @return bool
     */
    private function isExcludedRoute()
    {
        return ! is_active(['account.security.patch', 'account.information.patch']);
    }
}
