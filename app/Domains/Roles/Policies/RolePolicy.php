<?php

namespace App\Domains\Roles\Policies;

use App\Domains\Users\Models\Role;
use App\User;

/**
 * Class RolePolicy
 *
 * @package App\Domains\Roles\Policies
 */
class RolePolicy
{
    /**
     * Determine whether the the authenticated user can view the role overview.
     *
     * @param  User $user Resource entity from the authenticated user.
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasKioskUserGroup() || $user->hasPermissionTo('view-roles');
    }

    /**
     * Determine whether the user can view the role overview or not.
     *
     * @param  User $user The resource entity from the authenticated user.
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasKioskUserGroup() || $user->hasPermissionTo('view-roles');
    }

    /**
     * Determine whether the user can create a user role in the application or not.
     *
     * @param  User $user The resource entity from the authenticated user.
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasKioskUserGroup() || $user->hasPermissionTo('create-roles');
    }

    /**
     * Determine whether the authenticated user can delete an permission role or not.
     *
     * @param  User $user The resource entity from the authenticated user.
     * @return bool
     */
    public function destroy(User $user): bool
    {
        return $user->hasKioskUserGroup() || $user->hasPermissionTo('delete-roles');
    }
}
