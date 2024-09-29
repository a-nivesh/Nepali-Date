<?php

namespace Nivesh\NepaliDate;

use Exception;
use Nivesh\NepaliDate\Utils\DateHelper;

class NepaliDate {
    private $helper;

    public function __construct()
    {
        $this->helper = new DateHelper();
    }

    public static function __callStatic($method, $arguments) {
        $instance = new self();

        if (method_exists($instance, $method)) {
            return call_user_func_array([$instance, $method], $arguments);
        } else {
            throw new Exception("Method $method does not exist.");
        }
    }

    protected function convertToBs(string $date = null, string $format = null)
    {
        return $this->helper->convertToBs($date, $format);
    }

    protected function today(string $format = null)
    {
        return $this->helper->today($format);
    }

    protected function yesterday(string $format = null)
    {
        return $this->helper->yesterday($format);
    }

    protected function tomorrow(string $format = null)
    {
        return $this->helper->tomorrow($format);
    }

    protected function daysAfter(int $days, string $format = null)
    {
        return $this->helper->daysAfter($days, $format);
    }

    protected function daysBefore(int $days, string $format = null)
    {
        return $this->helper->daysBefore($days, $format);
    }

    protected function nextWeek(string $format = null)
    {
        return $this->helper->nextWeek($format);
    }

    protected function previousWeek(string $format = null)
    {
        return $this->helper->previousWeek($format);
    }

    protected function currentMonth(string $format = null)
    {
        return $this->helper->currentMonth($format);
    }

    protected function nextMonth(string $format = null)
    {
        return $this->helper->nextMonth($format);
    }

    protected function previousMonth(string $format = null)
    {
        return $this->helper->previousMonth($format);
    }

    protected function currentQuater(string $format = null)
    {
        return $this->helper->currentQuater($format);
    }

    protected function nextQuater(string $format = null)
    {
        return $this->helper->nextQuater($format);
    }

    protected function previousQuater(string $format = null)
    {
        return $this->helper->previousQuater($format);
    }

    protected function currentYear(string $format = null)
    {
        return $this->helper->currentYear($format);
    }

    protected function nextYear(string $format = null)
    {
        return $this->helper->nextYear($format);
    }

    protected function previousYear(string $format = null)
    {
        return $this->helper->previousYear($format);
    }
}