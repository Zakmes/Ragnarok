<?php

namespace Tests\Feature\Api\Http\Controllers\Web;

use App\Domains\Api\Http\Controllers\Web\UserTokensController;
use App\Domains\Api\Http\Requests\CreateFormRequest;
use App\Domains\Api\Models\PersonalAccessToken;
use App\Domains\Roles\Models\Permission;
use App\Domains\Users\Enums\GroupEnum;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class UserTokensControllerTest
 *
 * @package Tests\Feature\Api\Http\Controllers\Web
 */
class UserTokensControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Insert all the needed permissions for the module in the database.
     *
     * @return void
     */
    private function seedPermissions(): void
    {
        Permission::insert([
            ['name' => 'revoke-tokens', 'description' => 'The user can revoke tokens from other users.', 'guard_name' => 'web'],
            ['name' => 'overview-tokens', 'description' => 'The user can view all the personal access tokens', 'guard_name' => 'web'],
            ['name' => 'restore-tokens', 'description' => 'The user can restore revoked personal access tokens', 'guard_name' => 'web'],
        ]);
    }

    /** @test */
    public function userCanSuccessfullyViewTheOverviewPage(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);

        PersonalAccessToken::factory()->count(6)->create();

        $this->seedPermissions();
        $this->assertActionUsesMiddleware(UserTokensController::class, 'index', ['auth', 'kiosk']);

        $response = $this->actingAs($me)->get(kioskRoute('api-management.index'));
        $response->assertSuccessful();
        $response->assertViewIs('api.index');
    }

    /** @test */
    public function userCanViewTheRevokedTokensOverview(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);

        PersonalAccessToken::factory()->revoked()->count(6)->create();

        $this->seedPermissions();
        $this->assertActionUsesMiddleware(UserTokensController::class, 'index', ['web', 'kiosk']);

        $response = $this->actingAs($me)->get(kioskRoute('api-management.index', ['filter' => 'revoked']));
        $response->assertSuccessful();
        $response->assertViewIs('api.index');
    }

    /** @test */
    public function AuthenticatedUserCanOverviewHisPersonalAccessTokens(): void
    {
        $me = User::factory()->create();
        $me->createToken('test-token');

        $this->assertActionUsesMiddleware(UserTokensController::class, 'show', ['auth']);

        $response = $this->actingAs($me)->get(route('account.tokens'));
        $response->assertSuccessful();
        $response->assertViewIs('api.user-screen');
    }

    /** @test */
    public function authenticatedUserCanRevokeHisToken(): void
    {
        $me = User::factory()->create();
        $token = $me->createToken('test-token');

        $this->seedPermissions();
        $this->assertActionUsesMiddleware(UserTokensController::class, 'revoke', ['auth']);

        $response = $this->actingAs($me)->get(route('account.tokens.revoke', $token->accessToken));
        $response->assertRedirect(url('/'));
        $response->assertSessionHas([
            'laravel_flash_message.class' => 'alert-success',
            'laravel_flash_message.level' => 'success',
            'laravel_flash_message.message' => __('The personal access token is successfully revoked'),
        ]);
    }

    /** @test */
    public function anAdministratorCanRevokeAToken(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);

        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $this->seedPermissions();
        $this->assertActionUsesMiddleware(UserTokensController::class, 'revoke', ['auth']);

        $response = $this->actingAs($me)->get(route('account.tokens.revoke', $token->accessToken));
        $response->assertRedirect(url('/'));
        $response->assertSessionHas([
            'laravel_flash_message.class' => 'alert-success',
            'laravel_flash_message.level' => 'success',
            'laravel_flash_message.message' => __('The personal access token is successfully revoked'),
        ]);
    }

    /** @test */
    public function authenticatedUserCanIssueANewPersonalAccessToken(): void
    {
        $me = User::factory()->create();

        $this->assertActionUsesMiddleware(UserTokensController::class, 'store', ['auth']);
        $this->assertActionUsesFormRequest(UserTokensController::class, 'store', CreateFormRequest::class);

        $response = $this->actingAs($me)->post(route('account.tokens'), ['tokenName' => 'vame']);
        $response->assertRedirect(route('account.tokens'));
        $response->assertSessionHas('token');
    }
}
