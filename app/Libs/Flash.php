<?php

namespace App\Libs;

class Flash
{
    public static function set($key, $message)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash'][$key] = $message;
    }

    public static function get($key)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]); // Elimina el mensaje después de obtenerlo
            return $message;
        }
        return null;
    }

    public static function has($key)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['flash'][$key]);
    }
}
