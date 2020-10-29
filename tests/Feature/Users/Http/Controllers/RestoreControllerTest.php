<?php

namespace Tests\Feature\Users\Http\Controllers;

use App\Domains\Users\Enums\GroupEnum;
use App\Domains\Users\Http\Controllers\RestoreController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Class RestoreControllerTest
 *
 * @package Tests\Feature\Users\Http\Controllers
 */
class RestoreControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function webmasterCanRestoreMarkedUsersForDeletion(): void
    {
        Permission::insert(['name' => 'restore-users', 'description' => 'The user can restore deleted user accounts', 'guard_name' => 'web']);
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER])->givePermissionTo('restore-users');
        $lena = User::factory()->create(['deleted_at' => now()]);

        $this->assertActionUsesMiddleware(RestoreController::class, '__invoke', ['auth', 'kiosk', '2fa']);

        $response = $this->actingAs($me)->get(kioskRoute('users.restore', $lena));
        $response->assertRedirect(kioskRoute('users.show', $lena));
        $response->assertSessionHas([
            'laravel_flash_message.class' => 'alert-success',
            'laravel_flash_message.level' => 'success',
            'laravel_flash_message.message' => __("The user account from :user is successfully restored", ['user' => $lena->name]),
        ]);

        $this->assertNull($lena->fresh()->deleted_at);
    }

    /** @test */
    public function userCanNotRestoreMarkedUsersForDeletion(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::USER]);
        $lena = User::factory()->create(['deleted_at' => now()]);

        $this->assertActionUsesMiddleware(RestoreController::class, '__invoke', ['auth', 'kiosk', '2fa']);

        $response = $this->actingAs($me)->from(kioskRoute('users.index'))->get(kioskRoute('users.restore', $lena));
        $response->assertRedirect(kioskRoute('users.index'));
        $response->assertSessionHas([
            'laravel_flash_message.class' => 'alert-danger',
            'laravel_flash_message.level' => 'error',
            'laravel_flash_message.message' => __("You are not authorized perform this handling."),
        ]);

        $this->assertNotNull($lena->fresh()->deleted_at);
    }
}
