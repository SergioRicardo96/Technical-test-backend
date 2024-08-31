<?php

namespace App\Libs;

use Exception;

class Dotenv
{
    private static $variables = [];

    public static function load($path)
    {
        if (!file_exists($path)) {
            throw new Exception("The .env file is not located in the specified path.");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2) + [null, null];

            if ($key !== null && $value !== null) {
                $key = trim($key);
                $value = trim($value);
                putenv("$key=$value");
                $_ENV[$key] = $value;
                self::$variables[$key] = $value;
            }
        }
    }

    public static function get($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
