<?php

namespace Tests\Feature\Users\Http\Controllers;

use App\Domains\Users\Enums\GroupEnum;
use App\Domains\Users\Http\Controllers\LockController;
use App\Domains\Users\Http\Requests\LockFormRequest;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Class LockControllerTest
 *
 * @package Tests\Feature\Users\Http\Controllers
 */
class LockControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Method for creating a banned user in the application.
     *
     * @return User
     */
    private function createBannedUser(): User
    {
        $user = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);

        $this->actingAs($user);
        $user->ban(['comment' => 'meh', 'expired_at' => null]);

        $this->assertTrue($user->refresh()->isBanned());
        auth()->logout();

        return $user;
    }

    /**
     * Implement all the needed permissions for the tests.
     *
     * @return void
     */
    private function seedPermissions(): void
    {
        Permission::insert([
            ['name' => 'lock-users', 'description' => 'The user can deactivate other users.', 'guard_name' => 'web'],
            ['name' => 'unlock-users', 'description' => 'The user can reactivate deactivated users.', 'guard_name' => 'web'],
        ]);
    }

    /** @test */
    public function canViewTheBlockedInformationErrorPage(): void
    {
        $this->assertActionUsesMiddleware(LockController::class, 'index', 'auth');
        $user = $this->createBannedUser();

        $response = $this->actingAs($user->refresh())->get(kioskRoute('users.lock.error'));
        $response->assertSuccessful();
        $response->assertViewIs('errors.deactivated');
    }

    /** @test */
    public function canSuccessFullyDisplayTheDeactivationScreen(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);
        $lena = User::factory()->create();

        $this->assertActionUsesMiddleware(LockController::class, 'create', ['auth', 'kiosk']);

        $response = $this->actingAs($me)->get(kioskRoute('users.lock', $lena));
        $response->assertSuccessful();
        $response->assertViewIs('users.lock');
    }

    /** @test */
    public function anUserCanBeDeactivatedInTheApplication(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);
        $lena = User::factory()->create();

        $this->assertActionUsesMiddleware(LockController::class, 'store', ['auth', 'kiosk']);
        $this->assertActionUsesFormRequest(LockController::class, 'store', LockFormRequest::class);

        $response = $this->actingAs($me)->post(kioskRoute('users.lock', $lena), ['reason' => 'Deactivation reason.']);
        $response->assertRedirect(kioskRoute('users.show', $lena));
        $response->assertSessionHas([
            'laravel_flash_message.class' => 'alert-success',
            'laravel_flash_message.level' => 'success',
            'laravel_flash_message.message' =>  __(':user is successfully locked in the :application.', [
               'application' => config('app.name'), 'user' => $lena->name
            ])
        ]);
    }

    /** @test */
    public function deactivationReasonIsRequired(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);
        $lena = User::factory()->create();

        $this->assertActionUsesMiddleware(LockController::class, 'store', ['auth', 'kiosk']);
        $this->assertActionUsesFormRequest(LockController::class, 'store', LockFormRequest::class);

        $response = $this->actingAs($me)->from(kioskRoute('users.lock', $lena))->post(kioskRoute('users.lock', $lena), []);
        $response->assertRedirect(kioskRoute('users.lock', $lena));
        $response->assertSessionHasErrors('reason');
    }

    /** @test */
    public function reactivationFromAUserCanBeExecutedSuccessfully(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);
        $lena = $this->createBannedUser();

        $this->seedPermissions();
        $this->assertActionUsesMiddleware(LockController::class, 'destroy', ['auth', 'kiosk']);

        $response = $this->actingAs($me)->get(kioskRoute('users.unlock', $lena));
        $response->assertStatus(Response::HTTP_FOUND);
    }
}
