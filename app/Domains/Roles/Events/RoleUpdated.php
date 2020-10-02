<?php

namespace App\Domains\Roles\Events;

use App\Domains\Users\Models\Role;

/**
 * Class RoleUpdated
 *
 * @package App\Domains\Roles\Events
 */
class RoleUpdated
{
    public Role $role;

    /**
     * RoleUpdated constructor.
     *
     * @param  Role $role The resource entity from the created role in the application.
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }
}
