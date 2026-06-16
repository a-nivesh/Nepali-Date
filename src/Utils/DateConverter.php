<?php

namespace Nivesh\NepaliDate\Utils;

use DateTime;
use Exception;
use Nivesh\NepaliDate\Exceptions\AdDateOutOfRangeException;
use Nivesh\NepaliDate\Exceptions\BsDateOutOfRangeException;
use Nivesh\NepaliDate\Traits\DateMapper;

class DateConverter {

    use DateMapper;

    const MAX_AD_YEAR = 2043;
    const MIN_AD_YEAR = 1943;

    const MAX_BS_YEAR = 2100;
    const MIN_BS_YEAR = 2000;

    public int $year, $month, $day, $weekday;

    public function convertToBs($date = null)
    {
        $date = $date ? $date : date('Y-m-d');

        $this->validate($date);

        $this->weekday = date('w', strtotime($date));

        $exploded = preg_split('/[-\/]/', $date);
        $data = array_filter($this->getNewYearRelativeEnglishDate(), function($eng) use ($exploded) {
            return strpos($eng, $exploded[0]) === 0;
        });
        if (count($data) === 0 ) {
            throw new AdDateOutOfRangeException("AD date out of range! Must be between " . self::MIN_AD_YEAR . " and " . self::MAX_AD_YEAR . ".");
        }

        $this->year = array_keys($data)[0];
        $this->month = 0;
        $this->day = 1;
        $relativeEnglishDate = $data[$this->year];
        $difference = $this->getDateDifference($date, $relativeEnglishDate);
        $this->calculateNepaliDateByDifference($difference);
    }

    public function convertToAd(string $date)
    {
        $matches = $this->validateDateRegex($date);
        if (!$matches) {
            throw new Exception("Invalid Date Provided!");
        }
        $year = (int)$matches['year'];
        $month = (int)$matches['month'];
        $day = (int)$matches['day'];
        $this->validateBsYear($year);
        if ($month > 12 || $month < 1) {
            throw new Exception("Invalid Date Provided!");
        }
        if ($day < 1 || $day > $this->getMonthwiseDays()[$year][$month-1]) {
            throw new Exception("Invalid Date Provided!");
        }
        $this->year = $year;
        $this->month = $month - 1;
        $this->day = $day;

        $adDate = $this->getNewYearRelativeEnglishDate()[$this->year];
        $nepaliMonths = $this->getMonthwiseDays()[$this->year];
        $days = array_slice($nepaliMonths, 0, $this->month);
        $difference = array_reduce($days, function($carry, $day) {
            return $carry + $day;
        }) + $this->day - 1;
        $timestamp = strtotime($adDate . " + $difference days");
        $this->weekday = intval(date('w', $timestamp));
        return date('Y-m-d', $timestamp);
    }

    private function validate(string $date)
    {
        if (!$this->checkIfCorrectDate($date)) {
            throw new Exception("Invalid Date Provided!");
        }
    }

    private function checkIfCorrectDate(string $date)
    {
        if (strpos($date, '-') !== false && strpos($date, '/') !== false) {
            return false;
        }
    
        $matches = $this->validateDateRegex($date);
        if (!$matches) {
            return false;
        }

        $year = (int)$matches['year'];
        $month = (int)$matches['month'];
        $day = (int)$matches['day'];
        $this->validateAdYear($year);
        
        return checkdate($month, $day, $year);
    }

    private function validateDateRegex(string $date)
    {
        $regex = '/^(?<year>\d{4})([-\/])(?<month>\d{2})\2(?<day>\d{2})$/';
        if (!preg_match($regex, $date, $matches)) {
            return false;
        }
        return $matches;
    }

    private function getDateDifference(string $date1, string $date2)
    {
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        return $date2->diff($date1)->format('%r%a');
    }

