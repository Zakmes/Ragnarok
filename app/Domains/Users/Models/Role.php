<?php

namespace App\Domains\Users\Models;

use App\Domains\Users\Models\Relations\CreatorRelation;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
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
     * Method for defining the protected fields in a mass-assign statement.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Capitalize the role name.
     *
     * @param  string $roleName The name from the user permission role.
     * @return string
     */
    public function getNameAttribute(string $roleName): string
    {
        return ucfirst($roleName);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return RoleFactory::new();
    }
}
