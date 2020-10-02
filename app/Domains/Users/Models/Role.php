<?php

namespace App\Domains\Users\Models;

use App\Domains\Users\Models\Relations\CreatorRelation;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRoleModel;

/**
 * Class Role
 *
 * @package App\Domains\Users\Models
 */
class Role extends SpatieRoleModel
{
    use CreatorRelation;
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return RoleFactory::new();
    }
}
