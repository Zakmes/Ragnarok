<?php

namespace App\Domains\Users\Http\Controllers;

use App\Domains\Users\Http\Requests\RecoveryTokenFormRequest;
use App\Domains\Users\Services\TwoFactorService;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * Class TwoFactorRecoveryController
 *
 * @package App\Domains\Users\Http\Controllers
 */
class TwoFactorRecoveryController extends Controller
{
    private TwoFactorService $twoFactorService;

    /**
     * TwoFactorRecoveryController constructor.
     *
     * @param  TwoFactorService $twoFactorService The business logic layer for the 2FA service.
     * @return void
     */
    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->middleware(['auth', '2fa-recovery']);
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Method for displaying the two factor authentication recovery view.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('auth.2fa-recovery');
    }

    /**
     * Method for recover your account when the 2FA has been lost.
     *
     * @param  RecoveryTokenFormRequest $request The request instance that contains all the request information.
     * @return RedirectResponse
     */
    public function update(RecoveryTokenFormRequest $request): RedirectResponse
    {
        $this->twoFactorService->disableTwoFactorAuthentication($request->user());
        flash()->success(__('You have successfully recovered your account. Not that 2FA is also disabled. Visit your account security settings if you want to enable 2FA'));

        return redirect()->to(RouteServiceProvider::HOME);
    }
}
