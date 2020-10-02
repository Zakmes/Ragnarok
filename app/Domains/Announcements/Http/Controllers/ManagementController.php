<?php

namespace App\Domains\Announcements\Http\Controllers;

use App\Domains\Announcements\Actions\CreateAnnouncement;
use App\Domains\Announcements\Actions\DeleteAnnouncement;
use App\Domains\Announcements\DTO\AnnouncementDataObject;
use App\Domains\Announcements\Enums\AnnouncementAreaEnum;
use App\Domains\Announcements\Enums\AnnouncementTypeEnum;
use App\Domains\Announcements\Http\Requests\CreateAnnouncementRequest;
use App\Domains\Announcements\Models\Announcement;
use App\Domains\Announcements\Services\AnnouncementService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * Class ManagementController
 *
 * @todo Implement store method
 *
 * @package App\Domains\Announcements\Http\Controllers
 */
class ManagementController extends Controller
{
    private AnnouncementService $announcementService;

    /**
     * ManagementController constructor.
     *
     * @param  AnnouncementService $announcementService The business logic layer for the announcement system.
     * @return void
     */
    public function __construct(AnnouncementService $announcementService)
    {
        $this->middleware(['auth', 'kiosk']);
        $this->authorizeResource(Announcement::class, 'announcement');

        $this->announcementService = $announcementService;
    }

    /**
     * Method for displaying the management overview for the announcements.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('announcements.overview', ['announcements' => $this->announcementService->paginate()]);
    }

    /**
     * Method for displaying the announcement information in the application.
     *
     * @param  Announcement $announcement The resource entity from the announcement.
     * @return Renderable
     */
    public function show(Announcement $announcement): Renderable
    {
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Method for displaying the create view for an announcement.
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        return view('announcements.create', ['types' => AnnouncementTypeEnum::TYPES, 'areas' => AnnouncementAreaEnum::AREAS]);
    }

    /**
     * Method for creating an new announcement in the application.
     *
     * @param  CreateAnnouncementRequest $request            The form request class that contains all the request information.
     * @param  CreateAnnouncement        $createAnnouncement The action that stores the announcement in the application.
     * @return RedirectResponse
     */
    public function store(CreateAnnouncementRequest $request, CreateAnnouncement $createAnnouncement): RedirectResponse
    {
        $createAnnouncement->execute(AnnouncementDataObject::fromRequest($request), $request->user());
        flash()->success(__('The announcement has been created in the application.'));

        return redirect(kioskRoute('announcements.create'));
    }

    /**
     * Method for deleting an announcement in the application.
     *
     * @param  Announcement         $announcement The resource entity from the given announcement.
     * @param  DeleteAnnouncement   $deleteAction The business action that handles the removal.
     * @return Renderable|RedirectResponse
     */
    public function destroy(Announcement $announcement, DeleteAnnouncement $deleteAction): RedirectResponse
    {
        $deleteAction->execute($announcement);
        flash()->success('The announcement is successfully deleted.');

        return redirect(kioskRoute('announcements.overview'));
    }
}
