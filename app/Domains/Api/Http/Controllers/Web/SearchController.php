<?php

namespace App\Domains\Api\Http\Controllers\Web;

use App\Domains\Api\Models\PersonalAccessToken;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

/**
 * Class SearchController
 *
 * @package App\Domains\Api\Http\Controllers
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
        $this->middleware(['auth', '2fa', 'kiosk']);
    }

    /**
     * Method for searching trough the registered personal access tokens in the application.
     *
     * @param  Request             $request             The resource entity tht contains all the request information.
     * @param  PersonalAccessToken $personalAccessToken The database model for the personal access tokens in the application.
     * @return Renderable
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, PersonalAccessToken $personalAccessToken): Renderable
    {
        $this->authorize('tokens-overview', $personalAccessToken);

        return view('api.index', [
            'filter' => null,
            'tokens' => $personalAccessToken->search(['name'], $request->term)->paginate()
        ]);
    }
}
