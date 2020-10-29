<?php

namespace App\Domains\Users\Actions;

use App\User;

/**
 * Class EnableTwoFactorAuth
 *
 * @package App\Domains\Users\Actions
 */
class EnableTwoFactorAuth extends BaseAction
{
    /**
     * Action that enables the Two Factor authentication successfully in the application.
     *
     * @param  User   $user             The resource entoty from the authenticated user.
     * @param  string $verificationCode The verification code given by the user.
     * @return bool
     */
    public function execute(User $user, string $verificationCode): bool
    {
        if ($this->TwofactorKeyIsVerfied($user, $verificationCode)) {
            $user->twoFactorAuth->update(['google2fa_enable' => true]);

            return true;
        }

        return false;
    }

    /**
     * Method for verification of the Google Authenticator app key.
     *
     * @param  User   $user             The resource entity from the authenticated user.
     * @param  string $verificationCode The verification code given by the user.
     * @return bool
     */
    private function TwofactorKeyIsVerfied(User $user, string $verificationCode): bool
    {
        return app('pragmarx.google2fa')
            ->verifyKey($user->twoFactorAuth->google2fa_secret, $verificationCode);
    }
}
