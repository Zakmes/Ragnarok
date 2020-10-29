<?php

namespace Tests\Feature\Authentication;

use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Class ForgotPasswordControllerTest
 *
 * @author    Dariusz Czajkowski
 * @copyright https://github.com/DCzajkowski/auth-tests/blob/master/LICENSE
 * @package   Tests\Feature\Authentication
 */
class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * The password request route
     *
     * @return string
     */
    protected function passwordRequestRoute(): string
    {
        return route('password.request');
    }

    /**
     * The reset password email GET route
     *
     * @return string
     */
    protected function passwordEmailGetRoute(): string
    {
        return route('password.email');
    }

    /**
     * The  reset password email POST route
     *
     * @return string
     */
    protected function passwordEmailPostRoute(): string
    {
        return route('password.email');
    }

    /** @test */
    public function userCanViewAnEmailPasswordForm(): void
    {
        $response = $this->get($this->passwordRequestRoute());
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }

    /** @test */
    public function userCanViewAnEmailPasswordFormWhenAuthenticated(): void
    {
        $me = User::factory()->make();

        $response = $this->actingAs($me)->get($this->passwordRequestRoute());
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }

    /** @test */
    public function userReceivesAnEmailWithPasswordResetLink(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => $this->faker->safeEmail]);

        $this->post($this->passwordEmailPostRoute(), ['email' => $user->email]);
        $this->assertNotNull($token = DB::table('password_resets')->first());

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($token): bool {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    /** @test */
    public function userDoesNotReceiveEmailWhenNotRegistered(): void
    {
        Notification::fake();

        $response = $this->from($this->passwordEmailGetRoute())->post($this->passwordEmailPostRoute(), [
            'email' => $email = $this->faker->safeEmail,
        ]);

        $response->assertRedirect($this->passwordEmailGetRoute());
        $response->assertSessionHasErrors('email');

        Notification::assertNotSentTo(User::factory()->make(['email' => $email]), ResetPassword::class);
    }

    /** @test */
    public function testEmailIsRequired(): void
    {
        $response = $this->from($this->passwordEmailGetRoute())->post($this->passwordEmailPostRoute(), []);
        $response->assertRedirect($this->passwordEmailGetRoute());
        $response->assertSessionHasErrors('email');
    }

    public function testEmailIsValidEmail(): void
    {
        $response = $this->from($this->passwordEmailGetRoute())->post($this->passwordEmailPostRoute(), [
            'email' => $this->faker->safeEmail
        ]);

        $response->assertRedirect($this->passwordEmailGetRoute());
        $response->assertSessionHasErrors('email');
    }
}
