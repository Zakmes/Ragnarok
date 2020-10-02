<?php

namespace Database\Seeders;

use App\Support\Database\DisableForeignKeys;
use App\Support\Database\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    use DisableForeignKeys;
    use TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys();

        Artisan::call('cache:clear');
        resolve(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->truncateMultiple([
            config('permission.table_names.model_has_permissions'),
            config('permission.table_names.role_has_permissions'),
            config('permission.table_names.permissions'),
        ]);

        // Seeding groups
        $this->seedUserManagementPermissions();
        $this->seedRoleManagementPermissions();
        $this->seedPersonalAccessTokenPermissions();

        $this->enableForeignKeys();
    }

    private function seedUserManagementPermissions(): void
    {
        Permission::insert([
            ['name' => 'create-users', 'description' => 'The user can create new users in the application', 'guard_name' => 'web'],
            ['name' => 'delete-users', 'description' => 'The user can delete other users in the application.', 'guard_name' => 'web'],
            ['name' => 'view-users', 'description' => 'The user can view other users in the application.', 'guard_name' => 'web'],
            ['name' => 'lock-users', 'description' => 'The user can deactivate other users.', 'guard_name' => 'web'],
            ['name' => 'unlock-users', 'description' => 'The user can reactivate deactivated users.', 'guard_name' => 'web'],
            ['name' => 'update-users', 'description' => 'The user can update the information from other users.', 'guard_name' => 'web'],
            ['name' => 'restore-users', 'description' => 'The user can restore deleted user accounts', 'guard_name' => 'web'],
            ['name' => 'change-passwords', 'description' => 'The user can change password from other users', 'guard_name' => 'web'],
        ]);
    }

    private function seedRoleManagementPermissions(): void
    {
        Permission::insert([
            ['name' => 'view-roles', 'description' => 'The user can view all the permission roles in the application', 'guard_name' => 'web'],
            ['name' => 'create-roles', 'description' => 'The user can create new user group roles in the application', 'guard_name' => 'web'],
            ['name' => 'delete-roles', 'description' => 'The user can delete permission roles.', 'guard_name' => 'web'],
        ]);
    }

    private function seedPersonalAccessTokenPermissions(): void
    {
        Permission::insert([
            ['name' => 'revoke-tokens', 'description' => 'The user can revoke tokens from other users.', 'guard_name' => 'web'],
            ['name' => 'overview-tokens', 'description' => 'The user can view all the personal access tokens', 'guard_name' => 'web'],
            ['name' => 'restore-tokens', 'description' => 'The user can restore revoked personal access tokens', 'guard_name' => 'web'],
        ]);
    }
}
