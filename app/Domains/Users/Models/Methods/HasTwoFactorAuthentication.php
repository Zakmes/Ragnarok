<?php

namespace App\Domains\Users\Models\Methods;

use App\Domains\Users\Models\TwoFactorAuthentication;
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
        return config('google2fa.enabled') && $this->twoFactorAuth->google2fa_enable;
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

    /**
     * Determine whether the user has given an valid 2FA recovery code during the recovery request.
     *
     * @param  string $recoveryCode The 2FA recovery code given by the user.
     * @return bool
     */
    public function recoveryCodeIsValid(string $recoveryCode): bool
    {
        $recoveryCodes = json_decode(decrypt($this->twoFactorAuth->google2fa_recovery_tokens), true);

        return in_array($recoveryCode, $recoveryCodes, true);
    }

    /**
     * Method for determining if the user is using the 2fa setup or not.
     *
     * @return bool
     */
    public function isUsingTwoFactorAuthentication(): bool
    {
        return config('google2fa.enabled') && $this->twoFactorAuth->google2fa_enable;
    }
}
