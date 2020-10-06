<?php

namespace Tests\Feature\Roles\Http\Controllers;

use App\Domains\Roles\Http\Controllers\RoleController;
use App\Domains\Roles\Http\Requests\StoreFormRequest;
use App\Domains\Roles\Http\Requests\UpdateFormRequest;
use App\Domains\Roles\Models\Permission;
use App\Domains\Users\Enums\GroupEnum;
use App\Domains\Users\Models\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class RoleControllerTest
 *
 * @package Tests\Feature\Roles\Http\Controllers
 */
class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function userCanSuccessFullyViewTheOverviewPage(): void
    {
        Role::factory()->count(4)->create();
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);

        $this->assertActionUsesMiddleware(RoleController::class, 'index', ['auth', 'kiosk']);

        $response = $this->actingAs($me)->get(kioskRoute('roles.index'));
        $response->assertSuccessful();
        $response->assertViewIs('roles.index');
    }

    /** @test */
    public function userCanViewTheRoleCreateView(): void
    {
        Permission::factory()->count('5')->create();
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);

        $this->assertActionUsesMiddleware(RoleController::class, 'create', ['auth', 'kiosk']);

        $response = $this->actingAs($me)->get(kioskRoute('roles.create'));
        $response->assertSuccessful();
        $response->assertViewIs('roles.create');
    }

    /** @test */
    public function userCanCreateAnNewRoleInTheApplication(): void
    {
        $permissions = Permission::factory()->count(5)->create();
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);

        $this->assertActionUsesMiddleware(RoleController::class, 'store', ['auth', 'kiosk']);
        $this->assertActionUsesFormRequest(RoleController::class, 'store', StoreFormRequest::class);

        $response = $this->actingAs($me)->post(kioskRoute('roles.store'), [
            'name' => 'Role name',
            'description' => 'Role description',
            'permission' => [$permissions[0]->name, $permissions[1]->name],
        ]);

        $response->assertRedirect(kioskRoute('roles.index'));
        $response->assertSessionHas([
            'laravel_flash_message.message' => __('The permission role is successfully saved.'),
            'laravel_flash_message.class' => 'alert-success',
            'laravel_flash_message.level' => 'success',
        ]);

        $role = Role::whereName('Role name')->first();

        $this->assertTrue($role->hasPermissionTo($permissions[0]->name));
        $this->assertTrue($role->hasPermissionTo($permissions[1]->name));
    }

    /** @test */
    public function userCanSuccessfullyViewTheRoleInformation(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);
        $role = Role::factory()->create();

        $this->assertActionUsesMiddleware(RoleController::class, 'show', ['kiosk', 'web']);

        $response = $this->actingAs($me)->get(kioskRoute('roles.show', $role));
        $response->assertSuccessful();
        $response->assertViewIs('roles.show');
    }

    /** @test */
    public function userCanViewTheEditForm(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);
        $role = Role::factory()->create();

        $this->assertActionUsesMiddleware(RoleController::class, 'edit', ['kiosk', 'web']);

        $response = $this->actingAs($me)->get(kioskRoute('roles.edit', $role));
        $response->assertSuccessful();
        $response->assertViewIs('roles.edit');
    }

    /** @test */
    public function userCanUpdateAnRoleInTheApplication(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);
        $permissions = Permission::factory()->count(4)->create();

        $role = Role::factory()->create();
        $role->syncPermissions([$permissions[0]->name, $permissions[1]->name]);

        $this->assertTrue($role->hasPermissionTo($permissions[0]->name));
        $this->assertTrue($role->hasPermissionTo($permissions[1]->name));

        $this->assertActionUsesMiddleware(RoleController::class, 'update', ['auth', 'kiosk']);
        $this->assertActionUsesFormRequest(RoleController::class, 'update', UpdateFormRequest::class);

        $response = $this->actingAs($me)->patch(kioskRoute('roles.update', $role), [
            'name' => 'new-role-name',
            'description' => 'new-description',
            'permission' => [$permissions[2]->name, $permissions[3]->name]
        ]);

        $response->assertRedirect(kioskRoute('roles.edit', $role));
    }

    /** @test */
    public function userCanDeleteAnPermissionRole(): void
    {
        $role = Role::factory()->create();
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);

        $response = $this->actingAs($me)->get(kioskRoute('roles.destroy', $role));
        $response->assertRedirect(kioskRoute('roles.index'));
        $response->assertSessionHas([
            'laravel_flash_message.level' => 'success',
            'laravel_flash_message.class' => 'alert-success',
            'laravel_flash_message.message' => __('The permission role is successfully deleted in the application.'),
        ]);
    }
}
