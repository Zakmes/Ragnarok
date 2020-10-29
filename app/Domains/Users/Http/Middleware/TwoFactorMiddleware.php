<?php

namespace App\Domains\Users\Http\Middleware;

use App\Domains\Users\Services\AuthenticatorService;
use Closure;

/**
 * Class TwoFactorMiddleware
 *
 * @package App\Domains\Users\Http\Middleware
 */
class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authenticator = app(AuthenticatorService::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            return $next($request);
        }

        return $authenticator->makeRequestOneTimePasswordResponse();
    }
}
