<?php

namespace App\Domains\Users\Actions;

use App\Domains\Announcements\Services\AnnouncementService;
use App\Domains\Users\Services\RoleService;
use App\Domains\Users\Services\UserService;

/**
 * Class BaseAction
 *
 * @package App\Support\Actions
 */
class BaseAction
{
    protected UserService $userService;
    protected RoleService $roleService;
    protected AnnouncementService $announcementService;

    public function __construct(
        UserService $userService,
        RoleService $roleService,
        AnnouncementService $announcementService
    ) {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->announcementService = $announcementService;
    }

    public function announcementService(): AnnouncementService
    {
        return new AnnouncementService();
    }
}
