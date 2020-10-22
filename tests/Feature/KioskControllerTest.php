<?php

namespace Tests\Feature;

use App\Domains\Users\Enums\GroupEnum;
use App\Http\Controllers\KioskController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class KioskControllerTest
 *
 * @package Tests\Feature
 */
class KioskControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canSuccessFullyViewTheKioskDashboard(): void
    {
        $me = User::factory()->make(['user_group' => GroupEnum::WEBMASTER]);

        $this->assertActionUsesMiddleware(KioskController::class, '__invoke', ['auth', 'kiosk', '2fa']);

        $this->actingAs($me)
            ->get(kioskRoute('dashboard'))
            ->assertViewIs('kiosk')
            ->assertSuccessful();
    }
}
