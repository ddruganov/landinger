<?php

namespace core\components\helpers;

class DateHelper
{
    private static array $monthGenetive = [
        1 => 'января',
        2 => 'февраля',
        3 => 'марта',
        4 => 'апреля',
        5 => 'мая',
        6 => 'июня',
        7 => 'июля',
        8 => 'августа',
        9 => 'сентября',
        10 => 'октября',
        11 => 'ноября',
        12 => 'декабря',
    ];

    public static function datetimeToHumanReadable(string $datetime)
    {
        [$date, $time] = explode(' ', $datetime);

        return self::dateToHumanReadable($date) . ' в ' . $time;
    }

    public static function dateToHumanReadable(string $date)
    {
        [$year, $month, $day] = explode('-', $date);
        if (strpos($day, '0') === 0) {
            $day = str_replace('0', '', $day);
        }
        if (strpos($month, '0') === 0) {
            $month = str_replace('0', '', $month);
        }
        return $day . 'го ' . self::$monthGenetive[$month] . ' ' . $year;
    }

    public static function now(string $format = 'Y-m-d H:i:s')
    {
        return self::datetimeAsString($format, time());
    }

    public static function datetimeAsString(string $format, int $timestamp): ?string
    {
        if (!$timestamp) {
            return null;
        }

        return date($format, $timestamp);
    }

    public static function addDays(int $days): string
    {
        return self::datetimeAsString('Y-m-d H:i:s', strtotime("+ $days days"));
    }
}
