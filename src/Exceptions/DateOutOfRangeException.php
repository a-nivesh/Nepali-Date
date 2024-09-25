<?php

namespace Nivesh\App\Exceptions;

use RuntimeException;

class DateOutOfRangeException
{

    public function __construct()
    {
        new RuntimeException("Date out of range! Must be between 1975 and 2110.");
    }
}