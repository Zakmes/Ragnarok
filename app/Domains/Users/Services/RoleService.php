<?php

namespace App\Domains\Users\Services;

use App\Domains\Roles\Events\RoleCreated;
use App\Domains\Users\Models\Role;
use App\Support\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class RoleService
 *
 * @package App\Domains\Users\Services
 */
class RoleService extends BaseService
{
    /**
     * RoleService constructor.
     *
     * @param  Role $roleModel
     * @return void
     */
    public function __construct(Role $roleModel)
    {
        $this->model = $roleModel;
    }

    /**
     * @return array
     */
    public function getRolesForDropdown(): array
    {
        return $this->model->pluck('name', 'name')->toArray();
    }

    /**
     * @param  DataTransferObject $dataTransferObject
     * @return Role
     */
    public function storeRole(DataTransferObject $dataTransferObject): Model
    {
        if ($role = $this->model->create($dataTransferObject->toArray())) {
            event(new RoleCreated($role));
        }

        return $role;
    }
}
