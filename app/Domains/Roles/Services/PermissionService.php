<?php

namespace App\Domains\Roles\Services;

use App\Domains\Roles\Models\Permission;
use App\Support\Services\BaseService;

/**
 * Class PermissionService
 *
 * @package App\Domains\Roles\Services
 */
class PermissionService extends BaseService
{
    /**
     * PermissionService constructor.
     *
     * @param  Permission $permission The database model for all the permissions in the application.
     * @return void
     */
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }
}
