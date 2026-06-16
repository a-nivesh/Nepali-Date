<?php

namespace Nivesh\NepaliDate;

use Exception;
use Nivesh\NepaliDate\Utils\DateHelper;

class NepaliDate {
    private DateHelper $helper;

    public function __construct()
    {
        $this->helper = new DateHelper();
    }

    public static function __callStatic(string $method, mixed $arguments) {
        $instance = new self();

        if (method_exists($instance, $method)) {
            return call_user_func_array([$instance, $method], $arguments);
        } else {
            throw new Exception("Method $method does not exist.");
        }
    }

    protected function convertToBs(string $date = '', string $format = '')
    {
        return $this->helper->convertToBs($date, $format);
    }

    protected function convertToAd(string $date)
    {
        return $this->helper->convertToAd($date);
    }

    protected function today(string $format = '')
    {
        return $this->helper->today($format);
    }

    protected function yesterday(string $format = '')
    {
        return $this->helper->yesterday($format);
    }

    protected function tomorrow(string $format = '')
    {
        return $this->helper->tomorrow($format);
    }

    protected function daysAfter(int $days, string $format = '')
    {
        return $this->helper->daysAfter($days, $format);
    }

    protected function daysBefore(int $days, string $format = '')
    {
        return $this->helper->daysBefore($days, $format);
    }

    protected function nextWeek(string $format = '')
    {
        return $this->helper->nextWeek($format);
    }

    protected function previousWeek(string $format = '')
    {
        return $this->helper->previousWeek($format);
    }

    protected function currentMonth(string $format = '')
    {
        return $this->helper->currentMonth($format);
    }

    protected function nextMonth(string $format = '')
    {
        return $this->helper->nextMonth($format);
    }

    protected function previousMonth(string $format = '')
    {
        return $this->helper->previousMonth($format);
    }

    protected function currentQuarter(string $format = '')
    {
        return $this->helper->currentQuarter($format);
    }

    protected function nextQuarter(string $format = '')
    {
        return $this->helper->nextQuarter($format);
    }

    protected function previousQuarter(string $format = '')
    {
        return $this->helper->previousQuarter($format);
    }

    protected function currentYear(string $format = '')
    {
        return $this->helper->currentYear($format);
    }

    protected function nextYear(string $format = '')
    {
        return $this->helper->nextYear($format);
    }

    protected function previousYear(string $format = '')
    {
        return $this->helper->previousYear($format);
    }

    protected function nepaliDate(string $date, string $format = '')
    {
        return $this->helper->nepaliDate($date, $format);
    }
}