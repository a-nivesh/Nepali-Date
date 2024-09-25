<?php

namespace Nivesh\App;

use Exception;
use Nivesh\App\Traits\DateFormatter;

class NepaliDate {
    
    use DateFormatter;

    public static function __callStatic($method, $arguments) {
        $instance = new self();

        if (method_exists($instance, $method)) {
            return call_user_func_array([$instance, $method], $arguments);
        } else {
            throw new Exception("Method $method does not exist.");
        }
    }

    protected function today(string $format = "{Y}-{m}-{d}")
    {
        return $this->engDate()->format($format);
    }

    protected function yesterday(string $format = "{Y}-{m}-{d}")
    {
        $date = date('Y-m-d', strtotime("yesterday"));
        return $this->engDate($date)->format($format);
    }

    protected function tomorrow(string $format = "{Y}-{m}-{d}")
    {
        $date = date('Y-m-d', strtotime("tomorrow"));
        return $this->engDate($date)->format($format);
    }

    protected function daysAfter(int $days, string $format = "{Y}-{m}-{d}")
    {
        $date = date('Y-m-d', strtotime(("+$days day")));
        return $this->engDate($date)->format($format);
    }

    protected function daysBefore(int $days, string $format = "{Y}-{m}-{d}")
    {
        $date = date('Y-m-d', strtotime(("-$days day")));
        return $this->engDate($date)->format($format);
    }

    protected function nextWeek(string $format = "{Y}-{m}-{d}")
    {
        $date = date('Y-m-d', strtotime(("+1 week")));
        return $this->engDate($date)->format($format);
    }

    protected function previousWeek(string $format = "{Y}-{m}-{d}")
    {
        $date = date('Y-m-d', strtotime(("-1 week")));
        return $this->engDate($date)->format($format);
    }

    protected function currentMonth(string $format = "{M}")
    {
        return $this->getCurrentMonth($format);
    }

    protected function nextMonth(string $format = "{M}")
    {
        return $this->getNextMonth($format);
    }

    protected function previousMonth(string $format = "{M}")
    {
        return $this->getPreviousMonth($format);
    }

    protected function currentQuater(string $format = "{Q}")
    {
        return $this->getCurrentQuater($format);
    }

    protected function nextQuater(string $format = "{Q}")
    {
        return $this->getNextQuater($format);
    }

    protected function previousQuater(string $format = "{Q}")
    {
        return $this->getPreviousQuater($format);
    }

    protected function currentFiscalYear(string $format = "{F}")
    {
        return $this->getCurrentFiscalYear($format);
    }

    protected function nextFiscalYear(string $format = "{F}")
    {
        return $this->getNextFiscalYear($format);
    }

    protected function previousFiscalYear(string $format = "{F}")
    {
        return $this->getPreviousFiscalYear($format);
    }
}