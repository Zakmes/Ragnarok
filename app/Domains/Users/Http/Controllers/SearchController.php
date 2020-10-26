<?php

namespace App\Domains\Users\Http\Controllers;

use App\Domains\Users\Enums\GroupEnum;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

/**
 * Class SearchController
 *
 * @see     \App\Providers\MacroServiceProvider::boot() - For the eloquent search macro
 * @package App\Domains\Users\Http\Controllers
 */
class SearchController extends Controller
{
    /**
     * SearchController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'kiosk', '2fa']);
    }

    /**
     * Method for displaying the result records based on the user search.
     *
     * @param Request $request The request entity that contains all the request information.
     * @param User $users Data database model for the users in the application.
     * @return Renderable
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, User $users): Renderable
    {
        $this->authorize('viewAny', User::class);

        return view('users.index', [
            'users' => $users->search(['firstName', 'lastName', 'email'], $request->term)->paginate(),
            'filter' => null,
            'groupEnum' => GroupEnum::class,
        ]);
    }
}
