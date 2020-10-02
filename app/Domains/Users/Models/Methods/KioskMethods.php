<?php

namespace App\Domains\Users\Models\Methods;

/**
 * Trait KioskMethods
 *
 * @package App\Domains\Users\Models\Methods
 */
trait KioskMethods
{
    public function canAccessKiosk(): bool
    {
        return ! $this->isOnKiosk() && $this->hasKioskUserGroup();
    }

    public function isOnKiosk(): bool
    {
        return is_active(config('spoon.kiosk_prefix') . '.*');
    }

    public function hasKioskUserGroup(): bool
    {
        return in_array($this->user_group, config('spoon.access.kiosk'), true);
    }
}
