<?php
namespace APP\Helpers;

class DateHelper
{
    public static function isValidDate(string $date): bool
    {
        list($year, $month, $day) = explode('-', $date);

        return checkdate($month, $day, $year);
    }
}