<?php

namespace App\Domains\Users\Http\Controllers\Settings;

use App\Domains\Users\Actions\UpdateAction;
use App\Domains\Users\DTO\UserInformationObject;
use App\Domains\Users\Http\Requests\InformationFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Class UpdateInformationController
 *
 * @package App\Domains\Users\Http\Controllers\Settings
 */
class UpdateInformationController extends Controller
{
    /**
     * UpdateInformationController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Method for update the user information in the application.
     *
     * @param  InformationFormRequest $request          The request instance that contains all the information and handles the validation.
     * @param  UpdateAction           $userUpdateAction The action that handles the account information update logic.
     * @return RedirectResponse
     */
    public function __invoke(InformationFormRequest $request, UpdateAction $userUpdateAction): RedirectResponse
    {
        $userUpdateAction->execute($request->user(), UserInformationObject::fromRequest($request)->except('password'));
        flash()->success(__('Your account information has been updated successfully.'));

        return back();
    }
}
