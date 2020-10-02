<?php

namespace App\Domains\Users\Actions;

use App\Domains\Users\Enums\DeleteType;
use App\User;
use Spatie\QueueableAction\QueueableAction;

/**
 * Class DeleteAction
 *
 * @package App\Domains\Users\Actions
 */
class DeleteAction extends BaseAction
{
    use QueueableAction;

    /**
     * Execute the user deletion job in the application.
     *
     * @param  int|User $user   The given resource entity from the user in the application.
     * @param  string   $action The type of action that needs to be fired. Can be delete or restore
     * @return bool|User|null
     *
     * @throws \Exception
     */
    public function execute($user, string $action = 'mark')
    {
        switch ($action) {
            case DeleteType::MARK:    return $this->userService->markUser($user);
            case DeleteType::RESTORE: return $this->userService->restoreUser($user);
            case DeleteType::DELETE:  return $this->userService->deleteUser($user);
        }
    }
}
