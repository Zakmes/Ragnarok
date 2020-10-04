<?php

namespace App\Domains\Users\Policies;

use App\User;

/**
 * Class UserPolicy
 *
 * @package App\Domains\Users\Policies
 */
class UserPolicy
{
    /**
     * Determine whether the authenticated user can delete an other user or not.
     *
     * @param  User $user The resource entity from the authenticated user.
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasKioskUserGroup() || $user->hasPermissionTo('delete-users');
    }

    /**
     * Determine whether the authenticated user can view other users or not.
     *
     * @param  User $user The resource entity from the authenticated user.
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasKioskUserGroup() || $user->hasKioskUserGroup() && $user->hasPermissionTo('view-users');
    }

    /**
     * Determine whether the user can create new users in the application or not.
     *
     * @param  User $user  The resource entity from the authenticated user.
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasKioskUserGroup() || $user->hasKioskUserGroup() && $user->hasPermissionTo('create-users');
    }

    /**
     * Determine whether the authenticated user can update other users or not.
     *
     * @param  User $user  The resource entity from the authenticated user.
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->hasKioskUserGroup() || $user->hasKioskUserGroup() && $user->hasPermissionTo('update-users');
    }

    /**
     * Determine whether the authenticated user can lock other user accounts.
     *
     * @param  User $user  The resource entity from the authenticated user.
     * @param  User $model The resource entity from the given user.
     * @return bool
     */
    public function lock(User $user, User $model): bool
    {
        return $user->hasKioskUserGroup() && $model->isNotBanned()
            || $user->hasKioskUserGroup() && $user->hasPermissionTo('lock-users');
    }

    /**
     * Determine whether the authenticated user can unlock an user account or not.
     *
     * @param  User $user  The resource entity from the authenticated user.
     * @param  User $model The resource entity from the given user.
     * @return bool
     */
    public function unlock(User $user, User $model): bool
    {
        return $user->hasKioskUserGroup()
            && $user->hasPermissionTo('lock-users')
            && $model->isBanned();
    }

    /**
     * Determine whether the authenticated user can restore a 'deleted' user account or not.
     *
     * @param  User $user  The resource entity from the authenticated user.
     * @param  User $model The resource entity from the given user.
     * @return bool
     */
    public function restore(User $user, User $model): bool
    {
        return $model->trashed()
            && $user->hasKioskUserGroup()
            || $user->hasKioskUserGroup() && $user->hasPermissionTo('restore-users');
    }

    /**
     * Determine whether the user can change passwords from other users or not.
     *
     * @param  User $user The resource entity from the authenticated user.
     * @return bool
     */
    public function changePassword(User $user): bool
    {
        return $user->hasKioskUserGroup() && $user->hasPermissionTo('change-password');
    }
}
