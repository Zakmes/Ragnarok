<?php

namespace App\Http\Controllers;

use App\Domains\Activity\Models\Activity;
use App\Domains\Activity\Services\ActivityService;
use App\Domains\Api\Services\TokenService;
use App\Domains\Users\Models\Role;
use App\Domains\Users\Services\RoleService;
use App\Domains\Users\Services\UserService;
use App\User;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class KioskController
 *
 * @package App\Http\Controllers
 */
class KioskController extends Controller
{
    private ActivityService $activityService;
    private UserService $userService;
    private RoleService $roleService;
    private TokenService $personalAccessTokenService;

    /**
     * KioskController constructor.
     *
     * @param  ActivityService  $activityService
     * @param  UserService      $userService
     * @param  RoleService      $roleService
     * @param  TokenService     $personalAccessTokenService The service layer for the personal access tokens. (API)
     * @return void
     */
    public function __construct(
        ActivityService $activityService,
        UserService $userService,
        RoleService $roleService,
        TokenService $personalAccessTokenService
    ) {
        $this->middleware(['auth', 'kiosk']);

        $this->activityService = $activityService;
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->personalAccessTokenService = $personalAccessTokenService;
    }

    /**
     * Method for displaying the kiosk dashboard page.
     *
     * @return Renderable
     */
    public function __invoke(): Renderable
    {
        return view('kiosk', [
            'activities' => Activity::orderBy('id', 'DESC')->limit(10)->get(),
            'users' => User::orderBy('id', 'DESC')->limit(5)->get(),
            'roles' => Role::orderBy('id', 'DESC')->limit(5)->get()
        ]);
    }
}
