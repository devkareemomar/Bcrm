<?php

namespace App\Enums;

use BackedEnum;

trait EnumUtils
{
    /** Get an array of case values. */
    public static function values(): array
    {
        $cases = static::cases();

        return isset($cases[0]) && $cases[0] instanceof BackedEnum
            ? array_column($cases, 'value')
            : array_column($cases, 'name');
    }
}
