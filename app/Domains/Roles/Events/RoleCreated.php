<?php

namespace App\Domains\Roles\Events;

use App\Domains\Users\Models\Role;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RoleCreated
 *
 * @package App\Domains\Roles\Events
 */
class RoleCreated
{
    public Model $role;

    /**
     * RoleCreated constructor.
     *
     * @param  Model $role The resource entity from the created role in the application.
     * @return void
     */
    public function __construct(Model $role)
    {
        $this->role = $role;
    }
}
