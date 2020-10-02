<?php

namespace App\Domains\Users\Http\Controllers;

use App\Domains\Users\Actions\DeleteAction;
use App\Domains\Users\Enums\DeleteType;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use function kioskRoute;

/**
 * Class RestoreController
 *
 * @package App\Domains\Users\Http\Controllers
 */
class RestoreController extends Controller
{
    /**
     * RestoreController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'kiosk']);
    }

    /**
     * Method for restoring user accounts that are marked for deletion.
     *
     * @param  int          $user             The unique identifier from the user in the storage.
     * @param  DeleteAction $userDeleteAction The action class that handling marking, deleting and restoring accounts.
     * @return RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(int $user, DeleteAction $userDeleteAction): RedirectResponse
    {
        $this->authorize('restore', $user);

        $user = $userDeleteAction->execute($user, DeleteType::RESTORE);
        flash()->success(__("The user account from {$user->name} is successfully restored"));

        return redirect(kioskRoute('users.show', $user));
    }
}
