<?php

namespace App\Domains\Users\Http\Controllers;

use App\Domains\Users\Actions\CreateAction;
use App\Domains\Users\Actions\DeleteAction;
use App\Domains\Users\Actions\UpdateAction;
use App\Domains\Users\DTO\UserEntityObject;
use App\Domains\Users\Enums\DeleteType;
use App\Domains\Users\Enums\GroupEnum;
use App\Domains\Users\Http\Requests\InformationFormRequest;
use App\Domains\Users\Http\Requests\UpdateFormRequest;
use App\Domains\Users\Services\RoleService;
use App\Domains\Users\Services\UserService;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function kioskRoute;

/**
 * Class UsersController
 *
 * @package App\Domains\Users\Http\Controllers
 */
class UsersController extends Controller
{
    private UserService $userService;
    private RoleService $roleService;

    /**
     * UsersController constructor.
     *
     * @param  UserService $userService The business logic layer that is relation to the user management.
     * @param  RoleService $roleService The business logic layer that is related to the permission groups
     * @return void
     */
    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->middleware(['auth', 'kiosk', '2fa']);
        $this->authorizeResource(User::class, 'user');

        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * Method for displaying all the users in the application.
     *
     * @param  string|null $filter The filter you want to apply on your results.
     * @return Renderable
     */
    public function index(?string $filter = null): Renderable
    {
        return view('users.index')
            ->withFilter($filter)
            ->withGroupEnum(GroupEnum::class)
            ->withUsers($this->userService->getUsersBasedOnFilter($filter));
    }

    /**
     * Method for displaying the user information in the application.
     *
     * @param  User $user The database entity from the given user in the application.
     * @return Renderable
     */
    public function show(User $user): Renderable
    {
        return view('users.show', compact('user'));
    }

    /**
     * Method for displaying the edit view from an user.
     *
     * @param  User $user The database entity from the given user.
     * @return Renderable
     */
    public function edit(User $user): Renderable
    {
        return view('users.edit')
            ->withUserGroups($this->userService->getUserGroupArray())
            ->withRoles($this->roleService->getRolesForDropdown())
            ->withUser($user);
    }

    /**
     * Method for displaying the create view for a new user in the application.
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        $userGroups = $this->userService->getUserGroupArray();
        $roles = $this->roleService->getRolesForDropdown();

        return view('users.create', compact('userGroups', 'roles'));
    }

    /**
     * Method for registering a new user account in the application.
     *
     * @param  InformationFormRequest $request      The form request instance that handles the validation logic.
     * @param  CreateAction           $createAction
     * @return RedirectResponse
     */
    public function store(InformationFormRequest $request, CreateAction $createAction): RedirectResponse
    {
        $user = $createAction->execute(UserEntityObject::fromRequest($request), $request->role);
        flash()->success(__("The user account for :user is successfully created", ['user' => $user->name]));

        return back();
    }

    /**
     * Method for updating users in the resource storage.
     *
     * @param  UpdateFormRequest $updateFormRequest The form request class that handles the validation logic.
     * @param  User              $user              The resource entity from the given user.
     * @param  UpdateAction      $updateAction      The action that handles all business logic for the controller.
     * @return RedirectResponse
     */
    public function update(UpdateFormRequest $updateFormRequest, User $user, UpdateAction $updateAction): RedirectResponse
    {
        $dataTransferObject = $updateFormRequest->filled('password')
            ? UserEntityObject::fromRequest($updateFormRequest)
            : UserEntityObject::fromRequest($updateFormRequest)->except('password');

        $updateAction->execute($user, $dataTransferObject);
        flash()->success(__('The user is successfully updated in the application.'));

        return redirect(kioskRoute('users.show', $user));
    }

    /**
     * Method for deleting a user in the application.
     *
     * @param  Request      $request          The request instance that holds all the information from the request.
     * @param  User         $user             The resource entity from the given user.
     * @param  DeleteAction $userDeleteAction The action class that handles the deletion of the user account.
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Request $request, User $user, DeleteAction $userDeleteAction)
    {
        if ($request->isMethod('GET')) {
            return view('users.destroy', compact('user'));
        }

        $userDeleteAction->execute($user, DeleteType::MARK);
        flash()->success(__("The user account from {$user->name} is successfully marked for deletion."));

        return redirect(kioskRoute('users.index', ['filter' => 'deleted']));
    }
}
