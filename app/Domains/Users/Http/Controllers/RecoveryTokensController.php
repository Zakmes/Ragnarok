<?php

namespace App\Domains\Users\Http\Controllers;

use App\Domains\Users\Services\TwoFactorService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class RecoveryTokensController
 *
 * @package App\Domains\Users\Http\Controllers
 */
class RecoveryTokensController extends Controller
{
    private TwoFactorService $twoFactorService;

    /**
     * RecoveryTokensController constructor.
     *
     * @param  TwoFactorService $twoFactorAuthenticationService The business logic layer for the twofactor authentication.
     * @return void
     */
    public function __construct(TwoFactorService $twoFactorAuthenticationService)
    {
        $this->middleware('auth');
        $this->twoFactorService = $twoFactorAuthenticationService;
    }

    /**
     * Method for regenerating the two factor authentication recovery tokens.
     *
     * @param  Request $request The request entity that contains all the request information.
     * @return RedirectResponse
     */
    public function regenerate(Request $request): RedirectResponse
    {
        if ($request->user()->isUsingTwoFactorAuthentication()) {
            $this->twoFactorService->regenerateRecoveryTokens($request->user());
            flash()->success(__('The recovery tokens for your 2FA are successfully regenerated.'));
            session()->flash('recoveryTokens', json_decode(decrypt($request->user()->twoFactorAuth->google2fa_recovery_tokens, true)));
        }

        return redirect()->route('account.security');
    }
}
