<?php

namespace Tests\Feature\Authentication;

use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Class RegisterControllerTest
 *
 * @package Tests\Feature\Authentication
 */
class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * The route for after an successfully registration.
     *
     * @return string
     */
    protected function successfulRegistrationRoute(): string
    {
        return url(RouteServiceProvider::HOME);
    }

    /**
     * The GET route for the registration form.
     *
     * @return string
     */
    protected function registerGetRoute(): string
    {
        return route('register');
    }

    /**
     * The POST route for the user registration.
     *
     * @return string
     */
    protected function registerPostRoute(): string
    {
        return route('register');
    }

    /**
     * The route that will be used in the guest middleware.
     *
     * @return string
     */
    protected function guestMiddlewareRoute(): string
    {
        return url(RouteServiceProvider::HOME);
    }

    /** @test */
    public function userCanViewRegistrationForm(): void
    {
        $response = $this->get($this->registerGetRoute());
        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    /** @test */
    public function userCannotViewARegistrationFormWhenAuthenticated(): void
    {
        $me = User::factory()->create();

        $response = $this->actingAs($me)->get($this->registerGetRoute());
        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    /** @test */
    public function userCanRegister(): void
    {
        Event::fake();

        $response = $this->post($this->registerPostRoute(), [
            'firstName' => $firstName = $this->faker->firstName,
            'lastName' => $lastName = $this->faker->lastName,
            'email' => $email = $this->faker->safeEmail,
            'password' => $password = $this->faker->password,
            'password_confirmation' => $password,
        ]);

        $response->assertRedirect($this->successfulRegistrationRoute());

        $this->assertDatabaseCount('users', 1);
        $this->assertCount(1, $users = User::all());
        $this->assertAuthenticatedAs($user = $users->first());
        $this->assertEquals($lastName, $user->lastName);
        $this->assertEquals($firstName, $user->firstName);
        $this->assertEquals($email, $user->email);
        $this->assertTrue(Hash::check($password, $user->password));

        Event::assertDispatched(Registered::class, static function (Registered $event) use ($user): bool {
            return $event->user->id === $user->id;
        });
    }

    /** @test */
    public function userCannotRegisterWithoutName(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'firstName' => '',
            'lastName' => '',
            'email' => $this->faker->safeEmail,
            'password' => $password = $this->faker->password,
            'password_confirmation' => $password,
        ]);

        $this->assertDatabaseCount('users', 0);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors(['firstName', 'lastName']);

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function userCannotRegisterWithoutEmail(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
           'firstName' => $this->faker->firstName,
           'lastName' => $this->faker->lastName,
           'email' => '',
           'password' => $password = $this->faker->password,
           'password_confirmation' => $password,
        ]);

        $this->assertDatabaseCount('users', 0);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertTrue(session()->hasOldInput('firstName'));
        $this->assertTrue(session()->hasOldInput('lastName'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function userCannotRegisterWithInvalidEmail(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => 'invalid-email',
            'password' => $password = $this->faker->password,
            'password_confirmation' => $password
        ]);

        $this->assertDatabaseCount('users', 0);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertTrue(session()->hasOldInput('firstName'));
        $this->assertTrue(session()->hasOldInput('lastName'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function userCannotRegisterWithoutPassword(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
           'firstName' => $this->faker->firstName,
           'lastName' => $this->faker->lastName,
           'email' => $this->faker->safeEmail,
           'password' => '',
           'password_confirmation' => '',
        ]);

        $this->assertDatabaseCount('users', 0);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');

        $this->assertTrue(session()->hasOldInput('lastName'));
        $this->assertTrue(session()->hasOldInput('firstName'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertGuest();
    }

    /** @test */
    public function userCannotRegisterWithoutPasswordConfirmation(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
           'firstName' => $this->faker->lastName,
           'lastName' => $this->faker->lastName,
           'email' => $this->faker->safeEmail,
           'password' => $this->faker->password,
           'password_confirmation' => '',
        ]);

        $this->assertDatabaseCount('users', 0);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');

        $this->assertTrue(session()->hasOldInput('firstName'));
        $this->assertTrue(session()->hasOldInput('lastName'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function userCannotRegisterWithPasswordsNotMatching(): void
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password,
            'password_confirmation' => 'other password',
        ]);

        $this->assertDatabaseCount('users', 0);

        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');

        $this->assertTrue(session()->hasOldInput('firstName'));
        $this->assertTrue(session()->hasOldInput('lastName'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
