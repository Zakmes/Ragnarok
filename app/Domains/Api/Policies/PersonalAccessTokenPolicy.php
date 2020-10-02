<?php

namespace App\Domains\Api\Policies;

use App\User;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Class PersonalAccessTokenPolicy
 *
 * @package App\Domains\Api\Policies
 */
class PersonalAccessTokenPolicy
{
    /**
     * Determine whether the user can access the personal access tokens overview or not.
     *
     * @param  User $user The instance from the current authenticated user.
     * @return bool
     */
    public function tokensOverview(User $user): bool
    {
        return $user->hasPermissionTo('overview-tokens') && $user->hasKioskUserGroup();
    }

    /**
     * Determine whether the authenticated user can revoke his owns token or other users.
     *
     * @param  User                $user        The instance from the currently authenticated user.
     * @param  PersonalAccessToken $accessToken The database entity from the personal access token.
     * @return bool
     */
    public function revokeToken(User $user, PersonalAccessToken $accessToken): bool
    {
        return $user->is($accessToken->tokenable)
            || $user->hasPermissionTo('revoke-tokens')
            && $user->hasKioskUserGroup()
            && ! $accessToken->trashed();
    }

    /**
     * Determine whether the user can restore revoked access tokens from other users.
     *
     * @param  User                $user        The instance from the currently authenticated user.
     * @param  PersonalAccessToken $accessToken The database entity from the personal access token.
     * @return bool
     */
    public function activateToken(User $user, PersonalAccessToken $accessToken): bool
    {
        return $user->is($accessToken->tokenable)
            || $user->hasPermissionTo('restore-tokens')
            && $user->hasKioskUserGroup()
            && $accessToken->trashed();
    }
}
