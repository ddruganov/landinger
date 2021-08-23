<?php

namespace app\components;

class ErrorLog
{
    private static array $styles = [
        'bold' => "\033[1m%s\033[0m",
        'dark' => "\033[2m%s\033[0m",
        'italic' => "\033[3m%s\033[0m",
        'underline' => "\033[4m%s\033[0m",
        'blink' => "\033[5m%s\033[0m",
        'reverse' => "\033[7m%s\033[0m",
        'concealed' => "\033[8m%s\033[0m",
    ];

    private static $fontColors = [
        'black' => "\033[30m%s\033[0m",
        'red' => "\033[31m%s\033[0m",
        'green' => "\033[32m%s\033[0m",
        'yellow' => "\033[33m%s\033[0m",
        'blue' => "\033[34m%s\033[0m",
        'magenta' => "\033[35m%s\033[0m",
        'cyan' => "\033[36m%s\033[0m",
        'white' => "\033[37m%s\033[0m",
    ];
    private static $bgColors = [
        'black' => "\033[40m%s\033[0m",
        'red' => "\033[41m%s\033[0m",
        'green' => "\033[42m%s\033[0m",
        'yellow' => "\033[43m%s\033[0m",
        'blue' => "\033[44m%s\033[0m",
        'magenta' => "\033[45m%s\033[0m",
        'cyan' => "\033[46m%s\033[0m",
        'white' => "\033[47m%s\033[0m",
    ];

    private static string $format = '';

    public static function log(...$dataPieces)
    {
        !self::$format && self::config();

        $data = join(' ', array_map(function ($piece) {
            $doNotExport = is_string($piece) || is_numeric($piece);
            return $doNotExport ? $piece : var_export($piece, true);
        }, $dataPieces));

        error_log(sprintf(self::$format, PHP_EOL . $data));
    }

    public static function config(string $fontColor = 'blue', string $bgColor = 'white', string $style = 'bold')
    {
        self::$format = '';
        if (isset(self::$styles[$style])) {
            self::$format = self::$styles[$style];
        }
        if (isset(self::$bgColors[$bgColor])) {
            self::$format = str_replace('%s', self::$bgColors[$bgColor], self::$format);
        }
        if (isset(self::$fontColors[$fontColor])) {
            self::$format = str_replace('%s', self::$fontColors[$fontColor], self::$format);
        }
    }
}
