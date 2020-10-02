<?php

namespace App\Domains\Api\Actions;

use App\Domains\Api\Events\PersonalAccessTokenRestored;
use App\Domains\Api\Events\PersonalAccessTokenRevoked;
use App\Domains\Api\Models\PersonalAccessToken;
use App\Domains\Api\Services\TokenService;
use App\User;

/**
 * Class RevokeTokenAction
 *
 * @package App\Domains\Api\Actions
 */
class RevokeTokenAction
{
    /**
     * Method for revoking the personal access token in the database.
     *
     * @param  PersonalAccessToken $accessToken  The instance from the personal access token.
     * @param  TokenService        $tokenService The business logic layer for revoking user tokens in the application.
     * @param  string              $action       The name for the action that is needed.
     * @return bool|null
     */
    public function execute(PersonalAccessToken $accessToken, TokenService $tokenService, string $action = 'revoke'): ?bool
    {
        if ($this->wantsToRestoreToken($action) && $tokenService->restoreToken($accessToken)) {
            event(new PersonalAccessTokenRestored($accessToken));
            return true;
        }

        if ($this->wantsToRevokeToken($action) && $tokenService->revokeToken($accessToken)) {
            if (auth()->user()->isNot($accessToken->tokenable)) {
                event(new PersonalAccessTokenRevoked($accessToken));
            }

            return true;
        }
    }

    /**
     * Method for determining which action is needed.
     *
     * @param  string $action The action name that needs to be executed.
     * @return bool
     */
    private function wantsToRestoreToken(string $action): bool
    {
        return $action === 'restore';
    }

    /**
     * Method for determining that the given action is a revoke action.
     *
     * @param  string $action The action name that needs to be executed.
     * @return bool
     */
    private function wantsToRevokeToken(string $action): bool
    {
        return $action === 'revoke';
    }
}
