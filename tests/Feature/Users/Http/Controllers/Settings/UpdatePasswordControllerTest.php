<?php

namespace Tests\Feature\Users\Http\Controllers\Settings;

use App\Domains\Users\Enums\GroupEnum;
use App\Domains\Users\Http\Controllers\Settings\UpdatePasswordController;
use App\Domains\Users\Http\Requests\SecurityFormRequest;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Class UpdatePasswordControllerTest
 *
 * @package Tests\Feature\Users\Http\Controllers\Settings
 */
class UpdatePasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canAccessSuccessfullyThePasswordSettingsViewAuthenticated(): void
    {
        $me = User::factory()->make();

        $this->actingAs($me)
            ->get(route('account.security.patch'))
            ->assertViewIs('users.settings.security')
            ->isSuccessful();
    }

    /** @test */
    public function cannotAccessThePasswordSettingsViewWhenNotAuthenticated(): void
    {
        $this->get(route('account.security.patch'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function canSuccessfullyChangeThePasswordInTheApplication(): void
    {
        Permission::insert(['name' => 'change-password', 'description' => 'The user can change password from other users', 'guard_name' => 'web']);
        $me = User::factory()->create(['password' => $password = 'current-password', 'user_group' => GroupEnum::WEBMASTER])->givePermissionTo('change-password');
        $requestData = ['currentPassword' => $password, 'password' => 'new-password', 'password_confirmation' => 'new-password'];

        $this->assertActionUsesMiddleware(UpdatePasswordController::class, '__invoke', 'auth');
        $this->assertActionUsesFormRequest(UpdatePasswordController::class, '__invoke', SecurityFormRequest::class);

        $this->actingAs($me)
            ->patch(route('account.security.patch', $requestData))
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasAll([
                'laravel_flash_message.class' => 'alert-success',
                'laravel_flash_message.level' => 'success',
                'laravel_flash_message.message' => __('Your account security settings has been updated successfully.')
            ]);

        $this->assertTrue(Hash::check('new-password', $me->fresh()->password));
    }
}
