<?php

namespace Nivesh\App\Utils;

use Exception;

class DateHelper
{
    private $converter, $formatter;

    private int $currentStep = 0;
    private bool $isBsConverted = false;

    const STEP_CONVERT_BS = 1;
    const STEP_DAY = 2;
    const STEP_MONTH = 3;
    const STEP_QUARTER = 4;
    const STEP_FISCAL_YEAR = 5;

    public function __construct()
    {
        $this->converter = new DateConverter();
        $this->formatter = new DateFormatter();
    }

    public function __toString()
    {
        return $this->format();
    }

    public function convertToBs($date = null, ?string $format)
    {
        if ($this->isBsConverted) {
            throw new Exception("Method convertToBs() is not callable.");
        }
        $this->converter->convertToBs($date);
        $this->currentStep = self::STEP_CONVERT_BS;
        $this->isBsConverted = true;
        return $format ? $this->format($format) : $this;
    }

    public function today(?string $format)
    {
        $this->validateStep(self::STEP_DAY);
        $this->currentStep = self::STEP_DAY;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        return $format ? $this->format($format) : $this;
    }

    public function yesterday(?string $format)
    {
        $this->validateStep(self::STEP_DAY);
        $this->currentStep = self::STEP_DAY;
        $this->converter->calculateDateAndWeekday(-1);
        return $format ? $this->format($format) : $this;
    }

    public function tomorrow(?string $format = null)
    {
        $this->validateStep(self::STEP_DAY);
        $this->currentStep = self::STEP_DAY;
        $this->converter->calculateDateAndWeekday(1);
        return $format ? $this->format($format) : $this;
    }

    public function daysAfter(int $days, ?string $format = null)
    {
        $this->validateStep(self::STEP_DAY);
        $this->currentStep = self::STEP_DAY;
        $this->converter->calculateDateAndWeekday($days);
        return $format ? $this->format($format) : $this;
    }

    public function daysBefore(int $days, ?string $format = null)
    {
        $this->validateStep(self::STEP_DAY);
        $this->currentStep = self::STEP_DAY;
        $this->converter->calculateDateAndWeekday(-$days);
        return $format ? $this->format($format) : $this;
    }

    public function nextWeek(?string $format = null)
    {
        $this->validateStep(self::STEP_DAY);
        $this->currentStep = self::STEP_DAY;
        $this->converter->calculateDateAndWeekday(7);
        return $format ? $this->format($format) : $this;
    }

    public function previousWeek(?string $format = null)
    {
        $this->validateStep(self::STEP_DAY);
        $this->currentStep = self::STEP_DAY;
        $this->converter->calculateDateAndWeekday(-7);
        return $format ? $this->format($format) : $this;
    }

    public function nextMonth(?string $format = null)
    {
        $this->validateStep(self::STEP_MONTH);
        $this->currentStep = self::STEP_MONTH;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        $this->converter->addMonth(1);
        return $format ? $this->format($format) : $this;
    }

    public function currentMonth(?string $format = null)
    {
        $this->validateStep(self::STEP_MONTH);
        $this->currentStep = self::STEP_MONTH;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        return $format ? $this->format($format) : $this;
    }

    public function previousMonth(?string $format = null)
    {
        $this->validateStep(self::STEP_MONTH);
        $this->currentStep = self::STEP_MONTH;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        $this->converter->addMonth(-1);
        return $format ? $this->format($format) : $this;
    }

    public function nextQuater(?string $format = null)
    {
        $this->validateStep(self::STEP_QUARTER);
        $this->currentStep = self::STEP_QUARTER;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        $this->converter->addQuater(1);
        return $format ? $this->format($format) : $this;
    }

    public function currentQuater(?string $format = null)
    {
        $this->validateStep(self::STEP_QUARTER);
        $this->currentStep = self::STEP_QUARTER;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        return $format ? $this->format($format) : $this;
    }

    public function previousQuater(?string $format = null)
    {
        $this->validateStep(self::STEP_QUARTER);
        $this->currentStep = self::STEP_QUARTER;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        $this->converter->addQuater(-1);
        return $format ? $this->format($format) : $this;
    }

    public function nextYear(?string $format = null)
    {
        $this->validateStep(self::STEP_FISCAL_YEAR);
        $this->currentStep = self::STEP_FISCAL_YEAR;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        $this->converter->addYear(1);
        return $format ? $this->format($format) : $this;
    }

    public function currentYear(?string $format = null)
    {
        $this->validateStep(self::STEP_FISCAL_YEAR);
        $this->currentStep = self::STEP_FISCAL_YEAR;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        return $format ? $this->format($format) : $this;
    }

    public function previousYear(?string $format = null)
    {
        $this->validateStep(self::STEP_FISCAL_YEAR);
        $this->currentStep = self::STEP_FISCAL_YEAR;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        $this->converter->addYear(-1);
        return $format ? $this->format($format) : $this;
    }

    private function validateStep(int $step)
    {
        if ($this->currentStep > $step) {
            throw new Exception("Can not call previous level method.");
        }
    }

    private function format(?string $format = null)
    {
        $this->formatter->year = $this->converter->year;
        $this->formatter->month = $this->converter->month;
        $this->formatter->day = $this->converter->day;
        $this->formatter->weekday = $this->converter->weekday;

        if (in_array($this->currentStep, [self::STEP_CONVERT_BS, self::STEP_DAY])) {
            return $this->formatter->format($format);
        } else if ($this->currentStep === self::STEP_MONTH) {
            return $this->formatter->formatMonth($format);
        } else if ($this->currentStep === self::STEP_QUARTER) {
            return $this->formatter->formatQuater($format);
        } else if ($this->currentStep === self::STEP_FISCAL_YEAR) {
            return $this->formatter->formatYear($format);
        }
    }

    public function isLastDayOfMonth()
    {
        return $this->converter->isLastDayOfMonth();
    }

    public function isLastDayOfQuater()
    {
        return $this->converter->isLastDayOfQuater();
    }

    public function isLastDayOfYear()
    {
        return $this->converter->isLastDayOfYear();
    }
}