<?php

namespace App\Libs;

use Exception;

class Config
{
    private static $configCache = [];

    private static function loadConfig($filePath)
    {
        $filePath = dirname(__DIR__, 2) . '/config/' . $filePath;
        if (!isset(self::$configCache[$filePath])) {
            if (file_exists($filePath)) {
                self::$configCache[$filePath] = include($filePath);
            } else {
                throw new Exception("Configuration file not found: $filePath");
            }
        }
        
        return self::$configCache[$filePath];
    }

    public static function get($filePath, $key, $default = null)
    {
        $path = str_replace('.', '/', $filePath) . '.php';
        $config = self::loadConfig($path);

        $keys = explode('.', $key);

        foreach ($keys as $segment) {
            if (isset($config[$segment])) {
                $config = $config[$segment];
            } else {
                return $default;
            }
        }

        return $config;
    }
}
