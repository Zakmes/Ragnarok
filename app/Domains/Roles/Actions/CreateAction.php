<?php

namespace App\Domains\Roles\Actions;

use App\Domains\Users\Actions\BaseAction;
use App\Domains\Users\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class CreateAction
 *
 * @package App\Domains\Roles\Actions
 */
class CreateAction extends BaseAction
{
    /**
     * Method for creating a new permission role in the application.
     *
     * @param  DataTransferObject $dataTransferObject   The data object that contains all the general info. For the group
     * @param  array|null         $permissions          The array with all the permissions for the permission role.
     * @return Role
     */
    public function execute(DataTransferObject $dataTransferObject, ?array $permissions = []): Role
    {
        return DB::transaction(function () use ($dataTransferObject, $permissions): Role {
            $role = $this->roleService->storeRole($dataTransferObject);
            $role->syncPermissions($permissions);

            return $role;
        });
    }
}
