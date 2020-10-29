<?php

namespace App\Domains\Users\Http\Controllers;

use App\Domains\Users\Events\UserImpersonated;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Lab404\Impersonate\Services\ImpersonateManager;

/**
 * Class ImpersonateController
 *
 * @todo Register routes
 *
 * @package App\Domains\Users\Http\Controllers
 */
class ImpersonateController extends Controller
{
    private ImpersonateManager $impersonateManager;

    /**
     * ImpersonateController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'kiosk', '2fa']);
        $this->impersonateManager = app()->make(ImpersonateManager::class);
    }

    /**
     * Method for impersonating another user in the application.
     *
     * @param  Request     $request   The resource entity that contains all the request information.
     * @param  User        $user      The resource entity from the user that needs to be impersonated.
     * @param  string|null $guardName The authentication guard name defaults to null
     * @return RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function take(Request $request, User $user, ?string $guardName = null): RedirectResponse
    {
        $guardName = $guardName ?? $this->impersonateManager->getDefaultSessionGuard();

        $this->authorize('impersonate', [$user, $guardName]);

        if ($user->canBeImpersonated() && $this->impersonateManager->take($request->user(), $user, $guardName)) {
            event(new UserImpersonated($user));
        }

        return redirect()->route('home');
    }

    /**
     * Method for leaving the impersonation session in the application.
     *
     * @return RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function leave(): RedirectResponse
    {
        $this->authorize('leave-impersonation', User::class); // TODO: Build up policy
        $this->impersonateManager->leave();

        return redirect()->route('home');
    }
}
