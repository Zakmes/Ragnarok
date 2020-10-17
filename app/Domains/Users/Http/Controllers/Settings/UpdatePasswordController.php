<?php

namespace App\Domains\Users\Http\Controllers\Settings;

use App\Domains\Users\Actions\UpdateAction;
use App\Domains\Users\DTO\UserPasswordObject;
use App\Domains\Users\Http\Requests\SecurityFormRequest;
use App\Domains\Users\Services\TwoFactorService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class UpdatePasswordController
 *
 * @package App\Domains\Users\Http\Controllers\Settings
 */
class UpdatePasswordController extends Controller
{
    /**
     * UpdatePasswordController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Method for displaying the security settings from the authenticated user.
     *
     * @param  Request          $request          The request instance that contains all the request information.
     * @param  TwoFactorService $twoFactorService The service layer for the two factor authentication.
     * @return Renderable
     */
    public function index(Request $request, TwoFactorService $twoFactorService): Renderable
    {
        return view('users.settings.security', ['qrCodeInline' => $twoFactorService->get2faUrl($request)]);
    }

    /**
     * Method for updating the account security settings in the application.
     *
     * @param  SecurityFormRequest $request      The request instance that contains all the request information.
     * @param  UpdateAction        $updateAction The action for updating the user information.
     * @return RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(SecurityFormRequest $request, UpdateAction $updateAction): RedirectResponse
    {
        $this->authorize('change-password', $request->user());

        $updateAction->execute($request->user(), UserPasswordObject::fromRequest($request)->only('password'));
        flash()->success(__('Your account security settings has been updated successfully.'));

        return back();
    }
}
