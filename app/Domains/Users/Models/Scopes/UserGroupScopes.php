<?php

namespace App\Domains\Users\Models\Scopes;

use App\Domains\Users\Enums\GroupEnum;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait UserGroupScopes
 *
 * @package App\Domains\Users\Models\Scopes
 */
trait UserGroupScopes
{
    /**
     * Scope for getting all the users in the webmasters user group.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWebmasters(Builder $query): Builder
    {
        return $query->where('user_group', GroupEnum::WEBMASTER);
    }

    /**
     * Scope for getting all the users in the default user group.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsers(Builder $query): Builder
    {
        return $query->where('user_group', GroupEnum::USER);
    }

    /**
     * Scope for getting all the users in the developers user group.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDevelopers(Builder $query): Builder
    {
        return $query->where('user_group', GroupEnum::DEVELOPER);
    }
}
