<?php

namespace App\Domains\Users\Enums;

use Spatie\Enum\Enum;

/**
 * Class DeleteType
 *
 * @package App\Domains\Users\Enums
 */
final class DeleteType extends Enum
{
    public const MARK = 'mark';
    public const RESTORE = 'restore';
    public const DELETE = 'delete';
}
