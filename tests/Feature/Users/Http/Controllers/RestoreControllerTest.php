<?php

namespace Tests\Feature\Users\Http\Controllers;

use App\Domains\Users\Enums\GroupEnum;
use App\Domains\Users\Http\Controllers\RestoreController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $me = User::factory()->make(['user_group' => GroupEnum::WEBMASTER]);
        $lena = User::factory()->create(['deleted_at' => now()]);

        $this->assertActionUsesMiddleware(RestoreController::class, '__invoke', ['auth', 'kiosk']);

        $response = $this->actingAs($me)->get(kioskRoute('users.restore', $lena));
        $response->assertRedirect(kioskRoute('users.show', $lena));
        $response->assertSessionHas([
            'laravel_flash_message.class' => 'alert-success',
            'laravel_flash_message.level' => 'success',
            'laravel_flash_message.message' => __("The user account from :user is successfully restored", ['user' => $lena->name]),
        ]);
    }
}
