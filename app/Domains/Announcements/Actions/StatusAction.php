<?php

namespace App\Domains\Announcements\Actions;

use App\Domains\Announcements\Events\StatusChanged;
use App\Domains\Announcements\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class StatusAction
 *
 * @package App\Domains\Announcements\Actions
 */
class StatusAction
{
    /**
     * Method that stores enables the announcement in the application.
     *
     * @param  Request        $request      The request entity that contains all the request.
     * @param  Announcement   $announcement The resource entity from the given announcement.
     * @param  bool           $status       The newly status enum for the announcement.
     * @return bool
     */
    public function execute(Request $request, Announcement $announcement, bool $status): bool
    {
        return DB::transaction(function () use ($request, $announcement, $status): bool {
            $updateAction = $announcement->update(['enabled' => $status]);
            event(new StatusChanged($request->user(), Announcement::findOrFail($announcement->id)));

            return $updateAction;
        });
    }
}
