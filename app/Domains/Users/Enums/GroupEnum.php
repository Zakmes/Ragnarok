<?php

namespace App\Domains\Users\Enums;

use Spatie\Enum\Enum;

/**
 * Class GroupEnum
 *
 * @package App\Domains\Users\Enums
 */
final class GroupEnum extends Enum
{
    public const USER = 'user';
    public const DEVELOPER = 'developer';
    public const WEBMASTER = 'webmaster';

    public const USERGROUPS = [self::USER, self::WEBMASTER, self::DEVELOPER];
}
