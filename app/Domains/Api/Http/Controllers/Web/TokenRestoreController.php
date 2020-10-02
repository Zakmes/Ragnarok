<?php

namespace App\Domains\Api\Http\Controllers\Web;

use App\Domains\Api\Actions\RevokeTokenAction;
use App\Domains\Api\Models\PersonalAccessToken;
use App\Domains\Api\Services\TokenService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use function kioskRoute;

/**
 * Class TokenRestoreController
 *
 * @package App\Domains\Api\Http\Controllers\Web
 */
class TokenRestoreController extends Controller
{
    /**
     * TokenRestoreController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'kiosk']);
    }

    /**
     * Method for restoring revoked personal access tokens.
     *
     * @param  PersonalAccessToken $accessToken       The database entity for the access token.
     * @param  TokenService        $tokenService      The business layer for all the logic that is related to API tokens.
     * @param  RevokeTokenAction   $revokeTokenAction The action that will handle the restore operation
     * @return RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(PersonalAccessToken $accessToken, TokenService $tokenService, RevokeTokenAction $revokeTokenAction): RedirectResponse
    {
        $this->authorize('activate-token', $accessToken);

        $revokeTokenAction->execute($accessToken, $tokenService, 'restore');
        flash()->success(__('The personal access token is successfully reactivated.'));

        return redirect(kioskRoute('api-management.index'));
    }
}
