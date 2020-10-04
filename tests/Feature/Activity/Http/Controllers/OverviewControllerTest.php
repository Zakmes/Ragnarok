<?php

namespace Tests\Feature\Activity\Http\Controllers;

use App\Domains\Activity\Models\Activity;
use App\Domains\Roles\Listeners\RoleEventListener;
use App\Domains\Users\Enums\GroupEnum;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class OverviewControllerTest
 *
 * @package Tests\Feature\Activity\Http\Controllers
 */
class OverviewControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canViewTheActivityLogsOverviewPage(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);
        Activity::factory()->times(10)->create();

        $response = $this->actingAs($me)->get(kioskRoute('activity.index'));
        $response->assertSuccessful();
        $response->assertViewIs('activity.index');
    }

    /** @test */
    public function pageIsSuccessfullyWhenWeUseAnFilterKeyword(): void
    {
        $me = User::factory()->create(['user_group' => GroupEnum::WEBMASTER]);
        Activity::factory()->times(10)->create(['log_name' => RoleEventListener::LogName]);

        $response = $this->actingAs($me)->get(kioskRoute('activity.index', ['filter' => RoleEventListener::LogName]));
        $response->assertSuccessful();
        $response->assertViewIs('activity.index');
    }
}
