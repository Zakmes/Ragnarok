<?php

namespace App\Domains\Roles\Http\Controllers;

use App\Domains\Roles\Actions\CreateAction;
use App\Domains\Roles\Actions\UpdateAction;
use App\Domains\Roles\DTO\RoleDataTransferObject;
use App\Domains\Roles\Events\RoleDeleted;
use App\Domains\Roles\Http\Requests\StoreFormRequest;
use App\Domains\Roles\Http\Requests\UpdateFormRequest;
use App\Domains\Roles\Services\PermissionService;
use App\Domains\Users\Models\Role;
use App\Domains\Users\Services\RoleService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use function kioskRoute;

/**
 * Class RoleController
 *
 * @package App\Domains\Roles\Http\Controllers
 */
class RoleController extends Controller
{
    private RoleService $roleService;
    private PermissionService $permissionService;

    /**
     * RoleController constructor.
     *
     * @param  RoleService       $roleService       The role service logic layer.
     * @param  PermissionService $permissionService The permission service logic layer.
     * @return void
     */
    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->middleware(['auth', 'kiosk']);
        $this->authorizeResource(Role::class, 'role');

        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    /**
     * Method for displaying all the user permission roles in the application.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('roles.index', ['roles' => $this->roleService->paginate(15)]);
    }

    /**
     * Method to creating a new user permission role in the application.
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        return view('roles.create')
            ->withUserPermissions($this->permissionService->where('name', '%-users%', 'LIKE')->get())
            ->withRolePermissions($this->permissionService->where('name', '%-roles%', 'like')->get())
            ->withApiTokenPermissions($this->permissionService->where('name', '%-tokens%', 'LIKE')->get());
    }

    /**
     * Method for storing the new permission role in the application.
     *
     * @param  StoreFormRequest $request      THe request instance that contains all the request information.
     * @param  CreateAction     $createAction The action for creating te new permission role in the application.
     * @return RedirectResponse
     */
    public function store(StoreFormRequest $request, CreateAction $createAction): RedirectResponse
    {
        $createAction->execute(RoleDataTransferObject::fromRequest($request), $request->get('permission'));
        flash()->success(__('The permission role is successfully saved.'));

        return redirect(kioskRoute('roles.index'));
    }

    /**
     * Method for displaying a permission role in the application.
     *
     * @param  Role $role The resource entity from the role in the application.
     * @return Renderable
     */
    public function show(Role $role): Renderable
    {
        return view('roles.show', compact('role'));
    }

    /**
     * Method for displayin the edit view for the role in the application.
     *
     * @param  Role $role The resource entity from the given role.
     * @return Renderable
     */
    public function edit(Role $role): Renderable
    {
        return view('roles.edit')
            ->withRole($role)
            ->withUserPermissions($this->permissionService->where('name', '%-users%', 'LIKE')->get())
            ->withRolePermissions($this->permissionService->where('name', '%-roles%', 'LIKE')->get())
            ->withApiTokenPermissions($this->permissionService->where('name', '%-tokens%', 'LIKE')->get());
    }

    /**
     * Method for updating the permission role in the application.
     *
     * @param  UpdateFormRequest $request       The request instance that contains all the request information.
     * @param  Role              $role          The resource entity from the permission role.
     * @param  UpdateAction      $updateAction  The business logic layer for updating the role.
     * @return RedirectResponse
     */
    public function update(UpdateFormRequest $request, Role $role, UpdateAction $updateAction): RedirectResponse
    {
        $updateAction->execute($role, RoleDataTransferObject::fromRequest($request), $request->permission);

        return redirect(kioskRoute('roles.edit', $role));
    }

    /**
     * Method for deleting permission roles in the application.
     *
     * @param  Role $role The resource entity for the permission role in the application.
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        event(new RoleDeleted($role));
        flash()->success(__('The permission role is successfully deleted in the application.'));

        return redirect(kioskRoute('roles.index'));
    }
}
