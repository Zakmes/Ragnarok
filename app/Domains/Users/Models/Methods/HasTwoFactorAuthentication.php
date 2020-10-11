<?php

namespace App\Domains\Users\Models\Methods;

use App\Domains\Users\Models\TwoFactorAuthentication;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Trait HasTwoFactorAuthentication
 *
 * @package App\Domains\Users\Models\Methods
 */
trait HasTwoFactorAuthentication
{
    /**
     * Determine whether the user is using 2FA or not.
     *
     * @return bool
     */
    public function hasTwoFactorAuthEnabled(): bool
    {
        return config('google2fa.enabled') && $this->twoFactorAuth()->exists();
    }

    /**
     * Determine if the authenticated user had disabled 2FA.
     *
     * @return bool
     */
    public function hasTwoFactorDisabled(): bool
    {
        return ! $this->hasTwoFactorAuthEnabled();
    }

    /**
     * Method for determining whether the user can setup 2FA.
     *
     * @return bool
     */
    public function canSetupTwoFactorAuthentication(): bool
    {
        return config('google2fa.enabled') && empty($this->twoFactorAuth);
    }

    /**
     * The data relation for the Two factor authentication.
     *
     * @return HasOne
     */
    public function twoFactorAuth(): HasOne
    {
        return $this->hasOne(TwoFactorAuthentication::class);
    }
}
