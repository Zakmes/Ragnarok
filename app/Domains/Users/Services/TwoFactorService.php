<?php

namespace App\Domains\Users\Services;

use App\Domains\Users\Models\TwoFactorAuthentication;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PragmaRX\Google2FALaravel\Google2FA;

/**
 * Class TwoFactorService
 *
 * @todo Implement exception for when the module is not enabled.
 *
 * @package App\Domains\Users\Services
 */
class TwoFactorService
{
    /**
     * TwoFactorService constructor.
     *
     * @param  TwoFactorAuthentication $twoFactorModel The database model for the two factor information.
     * @return void
     */
    public function __construct(TwoFactorAuthentication $twoFactorModel)
    {
        $this->model = $twoFactorModel;
    }

    /**
     * Method for accessing the the underlying 2FA package.
     *
     * @return Google2FA
     */
    private function google2faLayer(): Google2FA
    {
        return app('pragmarx.google2fa');
    }

    /**
     * Method for creating the secret key during the configuration.
     *
     * @param  Request $request The request entity that contains all the request information.
     * @return TwoFactorAuthentication
     */
    public function createSecretKey(Request $request): TwoFactorAuthentication
    {
        return DB::transaction(function () use ($request): TwoFactorAuthentication {
            return $this->model->create([
                'user_id' => $request->user()->id,
                'google2fa_secret' => $this->google2faLayer()->generateSecretKey(),
                'google2fa_recovery_tokens' => $this->generateRecoveryTokens(),
                'google2fa_enable' => false,
            ]);
        });
    }

    /**
     * Method for generating recovery tokens for the 2FA system.
     *
     * @return string
     */
    public function generateRecoveryTokens()
    {
        return encrypt(json_encode(Collection::times(8, static function (): string {
            return Str::random(10) . '-' . Str::random(10);
        })));
    }

    /**
     * Method for regenerating the recovery tokens in the application.
     *
     * @param  User $user Resource entity from the authenticated user.
     * @return bool
     */
    public function regenerateRecoveryTokens(User $user): bool
    {
        return DB::transaction(function () use ($user): bool {
            return $user->twoFactorAuth->update(['google2fa_recovery_tokens' => $this->generateRecoveryTokens()]);
        });
    }

    /**
     * Get te url from the Google 2FA system.
     *
     * @param  Request $request The request instance that contains all the request information.
     * @return string|null
     */
    public function get2faUrl(Request $request): ?string
    {
        if ($request->user()->twoFactorAuth()->exists()) {
            $google2fa = app('pragmarx.google2fa');

            return $google2fa->getQrCodeInline(
                config('app.name'),
                $request->user()->email,
                $request->user()->twoFactorAuth->google2fa_secret
            );
        }

        return null;
    }

    /**
     * Method for disabling the 2FA configuration from the given user.
     *
     * @param  User $user The resource entity from the authenticated user.
     * @return void
     */
    public function disableTwoFactorAuthentication(User $user): void
    {
        $user->twoFactorAuth()->delete();
    }
}
