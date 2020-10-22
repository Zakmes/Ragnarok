<?php

namespace App\Domains\Announcements\Http\Controllers;

use App\Domains\Announcements\Actions\StatusAction;
use App\Domains\Announcements\Enums\AnnouncementStatusEnum;
use App\Domains\Announcements\Models\Announcement;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class StatusController
 *
 * @package App\Domains\Announcements\Http\Controllers
 */
class StatusController extends Controller
{
    /**
     * StatusController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'kiosk', '2fa']);
    }

    /**
     * Method for enabling an announcement in the application.
     *
     * @param  Request            $request      The request instance that contains all the request information.
     * @param  Announcement       $announcement The resource entity from the given announcement.
     * @param  StatusAction       $statusAction The internal action class that actually enable the announcement in the application.
     * @return RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function enable(Request $request, Announcement $announcement, StatusAction $statusAction): RedirectResponse
    {
        $this->authorize('enable-announcement', $announcement);

        $statusAction->execute($request, $announcement, AnnouncementStatusEnum::ENABLED);
        flash()->success(__('The announcement is successfully enabled.'));

        return back();
    }

    /**
     * Method for disabling an announcement in the application.
     *
     * @param  Request            $request      The request instance that contains all the request information.
     * @param  Announcement       $announcement The resource entity from the given announcement.
     * @param  StatusAction       $statusAction The internal action class that actually enable the announcement in the application.
     * @return RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function disable(Request $request, Announcement $announcement, StatusAction $statusAction): RedirectResponse
    {
        $this->authorize('disable-announcement', $announcement);

        $statusAction->execute($request, $announcement, AnnouncementStatusEnum::DISABLED);
        flash()->success(__('The announcement is successfully disabled.'));

        return back();
    }
}
