<?php

namespace App\Domains\Users\Http\Middleware;

use App\Domains\Users\Services\AuthenticatorService;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class TwoFactorRecoveryMiddleware
 *
 * @package App\Domains\Users\Http\Middleware
 */
class TwoFactorRecoveryMiddleware
{
    private Guard $auth;

    /**
     * TwoFactorRecoveryMiddleware constructor.
     *
     * @param  Guard $auth The authentication guard for the users.
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request The request entity that contains all the request information
     * @param  Closure  $next    The close for proceeding with the request
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->canProceedRequest($request)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }

    /**
     * Method for determining whether the 2FA module is enabled in the application.
     *
     * @return bool
     */
    private function twoFactorEnabled(): bool
    {
        return config('google2fa.enabled');
    }

    /**
     * Method for determining whether the user can proceed the actual request.
     *
     * @param  Request $request The request entity that contains all the request information
     * @return bool
     */
    private function canProceedRequest(Request $request): bool
    {
        $authenticator = app(AuthenticatorService::class)->boot($request);

        return $this->auth->user()->isUsingTwoFactorAuthentication()
            && $this->twoFactorEnabled()
            && $authenticator->isAuthenticated();
    }
}
