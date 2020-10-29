<?php

namespace App\Domains\Activity\Http\Controllers;

use App\Domains\Activity\Models\Activity;
use App\Domains\Activity\Services\ActivityService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

/**
 * Class SearchController
 *
 * @package App\Domains\Activity\Http\Controllers
 */
class SearchController extends Controller
{
    private ActivityService $activityService;

    /**
     * SearchController constructor.
     *
     * @param  ActivityService $activityService The business logic layer for the logged activities in the storage.
     * @return void
     */
    public function __construct(ActivityService $activityService)
    {
        $this->middleware(['auth', 'kiosk', '2fa', 'can:view,' . Activity::class]);
        $this->activityService = $activityService;
    }

    /**
     * Method for searching trough the logged activities in the application.
     *
     * @param  Request  $request    The request entity that contains all the request information.
     * @param  Activity $activities The database model for the logged activities in the application.
     * @return Renderable
     */
    public function __invoke(Request $request, Activity $activities): Renderable
    {
        return view('activity.index', [
            'filter' => null,
            'filterKeywords' => $this->activityService->getFilterKeywords(),
            'activities' => $activities->search(['log_name', 'description'], $request->term)
        ]);
    }
}
