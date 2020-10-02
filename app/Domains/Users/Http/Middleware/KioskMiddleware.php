<?php

namespace App\Domains\Users\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class KioskMiddleware
 *
 * @package App\Domains\Users\Http\Middleware
 */
class KioskMiddleware
{
    private Guard $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = $this->auth->user();

        if (! in_array($user->user_group, config('spoon.access.kiosk'), true)) {
            flash()->warning(__('You are not authorized to access the kiosk.'));
        }

        return $next($request);
    }
}
