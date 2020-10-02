<?php

namespace Database\Seeders;

use App\Domains\Announcements\Actions\CreateAnnouncement;
use App\Domains\Announcements\DTO\AnnouncementDataObject;
use App\Domains\Announcements\Enums\AnnouncementAreaEnum;
use App\Domains\Users\Services\UserService;
use App\Support\Database\DisableForeignKeys;
use App\Support\Database\TruncateTable;
use Illuminate\Database\Seeder;

/**
 * Class AnnouncementSeeder
 *
 * @package Database\Seeders
 */
class AnnouncementSeeder extends Seeder
{
    use TruncateTable;
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @param  CreateAnnouncement $createAnnouncement   The action that gets executed for the user creation in the database.
     * @param  UserService        $userService          The business logic layer that is related to the users.
     * @return void
     */
    public function run(CreateAnnouncement $createAnnouncement, UserService $userService): void
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(['announcements', 'announcement_reads']);

        if ($this->environmentIsTestingOrLocal()) {
            $announcementData = $this->announcementDataArray();
            $creator = $userService->firstOrFail();

            $createAnnouncement->execute(
                AnnouncementDataObject::fromSeeder($announcementData)->except('ends_at', 'starts_at'),
                $creator
            );
        }
    }

    /**
     * Determine is the server environment is local of testing.
     *
     * @return bool
     */
    private function environmentIsTestingOrLocal(): bool
    {
        return app()->environment(['local', 'testing']);
    }

    /**
     * Method for preparing a data array that will be inserted into the announcements table.
     *
     * @return array
     */
    private function announcementDataArray()
    {
        return [
            'area' => AnnouncementAreaEnum::GLOBAL,
            'title' => 'Announcement title',
            'type' => 'info',
            'message' => 'This is a <strong>Global</strong> announcement that will show on both the frontend and backend.',
            'enabled' => true,
        ];
    }
}
