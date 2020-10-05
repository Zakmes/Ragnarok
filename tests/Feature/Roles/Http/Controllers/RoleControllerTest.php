<?php

namespace Tests\Feature\Roles\Http\Controllers;

use App\Domains\Roles\Http\Controllers\RoleController;
use App\Domains\Roles\Http\Requests\StoreFormRequest;
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
        Role::factory()->count(5)->make();
        $permissions = Permission::factory()->count(5)->create();
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);

        $this->assertActionUsesMiddleware(RoleController::class, 'store', ['auth', 'kiosk']);
        $this->assertActionUsesFormRequest(RoleController::class, 'store', StoreFormRequest::class);

        $response = $this->actingAs($me)->post(kioskRoute('roles.store'), [
            'name' => 'Role name',
            'description' => 'Role description',
        ]);

        $response->assertRedirect(kioskRoute('roles.index'));
    }
}
