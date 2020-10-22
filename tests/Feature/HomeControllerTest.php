<?php

namespace Tests\Feature;

use App\Http\Controllers\HomeController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class HomeControllerTest
 *
 * @package Tests\Feature
 */
class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function userCanViewTheApplicationDashboardView(): void
    {
        $me = User::factory()->make();

        $this->assertActionUsesMiddleware(HomeController::class, 'index', ['auth', '2fa']);

        $this->actingAs($me)
            ->get(route('home'))
            ->assertViewIs('home')
            ->assertSuccessful();
    }
}
