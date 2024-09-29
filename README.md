# Nepali Date Converter - AD to BS

`NepaliDate` is a PHP package that allows you to seamlessly convert English (AD) dates to Nepali (Bikram Sambat - BS) dates. This package provides an easy-to-use interface to work with Nepali dates, supporting features like retrieving individual date components (year, month, day) and handling complex date formatting.

## Features
- Convert AD date to BS (Bikram Sambat) format.
- Flexible interface for working with date conversions in a clean, readable syntax.
- Handles chaining function. Eg: ```NepaliDate::tomorrow()->nextWeek()->afterDays(4)->nextMonth();```
- Handles leap years and varying month lengths in the Nepali calendar.

## Installation

You can install the package via Composer: <br>
Add this line inside your composer repositories, if does not exist.
```
"repositories": [
    //...,
    {"type": "composer", "url": "https://packagist.wlink.com.np/repo/private/"}
]
```

```code
composer require nivesh/nepali-date
```
## Usage
```code
use Nivesh\NepaliDate\NepaliDate;
```

```code
echo NepaliDate::convertToBs(); // Outputs "2081-06-09"
echo NepaliDate::convertToBs('2024-09-25', "{Y}-{M}-{d}, {w}"); // Outputs "2081-Asoj-09, Wednesday"
echo NepaliDate::today("{%d} {%M} {%Y}"); // Outputs "०९ असोज २०८१"
echo NepaliDate::previousQuater(); // Outputs "Q4"
echo NepaliDate::nextMonth("{Mq}"); // Outputs "M2"
echo NepaliDate::currentYear(); // Outputs "2081"
```

## Available Methods
|   Method | Params | Return |Remarks |
|-----------------------------|---------------------|----------------------------------------------|----------------|
| ```convertToBs(string $date, string format)``` | date ```default:today```<br>format ```default:{Y}-{m}-{d}``` | ```obj\|string``` | Arg ```$date```:compatible formats YYYY-MM-DD or YYYY/MM/DD |
| ```today(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | |
| ```yesterday(string $format)``` | format ```default:{Y}-{m}-{d}```|```obj\|string``` | |
| ```tomorrow(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | |
| ```daysBefore(int $days, string $format)``` | days ```required``` <br>format ```default:{Y}-{m}-{d}```| ```obj\|string``` | |
| ```daysAfter(int $days, string $format)``` | days ```required``` <br>format ```default:{Y}-{m}-{d}```| ```obj\|string``` | |
| ```nextWeek(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | |
| ```previousWeek(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | |
| ```currentMonth(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | Only month formatters|
| ```nextMonth(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | Only month formatters|
| ```previousMonth(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | Only month formatters|
| ```currentQuater(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | Only quater formatters|
| ```nextQuater(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | Only quater formatters|
| ```previousQuater(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | Only quater formatters|
| ```currentYear(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | Only year formatters|
| ```nextYear(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | Only year formatters|
| ```previousYear(string $format)``` | format ```default:{Y}-{m}-{d}```| ```obj\|string``` | Only year formatters|


## Formatters

| Formatters | Details                               | Example                      | Remarks                                  |
|-------------|--------------------------------------|------------------------------|------------------------------------------|
|```{Y}```    | 4 digit year                         | ```2081```                   |                                  Falls under ```year formatters```        |
|```{y}```    | 2 digit year                         | ```81```                     |   Falls under ```year formatters```                                       |
|```{m}```    | Month digit                          | ```01``` for Baisakh         | Falls under ```month formatters```       |
|```{ms}```   | No leading 0 in month                | ```1```                      | Falls under ```month formatters```       |
|```{M}```    | Full month name                      | ```Asoj```                   | Falls under ```month formatters```       |
|```{d}```    | 2 digit day                          | ```09```                     |                                          |
|```{ds}```   | No leading 0 day                     | ```9```                      |                                          |
|```{w}```    | Full weekday                         | ```Sunday```                 |                                          |
|```{ws}```   | Shorthand weekday                    | ```Sun```                    |                                          |
|```{wi}```   | Weekday index                        | ```0-6``` 0 for Sunday       |                                          |
|```{F}```    | Fiscal year                          | ```2081/81```                | Falls under ```year formatters``` |
|```{Q}```    | Quater                               | ```Q1```                     | Falls under ```quater formatters```      |
|```{Qi}```   | Quater index                         | ```4``` for Q4               | Falls under ```quater formatters```      |
|```{Mq}```   | Month in quater                      | ```M3```                     | Falls under ```month formatters ```      |
|```{Mqi}```  | Month index in quater                | ```1``` for M1               | Falls under ```month formatters```       |
|```{%Y}```   | 4 digit year in Nepali               | ```२०८१```                   |                                          |
|```{%y}```   | 2 digit year in Nepali               | ```८१```                     |                                          |
|```{%m}```   | Month digit in Nepali                | ```०९```                     | Falls under ```month formatters```       |
|```{%ms}```  | No leading 0 month in Nepali         | ```९```                      | Falls under ```month formatters```       |
|```{%M}```   | Full month name in Nepali            | ```असोज```                   | Falls under ```month formatters```       |
|```{%d}```   | 2 digit day in Nepali                | ```०९```                     |                                          |
|```{%ds}```  | No leading 0 day in Nepali           | ```९```                      |                                          |
|```{%w}```   | Full weekday in Nepali               | ```आइतबार```                  |                                          |
|```{%ws}```  | Shorthand weekday in Nepali          | ```आइत```                    |                                          |
|```{%wi}```  | Weekday index in Nepali              | ```०-६``` ० for आइतबार        |                                          |
|```{%F}```   | Fiscal year in Nepali                | ```२०८१/८२```                | Falls under ```year formatters``` |
|```{%Qi}```  | Quater index in Nepali               | ```२``` for Q2               |                                          |
|```{%Mqi}``` | Month index in quater in Nepali      | ```२``` for M2               | Falls under ```month formatters```       |
|```#```      | Escape Character                     | ```{#Y}```                   | ```echo NepaliDate::convertToBs("2024-09-25", "{#Y} means year. Eg: {Y}");``` <br> ```//Outputs: {Y} means year. Eg: 2081```      |

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.