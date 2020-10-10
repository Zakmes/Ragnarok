<?php

namespace Tests\Feature\Api\Http\Controllers\Web;

use App\Domains\Api\Http\Controllers\Web\TokenRestoreController;
use App\Domains\Api\Models\PersonalAccessToken;
use App\Domains\Roles\Models\Permission;
use App\Domains\Users\Enums\GroupEnum;
use App\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class TokenRestoreControllerTest
 *
 * @package Tests\Feature\Api\Http\Controllers\Web
 */
class TokenRestoreControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function AdministratorUserCanRestoreAnToken(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);
        $token = PersonalAccessToken::factory()->revoked()->create();

        Permission::create(['name' => 'restore-tokens', 'description' => 'The user can restore revoked personal access tokens', 'guard_name' => 'web']);

        $this->assertActionUsesMiddleware(TokenRestoreController::class, '__invoke', ['auth', 'kiosk']);

        $response = $this->actingAs($me)->get(kioskRoute('api-management.restore', ['trashedToken' => $token]));
        $response->assertRedirect(kioskRoute('api-management.index'));
        $response->assertSessionHas([
            'laravel_flash_message.class' => 'alert-success',
            'laravel_flash_message.level' => 'success',
            'laravel_flash_message.message' => __('The personal access token is successfully reactivated.')
        ]);
    }
}
