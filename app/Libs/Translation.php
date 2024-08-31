<?php

namespace App\Libs;

class Translation
{
    protected static $translations = [];

    public static function getLang()
    {
        // Make sure the session is logged in
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // If a 'lang' parameter is passed in the URL, refresh the session
        if (isset($_GET['lang'])) {
            $_SESSION['lang'] = $_GET['lang'];
        }

        // Get the language from the session, default 'en'
        return $_SESSION['lang'] ?? 'en';
    }

    protected static function loadTranslations($file)
    {
        $lang = self::getLang();
        $path = dirname(__DIR__, 2) . "/lang/{$lang}/{$file}.php";

        if (file_exists($path)) {
            self::$translations = include($path);
        } else {
            self::$translations = [];
        }
    }

    public static function trans($file, $key)
    {
        // Upload specific translation file
        self::loadTranslations($file);

        return self::$translations[$key] ?? $key;
    }
}
