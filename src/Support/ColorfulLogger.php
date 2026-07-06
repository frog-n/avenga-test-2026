<?php

namespace App\Support;

class ColorfulLogger
{
    // ANSI Color Codes
    const string CLR_RESET = "\e[0m";

    const string CLR_SUCCESS = "\e[1;32m"; // Bold Green

    const string CLR_FAIL = "\e[1;31m"; // Bold Red

    const string CLR_WARN = "\e[1;33m"; // Bold Yellow

    const string CLR_INFO = "\e[1;36m"; // Bold Cyan

    const string CLR_TITLE = "\e[1;35m"; // Bold Magenta

    public function log(string $format, mixed ...$values): void
    {
        echo $this->parse($format, ...$values);
    }

    protected function parse(string $format, mixed ...$values): string
    {
        $string = empty($values) ? $format : sprintf($format, ...$values);

        return $this->cast($string);
    }

    protected function cast(string $string): string
    {
        return str_replace([
                               '{reset}',
                               '{success}',
                               '{fail}',
                               '{warn}',
                               '{info}',
                               '{title}',
                           ], [
                               self::CLR_RESET,
                               self::CLR_SUCCESS,
                               self::CLR_FAIL,
                               self::CLR_WARN,
                               self::CLR_INFO,
                               self::CLR_TITLE,
                           ], $string);
    }
}
