<?php

declare (strict_types = 1);

namespace Lukas\App;

use DateTime;

class DateUtils 
{
    public static function getDateTime($date) :DateTime
    {
        if (ctype_digit($date)) {
            $date = '@' . $date;
        }
        return new DateTime($date);
    }

    public static function formatDate($date) :string
    {
        $dateTime = self::getDateTime($date);
        return $dateTime->format('d.m.Y');
    }

    public static function formatDateTime($date) :string
    {
        $dateTime = self::getDateTime($date);
        return $dateTime->format('d.m.Y H:i:s');
    }
}
