<?php

namespace App\Domains\Roles\Http\Controllers;

use App\Domains\Users\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

/**
 * Class RoleSearchController
 *
 * @see     \App\Providers\MacroServiceProvider::boot() - For the eloquent search macro
 * @package App\Domains\Roles\Http\Controllers
 */
class RoleSearchController extends Controller
{
    /**
     * RoleSearchController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'kiosk', '2fa']);
    }

    /**
     * Method for displaying all the results for the role search
     *
     * @param Request $request The request instance that contains all the request information.
     * @param Role $roles The database model for the user permission roles
     * @return Renderable
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, Role $roles): Renderable
    {
        $this->authorize('viewAny', Role::class);

        return view('roles.index', [
            'roles' => $roles->search(['name', 'creator.firstName', 'creator.lastName', 'creator.email'], $request->term),
        ]);
    }
}
