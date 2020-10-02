<?php

namespace App\Domains\Announcements\Enums;

use Spatie\Enum\Enum;

/**
 * Class AnnouncementAreaEnum
 *
 * @package App\Domains\Announcements\Enums
 */
class AnnouncementAreaEnum extends Enum
{
    public const FRONTEND = 'frontend';
    public const BACKEND = 'backend';
    public const GLOBAL  = null;

    public const AREAS = [self::BACKEND, self::FRONTEND, self::GLOBAL];
}
