<?php
namespace APP\Helpers;

class DateHelper
{
    public static function isValidDate(string $date): bool
    {
        if(empty($date) || substr_count($date, '-') != 2) {
            return false;
        }
        list($year, $month, $day) = explode('-', $date);

        return checkdate((int)$month, (int)$day, (int)$year);
    }
}