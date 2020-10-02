<?php

namespace App\Domains\Roles\Events;

use App\Domains\Users\Models\Role;

/**
 * Class RoleDeleted
 *
 * @package App\Domains\Roles\Events
 */
class RoleDeleted
{
    public Role $role;

    /**
     * RoleDeleted constructor.
     *
     * @param  Role $role The resource entity from the created role in the application.
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }
}
