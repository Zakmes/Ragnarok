<?php

namespace App\Domains\Activity\Http\Controllers;

use App\Domains\Activity\Models\Activity;
use App\Domains\Activity\Services\ActivityService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class OverviewController
 *
 * @package App\Domains\Activity\Http\Controllers
 */
class OverviewController extends Controller
{
    private ActivityService $activityService;

    /**
     * OverviewController constructor.
     *
     * @todo register policy
     *
     * @param  ActivityService $activityService The business logic layer that is needed for the activity log.
     * @return void.
     */
    public function __construct(ActivityService $activityService)
    {
        $this->middleware(['auth', 'kiosk', 'can:view,' . Activity::class]);
        $this->activityService = $activityService;
    }

    /**
     * Method for displaying the activity log overview.
     *
     * @param  string|null $filter   The filter criteria u want to apply to your overview.
     * @param  string|null $timespan The timespan for the activity u want to display.
     * @return Renderable
     */
    public function __invoke(?string $filter = null, ?string $timespan = null): Renderable
    {
        return view('activity.index', [
            'filter' => $filter,
            'filterKeywords' => $this->activityService->getFilterKeywords(),
            'activities' => $this->activityService->getActivities($filter, $timespan)]);
    }
}
