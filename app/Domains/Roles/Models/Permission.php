<?php

namespace App\Domains\Roles\Models;

use Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as PermissionModel;

/**
 * Class Permission
 *
 * @package App\Domains\Roles\Model
 */
class Permission extends PermissionModel
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return PermissionFactory::new();
    }
}
