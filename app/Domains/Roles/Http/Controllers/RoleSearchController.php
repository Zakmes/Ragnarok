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
     * Method for displaying all the results for the role search
     *
     * @param  Request $request  The request instance that contains all the request information.
     * @param  Role    $roles    The database model for the user permission roles
     * @return Renderable
     */
    public function __invoke(Request $request, Role $roles): Renderable
    {
        return view('roles.index', [
            'roles' => $roles->search(['name', 'creator.firstName', 'creator.lastName', 'creator.email'], $request->term),
        ]);
    }
}
