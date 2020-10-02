<?php

namespace App\Domains\Roles\Actions;

use App\Domains\Roles\Events\RoleUpdated;
use App\Domains\Users\Actions\BaseAction;
use App\Domains\Users\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class UpdateAction
 *
 * @package App\Domains\Roles\Actions
 */
class UpdateAction extends BaseAction
{
    /**
     * Method for updating a permission role in the application.
     *
     * @param  Role               $role                 The resource entity from the given permission role
     * @param  DataTransferObject $dataTransferObject   The data object that contains all the general info. For the group
     * @param  array|null         $permissions          The array with all the permissions for the permission role.
     * @return bool
     */
    public function execute(Role $role, DataTransferObject $dataTransferObject, ?array $permissions = []): bool
    {
        return DB::transaction(function () use ($role, $dataTransferObject, $permissions): bool {
            if ($role->update($dataTransferObject->toArray()) && $role->syncPermissions($permissions)) {
                event(new RoleUpdated($role));
                return true;
            }

            return false;
        });
    }
}
