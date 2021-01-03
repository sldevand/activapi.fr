<?php

namespace Tests\OCFram;

use OCFram\DateFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class DateFactoryTest
 * @package Tests\OCFram
 */
class DateFactoryTest extends TestCase
{
    public function testTodayToString()
    {
        $expected = date('Y-m-d');
        $actual = DateFactory::todayToString();
        self::assertEquals($expected, $actual);
    }

    public function testTodayFullString()
    {
        $expected = date('20y-m-d H:i:s');
        $actual = DateFactory::todayFullString();
        self::assertEquals($expected, $actual);
    }
}