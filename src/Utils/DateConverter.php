<?php

namespace Nivesh\App\Utils;

use DateTime;
use Nivesh\App\Traits\DateMapper;
use Nivesh\App\Exceptions\DateOutOfRangeException;

Class DateConverter {

    use DateMapper;

    public function convertToNepali($date)
    {
        $exploded = preg_split('/[-\/]/', $date);
        $data = array_filter($this->getNewYearRelativeEnglishDate(), function($eng) use ($exploded) {
            return strpos($eng, $exploded[0]) === 0;
        });
        if (count($data) === 0 ) {
            throw new DateOutOfRangeException();
        }

        $nepaliYear = array_keys($data)[0];
        $relativeEnglishDate = $data[$nepaliYear];
        $difference = $this->getDateDifference($date, $relativeEnglishDate);
        return $this->getNepaliDateByDifference($nepaliYear, $difference);
    }

    private function getDateDifference($date1, $date2)
    {
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        return $date2->diff($date1)->format('%r%a');
    }

    private function getNepaliDateByDifference($nepaliYear, $difference)
    {
        $month = 1;
        $day = 1;
        $mapper = $this->getMonthwiseDays();
        $difference = intval($difference);
        if ($difference === 0) {
            return "$nepaliYear-$month-$day";
        }

        if ($difference < 0) {
            $nepaliYear--;
            $month = 11;
            $day = $mapper[$nepaliYear][$month];
            $difference += 1;
        }
        while ($difference < 0 ) {
            if (abs($difference) >= $mapper[$nepaliYear][$month]) {
                $difference += $mapper[$nepaliYear][$month];
                $month--;
                if ($month < 0) {
                    $nepaliYear--;
                    $month = 11;
                    continue;
                }
                $day = $mapper[$nepaliYear][$month];
            } else {
                $day = $mapper[$nepaliYear][$month] + $difference;
                $difference = 0;
            }
        }

        while ($difference > 0) {
            if ($difference >= $mapper[$nepaliYear][$month]) {
                $difference -= $mapper[$nepaliYear][$month];
                $month++;
                if ($month > 11) {
                    $nepaliYear++;
                    $month = 0;
                    continue;
                }
                $day = 1;
            } else {
                $day = $difference;
                $difference = 0;
            }
        }
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $day = str_pad($day, 2, '0', STR_PAD_LEFT);
        return "$nepaliYear-$month-$day";
    }
}