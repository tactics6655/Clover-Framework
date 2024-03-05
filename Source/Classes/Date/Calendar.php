<?php

declare(strict_types=1);

namespace Clover\Classes\Date;

class Calendar
{
    private int $year;

    private int $month;

    private int $day;

    private $days;

    private int $lastDayOfPreviousMonth;

    private int $lastDay;

    private $textureNameOfMonth;

    private $firstDayOfWeek;

    public function __construct()
    {
        $current = strtotime('now', time());

        $this->days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];

        $this->year = intval(date('Y', $current));
        $this->month = intval(date('m', $current));
        $this->day = intval(date('d', $current));
        $this->textureNameOfMonth = date('F', $current);

        $date = sprintf("%d-%d-%d", $this->day, $this->month, $this->year);

        $this->lastDayOfPreviousMonth = intval(date('j', strtotime('last day of previous month', strtotime($date))));
        $this->lastDay = intval(date('t', strtotime($date)));

        $this->firstDayOfWeek = array_search(date('D', strtotime($date . '-1')), $this->days);
    }

    public function getLastDay()
    {
        return $this->lastDay;
    }

    public function getLastDayOfPreviousMonth()
    {
        return $this->lastDayOfPreviousMonth;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getDay()
    {
        return $this->day;
    }

    public function getDays()
    {
        return $this->days;
    }

    public function getTextureNameOfMonth()
    {
        return $this->textureNameOfMonth;
    }

    public function getFirstDayOfWeek()
    {
        return $this->firstDayOfWeek;
    }
}
