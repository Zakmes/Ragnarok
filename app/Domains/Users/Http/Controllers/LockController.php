<?php

namespace App\Domains\Users\Http\Controllers;

use function abort_if;
use App\Domains\Users\Actions\LockAction;
use App\Domains\Users\DTO\LockReasonObject;
use App\Domains\Users\Events\UserStatusChanged;
use App\Domains\Users\Http\Requests\LockFormRequest;
use App\Domains\Users\Services\UserService;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function kioskRoute;

/**
 * Class LockController
 *
 * @package App\Domains\Users\Http\Controllers
 */
class LockController extends Controller
{
    /**
     * LockController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'kiosk'])->except('index ');
    }

    /**
     * Method for getting the user deactivation method.
     *
     * @param  UserService $userService The business logic layer that is related to the users in the application.
     * @return Renderable
     */
    public function index(Request $request, UserService $userService): Renderable
    {
        abort_if($request->user()->isNotBanned(), Response::HTTP_NOT_FOUND);

        return view('errors.deactivated')->withUser($request->user())->withBanInformation($request->user()->latestBan());
    }

    /**
     * Method for displaying the lock view for users.
     *
     * @param  User $userEntity The resource entity from the given user.
     * @return Renderable
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(User $userEntity): Renderable
    {
        $this->authorize('lock', $userEntity);

        return view('users.lock', ['user' => $userEntity]);
    }

    /**
     * Method for storing the user lock in the application;
     *
     * @param  LockFormRequest  $formRequest    The form class that handles The validation and authorization for controller method.
     * @param  User             $userEntity     The user resource entity that will be locked in the application.
     * @param  LockAction       $lockAction     The action that handles all the business logic for locking out users.
     * @return RedirectResponse
     */
    public function store(LockFormRequest $formRequest, User $userEntity, LockAction $lockAction): RedirectResponse
    {
        $languageKeys = ['user' => $userEntity->name, 'application' => config('app.name')];
        $lockAction->execute($userEntity, LockReasonObject::fromRequest($formRequest));

        flash()->success(__(':user is successfully locked in the :application.', $languageKeys));
        event(new UserStatusChanged($userEntity, __('Deactivated the account from :user in :application.', $languageKeys)));

        return redirect(kioskRoute('users.show', $userEntity));
    }

    /**
     * Method for unlocking users in the application.
     *
     * No action is used here because we fire only the unban method that is attached
     * on the user model of the application.
     *
     * @param User $userEntity The resource entity from the user that needs the unlock.
     * @return RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(User $userEntity): RedirectResponse
    {
        $this->authorize('unlock', $userEntity);

        $languageKeys = ['user' => $userEntity->name, 'application' => config('app.name')];
        $userEntity->unban();

        flash()->success(__('The user account from :user is successfully unlocked.', ['user' => $userEntity->name]));
        event(new UserStatusChanged($userEntity, __('Reactivated the account from :user in :application', $languageKeys)));

        return back();
    }
}
