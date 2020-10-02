<?php

namespace App\Support\Modules;

/**
 * Trait HasApiModuleChecks
 *
 * @package App\Support\Modules
 */
trait HasApiModuleChecks
{
    /**
     * Method for determine if the api module is enabled.
     *
     * @return bool
     */
    public function apiModuleEnabled(): bool
    {
        return config('spoon.modules.api-tokens');
    }
}
