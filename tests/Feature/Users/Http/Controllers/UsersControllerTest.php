<?php

namespace Tests\Feature\Users\Http\Controllers;

use App\Domains\Users\Enums\GroupEnum;
use App\Domains\Users\Http\Controllers\UsersController;
use App\Domains\Users\Http\Requests\InformationFormRequest;
use App\Domains\Users\Http\Requests\UpdateFormRequest;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Class UsersControllerTest
 *
 * @package Tests\Feature\Users\Http\Controllers
 */
class UsersControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Method for creating all the user related permissions.
     *
     * @return void
     */
    private function permissionsSeed(): void
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

    /**
     * Method for mapping the data as form request data.
     *
     * @return array
     */
    private function requestData(): array
    {
        return [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'userGroup' => GroupEnum::WEBMASTER,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ];
    }

    /** @test */
    public function userCanViewTheUsersOverview(): void
    {
        $me = User::factory()->make(['user_group' => GroupEnum::WEBMASTER]);

        $this->permissionsSeed();
        $this->assertActionUsesMiddleware(UsersController::class, 'index', ['auth', 'kiosk']);

        $this->actingAs($me)
            ->get(kioskRoute('users.index'))
            ->assertViewIs('users.index')
            ->assertSuccessful();

        foreach ([GroupEnum::WEBMASTER, GroupEnum::DEVELOPER, GroupEnum::USER, 'deleted'] as $value => $filter) {
            $this->actingAs($me)
                ->get(kioskRoute('users.index', ['filter' => $filter]))
                ->assertViewIs('users.index')
                ->assertSuccessful();
        }
    }

    /** @test */
    public function userCanViewTheUserInformation(): void
    {
        $jani = User::factory()->create();
        $lena = User::factory()->make(['user_group' => GroupEnum::WEBMASTER]);

        $this->permissionsSeed();
        $this->assertActionUsesMiddleware(UsersController::class, 'show', ['auth', 'kiosk']);

        $this->actingAs($lena)
            ->get(kioskRoute('users.show', $jani))
            ->assertViewIs('users.show')
            ->assertSuccessful();
    }

    /** @test  */
    public function userCanViewTheUserCreateView(): void
    {
        $me = User::factory()->make(['user_group' => GroupEnum::WEBMASTER]);

        $this->permissionsSeed();
        $this->assertActionUsesMiddleware(UsersController::class, 'create', ['auth', 'kiosk']);

        $this->actingAs($me)
            ->get(kioskRoute('users.create'))
            ->assertViewIs('users.create')
            ->assertSuccessful();
    }

    /** @test */
    public function userCanViewTheEditViewForAnUser(): void
    {
        $me = User::factory()->make(['user_group' => GroupEnum::WEBMASTER]);
        $lena = User::factory()->create();

        $this->permissionsSeed();
        $this->assertActionUsesMiddleware(UsersController::class, 'edit', ['auth', 'kiosk']);

        $this->actingAs($me)
            ->get(kioskRoute('users.update', $lena))
            ->assertViewIs('users.edit')
            ->assertSuccessful();
    }

    /** @test */
    public function userCanDeleteOtherUsersInTheApplication(): void
    {
        $me = User::factory()->make(['user_group' => GroupEnum::WEBMASTER]);
        $lena = User::factory()->create();

        $this->permissionsSeed();
        $this->assertActionUsesMiddleware(UsersController::class, 'destroy', ['auth', 'kiosk']);

        // Test the confirmation view
        $this->actingAs($me)
            ->get(kioskRoute('users.destroy', $lena))
            ->assertViewIs('users.destroy')
            ->assertSuccessful();

        // Test the actual delete operation
        $this->actingAs($me)
            ->delete(kioskRoute('users.destroy', $lena))
            ->assertRedirect(kioskRoute('users.index', ['filter' => 'deleted']))
            ->assertSessionHasAll([
                'laravel_flash_message.level' => 'success',
                'laravel_flash_message.class' => 'alert-success',
                'laravel_flash_message.message' => __("The user account from {$lena->name} is successfully marked for deletion.")
            ]);
    }

    /** @test */
    public function userCanUpdateAnotherUserHisInformation(): void
    {
        $me = User::factory()->make(['user_group' => GroupEnum::WEBMASTER]);
        $lotte = User::factory()->create();

        $this->permissionsSeed();
        $this->assertActionUsesFormRequest(UsersController::class, 'update', UpdateFormRequest::class);
        $this->assertActionUsesMiddleware(UsersController::class, 'update', ['kiosk', 'auth']);

        $this->actingAs($me)
            ->patch(kioskRoute('users.update', $lotte), $this->requestData())
            ->assertRedirect(kioskRoute('users.show', $lotte))
            ->assertSessionHasAll([
                'laravel_flash_message.level' => 'success',
                'laravel_flash_message.class' => 'alert-success',
                'laravel_flash_message.message' => __('The user is successfully updated in the application.')
            ]);
    }

    /** @test */
    public function testIfTheUserCanStoreAnNewUser(): void
    {
        $me = User::factory()->make(['user_group' => GroupEnum::WEBMASTER]);
        $requestData = $this->requestData();

        $this->permissionsSeed();
        $this->assertActionUsesFormRequest(UsersController::class, 'store', InformationFormRequest::class);
        $this->assertActionUsesMiddleware(UsersController::class, 'store', ['auth', 'kiosk']);

        $this->actingAs($me)
            ->post(kioskRoute('users.store'), $requestData)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasAll([
                'laravel_flash_message.level' => 'success',
                'laravel_flash_message.class' => 'alert-success',
                'laravel_flash_message.message' => __("The user account for :user is successfully created", [
                    'user' => $requestData['firstName'] . ' ' . $requestData['lastName']
                ])
            ]);
    }
}
