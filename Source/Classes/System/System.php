<?php

declare(strict_types=1);

namespace Clover\Classes\System;

class Output
{
    public static function print($content)
    {
        echo $content;
    }

    public static function printFormat($format, ...$values)
    {
        printf($format, ...$values);
    }
}
