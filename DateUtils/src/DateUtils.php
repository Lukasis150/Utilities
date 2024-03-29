<?php

declare (strict_types = 1);

namespace Lukas\App;

use DateTime;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
class DateUtils 
{
    const DATETIME_FORMAT = 'j.n.Y G:i:s';
    const DATE_FORMAT = 'j.n.Y';
    const TIME_FORMAT = 'G:i:s';

    const DB_DATETIME_FORMAT = 'Y-m-d H:i:s';
    //2018-12-03 13:06:34.387854
    const DB_DATETIME_FORMAT_MILISECONDS = 'Y-m-d H:i:s.u';
    const DB_DATE_FORMAT = 'Y-m-d';
    const DB_TIME_FORMAT = 'H:i:s';

    private static $errorMessages = array(
            self::DATE_FORMAT => 'Neplatné datum, zadejte ho prosím ve tvaru dd.mm.rrrr',
            self::TIME_FORMAT => 'Neplatný čas, zadejte ho prosím ve tvaru hh:mm, můžete dodat i vteřiny',
            self::DATETIME_FORMAT => 'Neplatné datum nebo čas, zadejte prosím hodnotu ve tvaru dd.mm.rrrr hh:mm, případně vteřiny',
    );

    private static $months = array('ledna', 'února', 'března', 'dubna', 'května', 'června', 'července', 'srpna', 'září', 'října', 'listopadu', 'prosince');

    private static $formatDictionary = array(
        self::DATE_FORMAT => self::DB_DATE_FORMAT,
        self::DATETIME_FORMAT => self::DB_DATETIME_FORMAT,
        self::TIME_FORMAT => self::DB_TIME_FORMAT,
    );

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

    private static function getPrettyDate($dateTime)
    {
        $now = new DateTime();
        if ($dateTime->format('Y') != $now->format('Y'))
            return $dateTime->format('j.n.Y');
        $dayMonth = $dateTime->format('d-m');
        if ($dayMonth == $now->format('d-m'))
            return "Dnes";
        $now->modify('-1 DAY');
        if ($dayMonth == $now->format('d-m'))
            return "Včera";
        $now->modify('+2 DAYS');
        if ($dayMonth == $now->format('d-m'))
            return "Zítra";
        return $dateTime->format('j.') . self::$months[$dateTime->format('n') - 1];
    }

    public static function prettyDate($date)
    {
        return self::getPrettyDate(self::getDateTime($date));
    }

    public static function prettyDateTime($date)
    {
        $dateTime = self::getDateTime($date);
        return self::getPrettyDate($dateTime) . $dateTime->format(' H:i:s');
    }

    public static function parseDateTime($date, $format = self::DATETIME_FORMAT)
    {
        if (mb_substr_count($date, ':') == 1)
            $date .= ':00';
        // Smaže mezery před nebo za separátory
        $a = array('/([\.\:\/])\s+/', '/\s+([\.\:\/])/', '/\s{2,}/');
        $b = array('\1', '\1', ' ');
        $date = trim(preg_replace($a, $b, $date));
        // Smaže nuly před čísly
        $a = array('/^0(\d+)/', '/([\.\/])0(\d+)/');
        $b = array('\1', '\1\2');
        $date = preg_replace($a, $b, $date);
        // Vytvoří instanci DateTime, která zkontroluje zda zadané datum existuje
        $dateTime = DateTime::createFromFormat($format, $date);
        $errors = DateTime::getLastErrors();
        // Vyvolání chyby
        if ($errors['warning_count'] + $errors['error_count'] > 0)
        {
            if (in_array($format, self::$errorMessages))
                throw new InvalidArgumentException(self::$errorMessages[$format]);
            else
                throw new InvalidArgumentException('Neplatná hodnota');
        }
        // Návrat data v MySQL formátu
        return $dateTime->format(self::$formatDictionary[$format]);
    }

    public static function validDate($date, $format = self::DATETIME_FORMAT)
    {   
        try
        {
            self::parseDateTime($date, $format);
            return true;
        }
        catch (InvalidArgumentException $e)
        {
        }
        return false;
    }
    
    public static function dbNow()
    {
        $dateTime = new DateTime();
        return $dateTime->format(self::DB_DATETIME_FORMAT);
    }
}
