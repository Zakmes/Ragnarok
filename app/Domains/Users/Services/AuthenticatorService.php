<?php

namespace App\Domains\Users\Services;

use PragmaRX\Google2FALaravel\Exceptions\InvalidSecretKey;
use PragmaRX\Google2FALaravel\Support\Authenticator;

/**
 * Class AuthenticatorService
 *
 * @package App\Domains\Users\Services
 */
class AuthenticatorService extends Authenticator
{
    /**
     * User can pass without One time password token.
     *
     * @return bool
     */
    protected function canPassWithoutCheckingOTP(): bool
    {
        if (empty($this->getUser()->twoFactorAuth)) {
            return true;
        }

        return ! $this->getUser()->twoFactorAuth->google2fa_enable
           || ! $this->isEnabled()
           || $this->noUserIsAuthenticated()
           || $this->twoFactorAuthStillValid();
    }

    /**
     * Determine if the user can display the recovery view
     *
     * @return bool
     */
    public function canDisplayRecoveryView(): bool
    {
        return $this->isEnabled() && ! $this->isAuthenticated();
    }

    /**
     * Method for getting the Google 2FA token.
     *
     * @return string
     *
     * @throws InvalidSecretKey
     */
    protected function getGoogle2FASecretKey()
    {
        $secret = $this->getUser()->twoFactorAuth->{$this->config('otp_secret_column')};

        if ($this->hasAuthenticationSecret($secret)) {
            throw new InvalidSecretKey('Secret key cannot be empty.');
        }

        return $secret;
    }

    /**
     * Method for determining is the user has an authentication secret.
     *
     * @param  string|null $secret The authentication secret for the authenticated user.
     * @return string|null
     */
    public function hasAuthenticationSecret(?string $secret): ?string
    {
        return $secret === null || empty($secret);
    }
}
