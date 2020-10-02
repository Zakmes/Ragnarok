<?php

namespace App\Domains\Api\Events;

use App\Domains\Api\Models\PersonalAccessToken;

/**
 * Class PersonalAccessTokenRevoked
 *
 * @package App\Domains\Api\Events
 */
class PersonalAccessTokenRevoked
{
    public PersonalAccessToken $personalAccessToken;

    /**
     * PersonalAccessTokenRevoked constructor.
     *
     * @param  PersonalAccessToken $personalAccessToken The resource entity from the given personal access token.
     * @return void
     */
    public function __construct(PersonalAccessToken $personalAccessToken)
    {
        $this->personalAccessToken = $personalAccessToken;
    }
}
