<?php

namespace Nivesh\App\Utils;

use DateTime;
use Exception;
use Nivesh\App\Exceptions\AdDateOutOfRangeException;
use Nivesh\App\Exceptions\BsDateOutOfRangeException;
use Nivesh\App\Traits\DateMapper;

class DateConverter {

    use DateMapper;

    const MAX_AD_YEAR = 2043;
    const MIN_AD_YEAR = 1943;

    const MAX_BS_YEAR = 2100;
    const MIN_BS_YEAR = 2000;

    public $year, $month, $day, $weekday;

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

    private function validate($date)
    {
        if (!$this->checkIfCorrectDate($date)) {
            throw new Exception("Invalid Date Provided!");
        }
    }

    private function checkIfCorrectDate($date)
    {
        if (strpos($date, '-') !== false && strpos($date, '/') !== false) {
            return false;
        }
    
        $regex = '/^(?<year>\d{4})([-\/])(?<month>\d{2})\2(?<day>\d{2})$/';
        if (!preg_match($regex, $date, $matches)) {
            return false;
        }

        $year = (int)$matches['year'];
        $month = (int)$matches['month'];
        $day = (int)$matches['day'];
        if ($year > self::MAX_AD_YEAR || $year < self::MIN_AD_YEAR) {
            throw new AdDateOutOfRangeException("AD date out of range! Must be between " . self::MIN_AD_YEAR . " and " . self::MAX_AD_YEAR . ".");
        }
        
        return checkdate($month, $day, $year);
    }

    private function getDateDifference($date1, $date2)
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
                    if ($this->year < self::MIN_BS_YEAR || $this->year > self::MAX_BS_YEAR) {
                        throw new BsDateOutOfRangeException("BS date out of range! Must be between " . self::MIN_BS_YEAR . " and " . self::MAX_BS_YEAR . ".");
                    }
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
                    if ($this->year > self::MAX_BS_YEAR || $this->year < self::MIN_BS_YEAR) {
                        throw new BsDateOutOfRangeException("BS date out of range! Must be between " . self::MIN_BS_YEAR . " and " . self::MAX_BS_YEAR . ".");
                    }
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
        $this->month = $monthDiff < 0 ? 12 - (($this->month + $value) % 12) : ($this->month + $value) % 12;
        $this->year += $yearsToAdd;
        if ($this->year > self::MAX_BS_YEAR || $this->year < self::MIN_BS_YEAR) {
            throw new BsDateOutOfRangeException("BS date out of range! Must be between " . self::MIN_BS_YEAR . " and " . self::MAX_BS_YEAR . ".");
        }
    }

    public function addQuater(int $value)
    {
        $this->addMonth($value * 3);
    }

    public function addYear(int $value)
    {
        $this->year += $value;
        if ($this->year > self::MAX_BS_YEAR || $this->year < self::MIN_BS_YEAR) {
            throw new BsDateOutOfRangeException("BS date out of range! Must be between " . self::MIN_BS_YEAR . " and " . self::MAX_BS_YEAR . ".");
        }
    }
}