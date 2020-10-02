<?php

namespace App\Domains\Api\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\PersonalAccessToken as PersonalAccessTokenLaravel;

/**
 * Class PersonalAccessToken
 *
 * @property \App\User $tokenable
 *
 * @package App\Domains\Api\Models
 */
class PersonalAccessToken extends PersonalAccessTokenLaravel
{
    use SoftDeletes;

    /**
     * Method for determining if the user can revoke an personal access token.
     *
     * @return bool
     */
    public function canRevoke(): bool
    {
        return ! $this->trashed() && auth()->user()->can('revoke-token', $this);
    }

    /**
     * Method for determining if the user can restore the personal access token.
     *
     * @return bool
     */
    public function canRestore(): bool
    {
        return $this->trashed() && auth()->user()->can('activate-token', $this);
    }
}
