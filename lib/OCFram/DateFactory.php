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
     */
    public static function createDateFromStr($dateStr)
    {
        return new DateTime($dateStr, new DateTimeZone('Europe/Paris'));
    }

    /**
     * @return string
     */
    public static function todayToString()
    {
        $now = new DateTime("now", new DateTimeZone('Europe/Paris'));

        return $now->format("Y-m-d");
    }

    /**
     * @param string $dateStr
     * @return string
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
     * @param $hourStr
     * @return string
     */
    public function prepareHourFromFile($hourStr)
    {
        $hourStr = explode(' ', $hourStr);
        $timeStr = explode(':', $hourStr[0]);

        $heure = $timeStr[0];
        $minute = $timeStr[1];
        $seconde = $timeStr[2];

        if (strlen($heure . ':' . $minute . ':' . $seconde) < 8) {
            if (strlen($heure) == 1) {
                $heure = '0' . $heure;
            }
            if (strlen($minute) == 1) {
                $minute = '0' . $minute;
            }
            if (strlen($seconde) == 1) {
                $seconde = '0' . $seconde;
            }
        }

        return $heure . ':' . $minute . ':' . $seconde;
    }

    /**
     * @param $dateStr
     * @return false|int|string
     */
    public function createDate($dateStr)
    {
        $date = date_create_from_format('d-m-Y H:i:s', $dateStr);
        if (!is_bool($date)) {
            return date_format($date, '20y-m-d H:i:s');
        }
        return -1;
    }
}
