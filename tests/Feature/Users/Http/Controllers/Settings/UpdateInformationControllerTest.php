<?php

namespace Tests\Feature\Users\Http\Controllers\Settings;

use App\Domains\Users\Http\Controllers\Settings\UpdateInformationController;
use App\Domains\Users\Http\Requests\InformationFormRequest;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class UpdateInformationControllerTest
 *
 * @package Tests\Feature\Users\Http\Controllers\Settingd
 */
class UpdateInformationControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function cannotViewTheInformationSettingsPageWhenNotAuthenticated(): void
    {
        $this->get(route('account.information.patch'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function canSuccessViewTheInformationSettingsPageWhenAuthenticated(): void
    {
        $me = User::factory()->make();

        $this->actingAs($me)
            ->get(route('account.information.patch'))
            ->assertViewIs('users.settings.information')
            ->assertSuccessful();
    }

    /** @test */
    public function canSuccessfullyTheAccountInformationInTheApplication(): void
    {
        $me = User::factory()->make();
        $requestData = ['firstName' => $this->faker->firstName, 'lastName' => $this->faker->lastName, 'email' => $this->faker->safeEmail];

        $this->assertActionUsesMiddleware(UpdateInformationController::class, '__invoke', ['auth']);
        $this->assertActionUsesFormRequest(UpdateInformationController::class, '__invoke', InformationFormRequest::class);

        $this->actingAs($me)
            ->patch(route('account.information.patch'), $requestData)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas([
               'laravel_flash_message.class' => 'alert-success',
               'laravel_flash_message.level' => 'success',
               'laravel_flash_message.message' => __('Your account information has been updated successfully.')
            ]);
    }
}
