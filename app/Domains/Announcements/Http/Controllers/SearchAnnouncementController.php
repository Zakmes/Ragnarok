<?php

namespace App\Domains\Announcements\Http\Controllers;

use App\Domains\Announcements\Models\Announcement;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

/**
 * Class SearchAnnouncementController
 *
 * @package App\Domains\Announcements\Http\Controllers
 */
class SearchAnnouncementController extends Controller
{
    /**
     * SearchAnnouncementController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'kiosk', '2fa']);
    }

    /**
     * Method for searching trough the activities in the application.
     *
     * @param  Request      $request        The resource entity that contains all the request information.
     * @param  Announcement $announcements  The database model for the announcements in the application.
     * @return Renderable
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, Announcement $announcements): Renderable
    {
        $this->authorize('view', $announcements);

        return view('announcements.overview', [
            'announcements' => $announcements->search(['title', 'message'], $request->term)
        ]);
    }
}
