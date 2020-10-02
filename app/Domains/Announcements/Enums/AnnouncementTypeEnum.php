<?php

namespace App\Domains\Announcements\Enums;

use Spatie\Enum\Enum;

/**
 * Class AnnouncementTypeEnum
 *
 * @package App\Domains\Announcements\Enums
 */
class AnnouncementTypeEnum extends Enum
{
    public const INFO = 'info';
    public const DANGER = 'danger';
    public const WARNING = 'warning';
    public const SUCCESS = 'success';

    public const TYPES = [self::INFO, self::DANGER, self::WARNING, self::SUCCESS];
}
