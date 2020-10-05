<?php

namespace App\Domains\Users\Http\Controllers\Settings;

use App\Domains\Users\Actions\UpdateAction;
use App\Domains\Users\DTO\UserPasswordObject;
use App\Domains\Users\Http\Requests\SecurityFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

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
     * Method for updating the account security settings in the application.
     *
     * @param  SecurityFormRequest $request      The request instance that contains all the request information.
     * @param  UpdateAction        $updateAction The action for updating the user information.
     * @return RedirectResponse
     */
    public function __invoke(SecurityFormRequest $request, UpdateAction $updateAction): RedirectResponse
    {
        $this->authorize('change-password', $request->user());
        $updateAction->execute($request->user(), UserPasswordObject::fromRequest($request)->only('password'));
        flash()->success(__('Your account security settings has been updated successfully.'));

        return back();
    }
}
