<?php

namespace Nivesh\App\Traits;

use Exception;
use Nivesh\App\Utils\DateConverter;

trait DateFormatter {
    private $converter;
    public $MAX_ENG_YEAR = 2044;
    public $MIN_ENG_YEAR = 1942;

    public function __construct()
    {
        $this->converter = new DateConverter();
    }

    protected function engDate($date = null)
    {
        if(!$date)
        {
            $date = date('Y-m-d');
        } else {
            $validated = $this->validate($date);
            if (!$validated) {
                throw new Exception("Invalid Date Provided!");
            }
        }
        $this->date = $date;
        $this->nepaliDate = $this->converter->convertToNepali($this->date);
        $this->exploded = preg_split('/[-\/]/', $this->nepaliDate);
        $this->exploded[] = date('w', strtotime($this->date));
        return $this;
    }

    public function __toString()
    {
        return $this->format();
    }

    private function validate($date)
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
        if ($year > $this->MAX_ENG_YEAR || $year < $this->MIN_ENG_YEAR) {
            return false;
        }
        
        return checkdate($month, $day, $year);
    }

    private function extract($part = "year")
    {
        switch ($part)
        {
            case "day":    
                return $this->exploded[2];
            case "month":    
                return $this->exploded[1];
            case "weekday":
                return $this->exploded[3];
            default:
                return $this->exploded[0];
        }
    }

    public function format($format = "{Y}-{m}-{d}")
    {
        $segments = $this->getAllFormats();
        return str_replace(array_keys($segments), array_values($segments), $format);
    }

    public function formatMonth($format = "{m}")
    {
        $segments = $this->getMonthFormats();
        return str_replace(array_keys($segments), array_values($segments), $format);
    }

    public function formatQuater($format = "{Q}")
    {
        $segments = $this->getQuaterFormats();
        return str_replace(array_keys($segments), array_values($segments), $format);
    }

    public function formatFiscalYear($format = "{F}")
    {
        $segments = $this->getFiscalYearFormats();
        return str_replace(array_keys($segments), array_values($segments), $format);
    }

    private function getAllFormats()
    {
        $dateFormatts = [
            '{Y}'       => $this->resolveYear(),
            '{y}'       => $this->resolveTwoDigitYear(),
            '{d}'       => $this->resolveDay(),
            '{ds}'      => $this->resolveNonZeroLeadingDay(),
            '{w}'       => $this->resolveWeekDay(),
            '{wi}'      => $this->resolveWeekDayIndex(),
            '{ws}'      => $this->resolveShortWeekDay(),
            '{%Y}'      => $this->resolveYear(true),
            '{%y}'      => $this->resolveTwoDigitYear(true),
            '{%d}'      => $this->resolveDay(true),
            '{%ds}'     => $this->resolveNonZeroLeadingDay(true),
            '{%w}'      => $this->resolveWeekDay(true),
            '{%wi}'     => $this->resolveWeekDayIndex(true),
            '{%ws}'     => $this->resolveShortWeekDay(true),
        ];
        return array_merge($dateFormatts, $this->getMonthFormats(), $this->getQuaterFormats(), $this->getQuaterFormats());
    }

    private function getMonthFormats()
    {
        return [
            '{m}'       => $this->resolveMonth(),
            '{ms}'      => $this->resolveNonZeroLeadingMonth(),
            '{M}'       => $this->resolveMonthName(),
            '{Mq}'      => $this->resolveQuaterlyMonth(),
            '{Mqi}'     => $this->resolveQuaterlyMonthIndex(),
            '{%m}'      => $this->resolveMonth(true),
            '{%ms}'     => $this->resolveNonZeroLeadingMonth(true),
            '{%M}'      => $this->resolveMonthName(true),
            '{%Mqi}'    => $this->resolveQuaterlyMonthIndex(true),
        ];
    }

    private function getQuaterFormats()
    {
        return [
            '{Q}'       => $this->resolveQuater(),
            '{Qi}'      => $this->resolveQuaterIndex(),
            '{%Qi}'     => $this->resolveQuaterIndex(true),
        ];
    }

    private function getFiscalYearFormats()
    {
        return [
            '{F}'       => $this->resolveFiscalYear(),
            '{%F}'      => $this->resolveFiscalYear(true),
        ];
    }

    private function resolveYear($nepali = false)
    {
        $year = $this->extract("year");
        return $nepali ? $this->convertNumber($year) : $year;
    }

    private function resolveTwoDigitYear($nepali = false)
    {
        $year = substr($this->resolveYear(), 2);
        return $nepali ? $this->convertNumber($year) : $year;
    }

    private function resolveMonth($nepali = false)
    {
        $month = $this->extract("month");
        return $nepali ? $this->convertNumber($month) : $month;

    }

    private function resolveNonZeroLeadingMonth($nepali = false)
    {
        $month = intval($this->resolveMonth());
        return $nepali ? $this->convertNumber($month) : $month;
    }

    private function resolveDay($nepali = false)
    {
        $day = $this->extract("day");
        return $nepali ? $this->convertNumber($day) : $day;
    }

    private function resolveNonZeroLeadingDay($nepali = false)
    {
        $day = intval($this->resolveDay());
        return $nepali ? $this->convertNumber($day) : $day;
    }

    private function resolveWeekDayIndex($nepali = false)
    {
        $day = $this->extract("weekday");
        return $nepali ? $this->convertNumber($day) : $day;
    }

    private function convertNumber($number)
    {
        $nepaliNumbers = $this->converter->getNepaliNumbers();
        return str_replace(array_keys($nepaliNumbers), $nepaliNumbers, $number);
    }

    private function resolveMonthName($nepali = false)
    {
        $monthIndex = $this->resolveNonZeroLeadingMonth();
        return $nepali ? $this->converter->getNepaliMonthName()[$monthIndex - 1][0] : $this->converter->getNepaliMonthName()[$monthIndex - 1][1];
    }

    private function resolveWeekDay($nepali = false)
    {
        $weekDayIndex = $this->resolveWeekDayIndex();
        return $nepali ? $this->converter->getFullDays()[$weekDayIndex][0] : $this->converter->getFullDays()[$weekDayIndex][1];
    }

    private function resolveShortWeekDay($nepali = false)
    {
        $weekDayIndex = $this->resolveWeekDayIndex();
        return $nepali ? $this->converter->getShortDays()[$weekDayIndex][0] : $this->converter->getFullDays()[$weekDayIndex][1];
    }

    private function resolveFiscalYear($nepali = false)
    {
        $year = $this->resolveYear();
        $fiscalYear = $this->converter->getFiscalYearMapping()[$year];
        return $nepali ? $this->convertNumber($fiscalYear) : $fiscalYear;
    }

    private function resolveQuater()
    {
        $month = $this->resolveNonZeroLeadingMonth();
        return $this->converter->getQuaterMapping()[$month - 1][1];
    }

    private function resolveQuaterlyMonth()
    {
        $month = $this->resolveNonZeroLeadingMonth();
        return $this->converter->getMonthMapping()[$month - 1][1];
    }

    private function resolveQuaterIndex($nepali = false)
    {
        $month = $this->resolveNonZeroLeadingMonth();
        $index =  $this->converter->getQuaterMapping()[$month - 1][0];
        return $nepali ? $this->convertNumber($index) : $index;
    }

    private function resolveQuaterlyMonthIndex($nepali = false)
    {
        $month = $this->resolveNonZeroLeadingMonth();
        $index =  $this->converter->getMonthMapping()[$month - 1][0];
        return $nepali ? $this->convertNumber($index) : $index;
    }

    private function getNextMonth($format)
    {
        $this->engDate();
        $this->exploded[1] = ($this->exploded[1] % 12) + 1;
        return $this->formatMonth($format);
    }

    private function getCurrentMonth($format)
    {
        $this->engDate();
        return $this->formatMonth($format);
    }

    private function getPreviousMonth($format)
    {
        $this->engDate();
        $this->exploded[1] = ($this->exploded[1] - 1) ? $this->exploded[1] - 1 : 12;
        return $this->formatMonth($format);
    }

    private function getNextQuater($format)
    {
        $this->engDate();
        $this->exploded[1] = ($this->exploded[1] + 3 > 12) ? ($this->exploded[1] - 9) : $this->exploded[1] + 3;
        return $this->formatQuater($format);
    }

    private function getCurrentQuater($format)
    {
        $this->engDate();
        return $this->formatQuater($format);
    }

    private function getPreviousQuater($format)
    {
        $this->engDate();
        $this->exploded[1] = ($this->exploded[1] - 3) > 0 ? ($this->exploded[1] - 3) : 9 + $this->exploded[1];
        return $this->formatQuater($format);
    }

    private function getNextFiscalYear($format)
    {
        $this->engDate();
        $this->exploded[0] = $this->exploded[0] + 1;
        return $this->formatFiscalYear($format);
    }

    private function getCurrentFiscalYear($format)
    {
        $this->engDate();
        return $this->formatFiscalYear($format);
    }

    private function getPreviousFiscalYear($format)
    {
        $this->engDate();
        $this->exploded[0] = $this->exploded[0] - 1;
        return $this->formatFiscalYear($format);
    }
}