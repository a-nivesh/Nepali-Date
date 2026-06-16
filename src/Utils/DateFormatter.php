<?php

namespace Nivesh\NepaliDate\Utils;

use Nivesh\NepaliDate\Traits\DateMapper;

class DateFormatter {

    use DateMapper;

    private $converter;
    public $ESCAPE_CHAR = '#';
    public int $year, $month, $day, $weekday;

    public function format(string $format)
    {
        $format = $format ? $format : '{Y}-{m}-{d}';
        $segments = $this->getAllFormats();
        return $this->handleEscape(str_replace(array_keys($segments), array_values($segments), $format));
    }

    public function formatMonth(string $format)
    {
        $format = $format ? $format : '{m}';
        $segments = $this->getMonthFormats();
        return $this->handleEscape(str_replace(array_keys($segments), array_values($segments), $format));
    }

    public function formatQuarter(string $format)
    {
        $format = $format ? $format : '{Q}';
        $segments = $this->getQuarterFormats();
        return $this->handleEscape(str_replace(array_keys($segments), array_values($segments), $format));
    }

    public function formatYear(string $format)
    {
        $format = $format ? $format : '{Y}';
        $segments = $this->getYearFormats();
        return $this->handleEscape(str_replace(array_keys($segments), array_values($segments), $format));
    }

    private function handleEscape(string $string)
    {
        return str_replace("{". $this->ESCAPE_CHAR, '{', $string);
    }

    private function getAllFormats()
    {
        $dateFormats = [
            '{d}'       => $this->resolveDay(),
            '{ds}'      => $this->resolveNonZeroLeadingDay(),
            '{w}'       => $this->resolveWeekDay(),
            '{wi}'      => $this->resolveWeekDayIndex(),
            '{ws}'      => $this->resolveShortWeekDay(),
            '{%d}'      => $this->resolveDay(true),
            '{%ds}'     => $this->resolveNonZeroLeadingDay(true),
            '{%w}'      => $this->resolveWeekDay(true),
            '{%wi}'     => $this->resolveWeekDayIndex(true),
            '{%ws}'     => $this->resolveShortWeekDay(true),
        ];
        return array_merge($dateFormats, $this->getMonthFormats());
    }

    private function getMonthFormats()
    {
        $monthFormats = [
            '{m}'       => $this->resolveMonth(),
            '{ms}'      => $this->resolveNonZeroLeadingMonth(),
            '{M}'       => $this->resolveMonthName(),
            '{Mq}'      => $this->resolveQuarterlyMonth(),
            '{Mqi}'     => $this->resolveQuarterlyMonthIndex(),
            '{%m}'      => $this->resolveMonth(true),
            '{%ms}'     => $this->resolveNonZeroLeadingMonth(true),
            '{%M}'      => $this->resolveMonthName(true),
            '{%Mqi}'    => $this->resolveQuarterlyMonthIndex(true),
        ];
        return array_merge($monthFormats, $this->getQuarterFormats());
    }

    private function getQuarterFormats()
    {
        $quaterFormats = [
            '{Q}'       => $this->resolveQuarter(),
            '{Qi}'      => $this->resolveQuarterIndex(),
            '{%Qi}'     => $this->resolveQuarterIndex(true),
        ];
        return array_merge($quaterFormats, $this->getYearFormats());
    }

    private function getYearFormats()
    {
        return [
            '{Y}'       => $this->resolveYear(),
            '{y}'       => $this->resolveTwoDigitYear(),
            '{%Y}'      => $this->resolveYear(true),
            '{%y}'      => $this->resolveTwoDigitYear(true),
            '{F}'       => $this->resolveFiscalYear(),
            '{%F}'      => $this->resolveFiscalYear(true),
        ];
    }

    private function resolveYear($nepali = false)
    {
        $year = $this->year;
        return $nepali ? $this->convertNumber($year) : $year;
    }

    private function resolveTwoDigitYear($nepali = false)
    {
        $year = substr($this->resolveYear(), 2);
        return $nepali ? $this->convertNumber($year) : $year;
    }

    private function resolveMonth($nepali = false)
    {
        $month = str_pad($this->month + 1, 2, '0', STR_PAD_LEFT);
        return $nepali ? $this->convertNumber($month) : $month;

    }

    private function resolveNonZeroLeadingMonth($nepali = false)
    {
        $month = $this->month + 1;
        return $nepali ? $this->convertNumber($month) : $month;
    }

    private function resolveDay($nepali = false)
    {
        $day = str_pad($this->day, 2, '0', STR_PAD_LEFT);
        return $nepali ? $this->convertNumber($day) : $day;
    }

    private function resolveNonZeroLeadingDay($nepali = false)
    {
        $day = intval($this->resolveDay());
        return $nepali ? $this->convertNumber($day) : $day;
    }

    private function resolveWeekDayIndex($nepali = false)
    {
        $day = $this->weekday;
        return $nepali ? $this->convertNumber($day) : $day;
    }

    private function convertNumber(string $number)
    {
        $nepaliNumbers = $this->getNepaliNumbers();
        return str_replace(array_keys($nepaliNumbers), $nepaliNumbers, $number);
    }

    private function resolveMonthName($nepali = false)
    {
        return $nepali ? $this->getNepaliMonthName()[$this->month][0] : $this->getNepaliMonthName()[$this->month][1];
    }

    private function resolveWeekDay($nepali = false)
    {
        $weekDayIndex = $this->resolveWeekDayIndex();
        return $nepali ? $this->getFullDays()[$weekDayIndex][0] : $this->getFullDays()[$weekDayIndex][1];
    }

    private function resolveShortWeekDay($nepali = false)
    {
        $weekDayIndex = $this->resolveWeekDayIndex();
        return $nepali ? $this->getShortDays()[$weekDayIndex][0] : $this->getFullDays()[$weekDayIndex][1];
    }

    private function resolveFiscalYear($nepali = false)
    {
        $year = $this->month > 3 ? $this->year : $this->year - 1;
        $fiscalYear = $this->getFiscalYearMapping()[$year];

        return $nepali ? $this->convertNumber($fiscalYear) : $fiscalYear;
    }

    private function resolveQuarter()
    {
        return $this->getQuarterMapping()[$this->month][1];
    }

    private function resolveQuarterlyMonth()
    {
        return $this->getMonthMapping()[$this->month][1];
    }

    private function resolveQuarterIndex($nepali = false)
    {
        $index =  $this->getQuarterMapping()[$this->month][0];
        return $nepali ? $this->convertNumber($index) : $index;
    }

    private function resolveQuarterlyMonthIndex($nepali = false)
    {
        $index =  $this->getMonthMapping()[$this->month][0];
        return $nepali ? $this->convertNumber($index) : $index;
    }
}