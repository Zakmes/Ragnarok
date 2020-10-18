<?php

namespace App\Domains\Users\Http\Controllers;

use App\Domains\Users\Actions\EnableTwoFactorAuth;
use App\Domains\Users\Http\Requests\TwoFactorDisableRequest;
use App\Domains\Users\Services\TwoFactorService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;

/**
 * Class TwoFactorAuthController
 *
 * @todo Controller needs testing
 * @package App\Domains\Users\Http\Controllers
 */
class TwoFactorAuthController extends Controller
{
    private TwoFactorService $twoFactorAuthenticationService;

    /**
     * TwoFactorAuthController constructor.
     *
     * @param  TwoFactorService $twoFactorAuthenticationService The service layer for all the logic that is related to the 2FA.
     * @return void
     */
    public function __construct(TwoFactorService $twoFactorAuthenticationService)
    {
        $this->middleware(['auth', '2fa']);
        $this->twoFactorAuthenticationService = $twoFactorAuthenticationService;
    }

    /**
     * Method for generating the 2FA secret authenticator code.
     *
     * @param  Request $request The request entity that contains all the request information.
     * @return RedirectResponse
     */
    public function generate2faSecret(Request $request): RedirectResponse
    {
        $route = redirect()->route('account.security');

        try {
            $this->twoFactorAuthenticationService->createSecretKey($request);
            return $route->with('success', __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application.'));
        } catch (IncompatibleWithGoogleAuthenticatorException $incompatibleWithGoogleAuthenticatorException) {
            return $route->with('error', __($incompatibleWithGoogleAuthenticatorException->getMessage()));
        } catch (InvalidCharactersException $invalidCharactersException) {
            return $route->with('error', __($invalidCharactersException->getMessage()));
        }
    }

    /**
     * Method for enabling the Two factor auth on the user account.
     *
     * @param  Request              $request                    The request instance that contains all the request information.
     * @param  EnableTwoFactorAuth  $enableTwoFactorAuthAction  Method that enabled the Two Factor auth in the application.
     * @return RedirectResponse
     */
    public function enable2fa(Request $request, EnableTwoFactorAuth $enableTwoFactorAuthAction): RedirectResponse
    {
        $redirect = redirect()->route('account.security');

        if ($enableTwoFactorAuthAction->execute($request->user(), $request->get('verify-code'))) {
            flash()->success(__('2FA is successfully enabled for your account.'));
            session()->flash('recoveryTokens', json_decode(decrypt($request->user()->twoFactorAuth->google2fa_recovery_tokens, true)));

            return $redirect;
        }

        return $redirect->with('error', __('Invalid authenticator app code. Please try again'));
    }

    /**
     * Method that disables the two factor for the authenticated user.
     *
     * @param  TwoFactorDisableRequest $request The request entity that contains all the request information.
     * @return RedirectResponse
     */
    public function disable2fa(TwoFactorDisableRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isUsingTwoFactorAuthentication()) {
            $user->twoFactorAuth->disable();
            flash()->success(__('Two factor is disabled on your account.'));
        }

        return redirect()->route('account.security');
    }
}
