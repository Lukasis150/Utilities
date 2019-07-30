<?php

declare (strict_types = 1);

namespace Lukas\App;

use DateTime;

class DateUtils 
{
    const DATETIME_FORMAT = 'j.n.Y G:i:s';
    const DATE_FORMAT = 'j.n.Y';
    const TIME_FORMAT = 'G:i:s';
    
    const DB_DATETIME_FORMAT = 'Y-m-d H:i:s';
    const DB_DATE_FORMAT = 'Y-m-d';
    const DB_TIME_FORMAT = 'H:i:s';

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
