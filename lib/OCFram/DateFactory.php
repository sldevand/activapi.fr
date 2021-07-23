<?php

namespace OCFram;

use DateTime;
use DateTimeZone;

/**
 * Class DateFactory
 * @package OCFram
 */
class DateFactory
{

    const PERIOD_KEYWORDS = ['today', 'yesterday', 'week', 'month'];

    /**
     * @var array
     */
    public static $days = [
        1 => "Lundi",
        2 => "Mardi",
        3 => "Mercredi",
        4 => "Jeudi",
        5 => "Vendredi",
        6 => "Samedi",
        7 => "Dimanche"
    ];

    /**
     * @param string $dateStr1
     * @param string $dateStr2
     * @return float|int
     * @throws \Exception
     */
    public static function diffMinutesFromStr($dateStr1, $dateStr2)
    {
        $date1 = new DateTime($dateStr1, new DateTimeZone('Europe/Paris'));
        $date2 = new DateTime($dateStr2, new DateTimeZone('Europe/Paris'));

        return self::diffMinutes($date1, $date2);
    }

    /**
     * @param DateTime $date1
     * @param DateTime $date2
     * @return float|int
     */
    public static function diffMinutes($date1, $date2)
    {
        return $minutes = $date1->diff($date2)->i + $date1->diff($date2)->h * 60;
    }

    /**
     * @param string $dateStr
     * @return DateTime
     * @throws \Exception
     */
    public static function createDateFromStr($dateStr)
    {
        return new DateTime($dateStr, new DateTimeZone('Europe/Paris'));
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function todayToString()
    {
        return self::now()->format("Y-m-d");
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function todayFullString()
    {
        return self::now()->format('20y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    public static function now()
    {
        return new DateTime('now', new DateTimeZone('Europe/Paris'));
    }

    /**
     * @param string $dateStr
     * @return string
     * @throws \Exception
     */
    public static function toFrDate($dateStr)
    {
        $date = new DateTime($dateStr, new DateTimeZone('Europe/Paris'));

        return $date->format('d/m/Y');
    }

    /**
     * @param int $dayNbr
     * @return mixed|null
     */
    public static function toStrDay($dayNbr)
    {
        if (!is_numeric($dayNbr)) {
            return null;
        }
        $strDay = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
        $dayNbr--;
        if ($dayNbr < 0 || $dayNbr > 6) {
            return null;
        }

        return $strDay[$dayNbr];
    }

    /**
     * @param string $day
     * @return array
     * @throws \Exception
     */
    public static function getDateLimits($day)
    {
        switch ($day) {
            case "today":
                $now = DateFactory::createDateFromStr("now");
                $dateMin = $now->format("Y-m-d");
                $dateMax = $now->format("Y-m-d");
                break;
            case "yesterday":
                $yesterday = DateFactory::createDateFromStr("now -1 day");
                $dateMin = $yesterday->format("Y-m-d");
                $dateMax = $yesterday->format("Y-m-d");
                break;
            case "week":
                $day = date('w');
                if ($day == 0) {
                    $day = 7;
                }
                $dateMin = date("Y-m-d", strtotime('-' . ($day - 1) . ' days'));
                $dateMax = date("Y-m-d", strtotime('+' . (7 - $day) . ' days'));
                break;
            case "month":
                $monthFirst = DateFactory::createDateFromStr("first day of this month");
                $monthLast = DateFactory::createDateFromStr("last day of this month");
                $dateMin = $monthFirst->format("Y-m-d");
                $dateMax = $monthLast->format("Y-m-d");
                break;
            default:
                $now = DateFactory::createDateFromStr("now");
                $dateMin = $now->format("Y-m-d");
                $dateMax = $now->format("Y-m-d");
        }

        return [
            'dateMin' => $dateMin,
            'dateMax' => $dateMax
        ];
    }
}
