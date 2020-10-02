<?php

namespace App\Domains\Api\Events;

use App\Domains\Api\Models\PersonalAccessToken;

/**
 * Class PersonalAccessTokenRestored
 *
 * @package App\Domains\Api\Events
 */
class PersonalAccessTokenRestored
{
    public PersonalAccessToken $personalAccessToken;

    /**
     * PersonalAccessTokenRestored constructor.
     *
     * @param  PersonalAccessToken $personalAccessToken The resource entity from the given personal access token.
     * @return void
     */
    public function __construct(PersonalAccessToken $personalAccessToken)
    {
        $this->personalAccessToken = $personalAccessToken;
    }
}
