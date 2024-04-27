<?php
declare(strict_types=1);

namespace Timer\Enum;

abstract class Time
{
    const int MINUTE_IN_SECONDS = 60;

    public static function getSecondsByMinutes(int $minutes) : int
    {
        return $minutes * self::MINUTE_IN_SECONDS;
    }

    public static function addMinutes(int $seconds, int $minutes) : int
    {
        return $seconds + self::getSecondsByMinutes($minutes);
    }


}
