<?php

declare (strict_types = 1);

namespace Lukas\App;
use DateTime;

require_once(__DIR__ . '/../src/DateUtils.php');

use PHPUnit\Framework\TestCase;

class DateUtilsTest extends TestCase
{   
    public static function testFormatDate()
    {
        $dateUtils = new DateUtils();
        self::assertEquals('03.12.2018', $dateUtils->formatDate(date('2018-12-03 13:06:34.3870')));
    }
    
    public static function testFormatDateTimeTest()
    {
        $dateUtils = new DateUtils();
        self::assertEquals('03.12.2018 13:06:34', $dateUtils->formatDateTime(date('2018-12-03 13:06:34.3870')));
    }
    
    public function testValidate()
    {
        $dateUtils = new DateUtils();
        self::assertEquals(false, $dateUtils->validDate(date('2018-12-03 13:06:71.387125')));
        // self::assertEquals(true, $dateUtils->validDate(date('2018-12-03 13:06:51.387125'), 'j.n.Y G:i:s'));
    }
}