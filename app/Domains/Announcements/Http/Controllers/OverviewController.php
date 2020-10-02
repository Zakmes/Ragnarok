<?php

namespace App\Domains\Announcements\Http\Controllers;

use App\Domains\Announcements\Models\Announcement;
use App\Domains\Announcements\Services\AnnouncementService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * Class OverviewController
 *
 * The controller that will display the frontend announcement overview in the application.
 * Also contains all the frontend related thingies for the users.
 *
 * @package App\Domains\Announcements\Http\Controllers
 */
class OverviewController extends Controller
{
    private AnnouncementService $announcementService;

    /**
     * OverviewController constructor.
     *
     * @param  AnnouncementService $announcementService The business logic layer for the announcements in the application.
     * @return void
     */
    public function __construct(AnnouncementService $announcementService)
    {
        $this->middleware('auth');
        $this->announcementService = $announcementService;
    }

    /**
     * Method for displaying the announcements overview.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('announcements.index', ['announcements' => $this->announcementService->getAnnouncementsToDisplay()]);
    }

    /**
     * Method for marking an announcement as read.
     *
     * @param  Announcement $announcement The resource entity from the giv en entity.
     * @return RedirectResponse
     */
    public function markAsRead(Announcement $announcement): RedirectResponse
    {
        $announcement->markAsRead();

        return redirect()->route('announcements.index');
    }
}
