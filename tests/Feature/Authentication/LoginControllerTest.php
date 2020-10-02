<?php

namespace Tests\Feature\Authentication;

use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Class LoginControllerTest
 *
 * @package Tests\Feature\Authentication
 */
class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get the dashboard route for when the user is successfully authenticated.
     *
     * @return string
     */
    protected function successfulLoginRoute(): string
    {
        return url(RouteServiceProvider::HOME);
    }

    /**
     * Get the route for the login view in the application.
     *
     * @return string
     */
    protected function loginGetRoute(): string
    {
        return route('login');
    }

    /**
     * The route that handles the login request in the application.
     *
     * @return string
     */
    public function loginPostRoute(): string
    {
        return route('login');
    }

    /**
     * The route that handles the logout request in the application.
     *
     * @return string
     */
    protected function logoutRoute(): string
    {
        return route('logout');
    }

    /**
     * The route for when an authenticated user is successfully logged out.
     *
     * @return string
     */
    protected function successfulLogoutRoute(): string
    {
        return '/';
    }

    /**
     * Get the route that is used in the guest middleware route.
     *
     * @return string
     */
    protected function guestMiddlewareRoute(): string
    {
        return url(RouteServiceProvider::HOME);
    }

    /**
     * Get the message for when a user reach the max login attempts.
     *
     * @return string
     */
    protected function getTooManyLoginAttemptsMessage(): string
    {
        return sprintf('/^%s$/', str_replace('\:seconds', '\d+', preg_quote(__('auth.throttle'), '/')));
    }

    /** @test */
    public function userCanViewALoginForm(): void
    {
        $response = $this->get($this->loginGetRoute());
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function userCannotViewALoginFormWhenAuthenticated(): void
    {
        $me = User::factory()->make();

        $response = $this->actingAs($me)->get($this->loginGetRoute());
        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    /** @test */
    public function userCanLoginWithCorrectCredentials(): void
    {
        $me = User::factory()->create(['password' => $password = 'my-password']);

        $response = $this->post($this->loginPostRoute(), ['email' => $me->email, 'password' => $password]);
        $response->assertRedirect($this->successfulLoginRoute());

        $this->assertAuthenticatedAs($me);
    }

    /** @test */
    public function rememberMeFunctionality(): void
    {
        $user = User::factory()->create(['id' => random_int(1, 100), 'password' => $password = 'i-love-laravel',]);

        $response = $this->post($this->loginPostRoute(), ['email' => $user->email, 'password' => $password, 'remember' => 'on',]);

        $user = $user->fresh();

        $response->assertRedirect($this->successfulLoginRoute());
        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function userCannotLoginWithIncorrectPassword(): void
    {
        $user = User::factory()->create(['password' => 'my-password']);

        $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
           'email' => $user->email, 'password' => 'invalid-password',
        ]);

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function userCannotLoginWithEmailThatDoesNotExist(): void
    {
        $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
           'email' => 'nobody@example.com', 'password' => 'invalid-password',
        ]);

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function userCanLogout(): void
    {
        $this->be(User::factory()->create());

        $response = $this->post($this->logoutRoute());
        $response->assertRedirect($this->successfulLogoutRoute());

        $this->assertGuest();
    }

    /** @test */
    public function userCannotLogoutWhenNotAuthenticated(): void
    {
        $response = $this->post($this->logoutRoute());
        $response->assertRedirect($this->successfulLogoutRoute());

        $this->assertGuest();
    }

    /** @test */
    public function userCannotMakeMoreThanFiveAttemptsInOneMinute(): void
    {
        $me = User::factory()->create(['password' => $password = 'my-password']);

        foreach (range(0, 5) as $_) {
            $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
               'email' => $me->email, 'password' => 'invalid-password'
            ]);
        }

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertMatchesRegularExpression(
            $this->getTooManyLoginAttemptsMessage(),
            collect($response->baseResponse->getSession()->get('errors')->getBag('default')->get('email'))->first()
        );

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
