<?php

namespace App\Domains\Api\Http\Controllers\Web;

use App\Domains\Api\Actions\CreateTokenAction;
use App\Domains\Api\Actions\RevokeTokenAction;
use App\Domains\Api\DTO\TokenDataTransferObject;
use App\Domains\Api\Http\Requests\CreateFormRequest;
use App\Domains\Api\Models\PersonalAccessToken;
use App\Domains\Api\Services\TokenService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function redirect;

/**
 * Class ApiTokensController
 *
 * @package App\Domains\Users\Http\Controllers
 */
class UserTokensController extends Controller
{
    private TokenService $tokenService;

    /**
     * ApiTokensController constructor.
     *
     * @param  TokenService $tokenService The business logic that is related to the API authentication.
     * @return void
     */
    public function __construct(TokenService $tokenService)
    {
        $this->middleware('auth');
        $this->middleware('kiosk')->except(['show', 'revoke', 'store']);

        $this->tokenService = $tokenService;
    }

    /**
     * Method for displaying all the personal access tokens in the application.
     *
     * @param  string|null $filter The result filter u want to apply to the overview.
     * @return Renderable
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(?string $filter = null): Renderable
    {
        $this->authorize('tokens-overview', PersonalAccessToken::class);

        return view('api.index', ['tokens' => $this->tokenService->getAllPersonalAccessTokens($filter), 'filter' => $filter]);
    }

    /**
     * Show the user api token screen in their settings.
     *
     * @param  Request $request The request instance that contains all the request information.
     * @return Renderable
     */
    public function show(Request $request): Renderable
    {
        $user = $request->user();
        $tokens = $this->tokenService->tokensForUser($request->user(), 8);

        return view('api.user-screen', compact('user', 'tokens'));
    }

    /**
     * Method for creating new personal access tokens (API) in the application.
     *
     * @param  CreateFormRequest  $request     The request instance that contains all the request information.
     * @param  CreateTokenAction  $createToken The action that handles all the creation logic for the personal access token.
     * @return RedirectResponse
     */
    public function store(CreateFormRequest $request, CreateTokenAction $createToken): RedirectResponse
    {
        $accessToken = $createToken->execute($request->user(), TokenDataTransferObject::fromRequest($request));
        session()->flash('token', explode('|', $accessToken->plainTextToken, 2)[1]);

        return redirect()->route('account.tokens');
    }

    /**
     * Method for revoking personal access tokens.
     *
     * @param PersonalAccessToken $accessToken       The database instance from the personal access token.
     * @param RevokeTokenAction   $revokeTokenAction The action that actually revokes the token.
     * @return RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function revoke(PersonalAccessToken $accessToken, RevokeTokenAction $revokeTokenAction): RedirectResponse
    {
        $this->authorize('revokeToken', $accessToken);

        $revokeTokenAction->execute($accessToken, $this->tokenService);
        flash()->success(__('The personal access token is successfully revoked'));

        return back();
    }
}
