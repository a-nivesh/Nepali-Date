<?php

namespace Nivesh\NepaliDate\Utils;

use Exception;

class DateHelper
{
    private DateConverter $converter;
    private DateFormatter $formatter;

    private int $currentStep = 0;
    private bool $isBsConverted = false;

    const STEP_CONVERT_BS = 1;
    const STEP_DAY = 2;
    const STEP_MONTH = 3;
    const STEP_QUARTER = 4;
    const STEP_YEAR = 5;

    public function __construct()
    {
        $this->converter = new DateConverter();
        $this->formatter = new DateFormatter();
    }

    public function __toString()
    {
        return $this->format();
    }

    public function convertToBs(?string $date, ?string $format)
    {
        if ($this->isBsConverted) {
            throw new Exception("Method convertToBs() is not callable.");
        }
        $this->converter->convertToBs($date);
        $this->currentStep = self::STEP_CONVERT_BS;
        $this->isBsConverted = true;
        return $format ? $this->format($format) : $this;
    }

    public function convertToAd(string $date)
    {
        if ($this->currentStep) {
            throw new Exception("Method convertToAd() is not callable.");
        }
        return $this->converter->convertToAd($date);
    }

    public function nepaliDate(string $date, ?string $format)
    {
        if ($this->isBsConverted) {
            throw new Exception("Method nepaliDate() is not callable.");
        }
        $this->converter->nepaliDate($date);
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

    public function nextQuarter(?string $format = null)
    {
        $this->validateStep(self::STEP_QUARTER);
        $this->currentStep = self::STEP_QUARTER;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        $this->converter->addQuarter(1);
        return $format ? $this->format($format) : $this;
    }

    public function currentQuarter(?string $format = null)
    {
        $this->validateStep(self::STEP_QUARTER);
        $this->currentStep = self::STEP_QUARTER;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        return $format ? $this->format($format) : $this;
    }

    public function previousQuarter(?string $format = null)
    {
        $this->validateStep(self::STEP_QUARTER);
        $this->currentStep = self::STEP_QUARTER;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        $this->converter->addQuarter(-1);
        return $format ? $this->format($format) : $this;
    }

    public function nextYear(?string $format = null)
    {
        $this->validateStep(self::STEP_YEAR);
        $this->currentStep = self::STEP_YEAR;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        $this->converter->addYear(1);
        return $format ? $this->format($format) : $this;
    }

    public function currentYear(?string $format = null)
    {
        $this->validateStep(self::STEP_YEAR);
        $this->currentStep = self::STEP_YEAR;
        if (!isset($this->converter->year, $this->converter->month, $this->converter->day, $this->converter->weekday)) {
            $this->converter->convertToBs();
        }
        return $format ? $this->format($format) : $this;
    }

    public function previousYear(?string $format = null)
    {
        $this->validateStep(self::STEP_YEAR);
        $this->currentStep = self::STEP_YEAR;
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
            return $this->formatter->formatQuarter($format);
        } else if ($this->currentStep === self::STEP_YEAR) {
            return $this->formatter->formatYear($format);
        }
    }

    public function isLastDayOfMonth()
    {
        return $this->converter->isLastDayOfMonth();
    }

    public function isLastDayOfQuarter()
    {
        return $this->converter->isLastDayOfQuarter();
    }

    public function isLastDayOfYear()
    {
        return $this->converter->isLastDayOfYear();
    }
}