<?php

namespace App\Domains\Users\Models\Methods;

use Cog\Laravel\Ban\Models\Ban;

/**
 * Trait GeneralMethods
 *
 * @package App\Domains\Users\Models\Scopes
 */
trait GeneralMethods
{
    /**
     * Method for getting the latest ban for the user.
     *
     * @return Ban
     */
    public function latestBan(): Ban
    {
        return $this->bans()->first();
    }

    /**
     * Method for displaying the reason why the user is banned.
     *
     * @return string
     */
    public function deactivationReason(): string
    {
        return $this->latestBan()->comment;
    }
}
