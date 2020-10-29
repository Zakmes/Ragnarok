<?php

namespace App\Domains\Users\Http\Middleware;

use Closure;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class DeactivationMiddleware
 *
 * @package App\Domains\Users\Http\Middleware
 */
class DeactivationMiddleware
{
    /**
     * The authentication guard implementation variable.
     *
     * @var Guard
     */
    protected Guard $auth;

    /**
     * DeactivationMiddleware constructor.
     *
     * @param  Guard $auth The authentication guard implementation.
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     *
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();

        if ($this->auth->check() && $this->isBanned($user)) {
            if ($this->requestIsErrorPage($request) || $this->requestIsLogoutRoute($request)) {
                return $next($request);
            }

            return redirect(kioskRoute('users.lock.error'));
        }

        return $next($request);
    }

    /**
     * Determine whether the user is banned or not.
     *
     * @param  Authenticatable|null $user The resource entity from the authenticated user. Default to null
     * @return bool
     */
    private function isBanned(?Authenticatable $user): bool
    {
        return $user && $user instanceof BannableContract && $user->isBanned();
    }

    /**
     * Check if the current route is the same than the deactivation error page.
     *
     * @param  Request $request The request instance that holds all the information.
     * @return bool
     */
    private function requestIsErrorPage(Request $request): bool
    {
        return $request->route()->getName() === config('spoon.kiosk_prefix') . '.users.lock.error';
    }

    /**
     * Check if the current route is the logout route from the application.
     *
     * @param  Request $request The request instance that holds all the information.
     * @return bool
     */
    private function requestIsLogoutRoute(Request $request): bool
    {
        return $request->route()->getName() === 'logout';
    }
}
