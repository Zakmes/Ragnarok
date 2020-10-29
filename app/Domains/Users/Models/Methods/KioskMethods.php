<?php

namespace App\Domains\Users\Models\Methods;

/**
 * Trait KioskMethods
 *
 * @package App\Domains\Users\Models\Methods
 */
trait KioskMethods
{
    /**
     * Function for determining whether the user can access the Kiosk or not.
     *
     * @return bool
     */
    public function canAccessKiosk(): bool
    {
        return ! $this->isOnKiosk() && $this->hasKioskUserGroup();
    }

    /**
     * Method for determining is the user is on the application.
     *
     * @return bool
     */
    public function isOnApplication(): bool
    {
        return ! $this->isOnKiosk();
    }

    /**
     * Determine if the authenticated user is currently on the kiosk section.
     *
     * @return bool
     */
    public function isOnKiosk(): bool
    {
        return is_active(config('spoon.kiosk_prefix') . '.*');
    }

    /**
     * Determine whether the user has the correct kiosk user group.
     *
     * @return bool
     */
    public function hasKioskUserGroup(): bool
    {
        return in_array($this->user_group, config('spoon.access.kiosk'), true);
    }
}