    private function calculateNepaliDateByDifference(int $difference)
    {
        if (!isset($this->year, $this->month, $this->day, $this->weekday)) {
            $this->convertToBs();
        }
        $mapper = $this->getMonthwiseDays();
        $difference = intval($difference);
        if ($difference === 0) {
            return;
        }

        while ($difference < 0 ) {
            if (abs($difference) >= $this->day) {
                if ($this->month > 0) {
                    $this->month--;
                }else {
                    $this->year--;
                    $this->validateBsYear($this->year);
                    $this->month = 11;
                }
                $difference += $this->day;
                $this->day = $mapper[$this->year][$this->month];
            } else {
                $this->day += $difference;
                $difference = 0;
            }
        }

        while ($difference > 0) {
            $remainingDays = $mapper[$this->year][$this->month] - $this->day;
            if ($difference > $remainingDays) {
                $difference -= $remainingDays;
                if ($this->month != 11) {
                    $this->month++;
                } else {
                    $this->month = 0;
                    $this->year++;
                    $this->validateBsYear($this->year);
                }
                $this->day = 0;
            } else {
                $this->day += $difference;
                $difference = 0;
            }
        }
    }

    public function calculateDateAndWeekday(int $difference)
    {
        $this->calculateNepaliDateByDifference($difference);
        $this->weekday = ($this->weekday + $difference) % 7;
    }

    public function addMonth(int $value)
    {
        $monthDiff = $this->month + $value;
        $yearsToAdd = floor($monthDiff / 12);
        $this->month = $monthDiff < 0 ? 12 + (($this->month + $value) % 12) : ($this->month + $value) % 12;
        $this->year += $yearsToAdd;
        $this->validateBsYear($this->year);
    }

    public function addQuarter(int $value)
    {
        $this->addMonth($value * 3);
    }

    public function addYear(int $value)
    {
        $this->year += $value;
        $this->validateBsYear($this->year);
    }

    public function isLastDayOfMonth()
    {
        return $this->day == $this->getMonthWiseDays()[$this->year][$this->month];
    }

    public function isLastDayOfQuarter()
    {
        return $this->day == $this->getMonthWiseDays()[$this->year][$this->month]  && in_array($this->month, [2, 5, 8, 11]);
    }

    public function isLastDayOfYear()
    {
        return $this->month == 11 && $this->day == $this->getMonthWiseDays()[$this->year][$this->month];
    }

    public function nepaliDate(string $date)
    {
        $this->convertToAd($date);
    }

    private function validateBsYear(string $year)
    {
        if ($year > self::MAX_BS_YEAR || $year < self::MIN_BS_YEAR) {
            throw new BsDateOutOfRangeException("BS date out of range! Must be between " . self::MIN_BS_YEAR . " and " . self::MAX_BS_YEAR . ".");
        }
    }

    private function validateAdYear(string $year)
    {
        if ($year > self::MAX_AD_YEAR || $year < self::MIN_AD_YEAR) {
            throw new AdDateOutOfRangeException("AD date out of range! Must be between " . self::MIN_AD_YEAR . " and " . self::MAX_AD_YEAR . ".");
        }
    }

    public function nepaliYear(int $year)
    {
        $this->validateBsYear($year);
        if (!isset($this->month) || !isset($this->day) || !isset($this->year)) {
            $this->convertToBs();
        }
        $this->year = $year;
    }

    public function nepaliMonth(int $month)
    {
        if ($month > 12 || $month < 1) {
            throw new Exception("Invalid Date Provided!");
        }

        if (!isset($this->month) || !isset($this->day) || !isset($this->year)) {
            $this->convertToBs();
        }
        $this->month = $month - 1;
    }

    public function nepaliDay(int $day)
    {
        if (!isset($this->month) || !isset($this->day) || !isset($this->year)) {
            $this->convertToBs();
        }

        if ($day < 1 || $day > $this->getMonthwiseDays()[$this->year][$this->month]) {
            throw new Exception("Invalid Date Provided!");
        }
        $this->day = $day;
    }
}