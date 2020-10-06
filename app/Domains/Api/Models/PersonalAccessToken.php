<?php

namespace App\Domains\Api\Models;

use Database\Factories\PersonalAccessTokenFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasFactory;

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

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return PersonalAccessTokenFactory::new();
    }

}
