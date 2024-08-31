<?php

use App\Libs\Config;
use App\Libs\CsrfTokenManager;
use App\Libs\Flash;
use App\Libs\Translation;

function trans($file, $key) {
    return Translation::trans($file, $key);
}

function config($file, $key, $valueDefault = '')
{
    return Config::get($file, $key, $valueDefault);
}

function csrf_field()
{
    $token = CsrfTokenManager::generateToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

$sections = [];
$currentSection = null;
$layout = null;

function startSection($section)
{
    global $sections, $currentSection;
    $currentSection = $section;
    ob_start();
}

function endSection()
{
    // Save the content in the corresponding section
    global $sections, $currentSection;
    if ($currentSection !== null) {
        $sections[$currentSection] = ob_get_clean(); 
        $currentSection = null;
    }
}

function yieldSection($section)
{
    // Returns the content of the section if defined
    global $sections;
    return $sections[$section] ?? ''; 
}

function extendsLayout($layoutName)
{
    global $layout;
    $layout = $layoutName;
}

function renderLayout()
{
    global $layout, $sections;

    // Asegúrate de que haya un layout definido
    if ($layout === null) {
        return ''; 
    }

    // Ruta del archivo del layout
    $layoutPath = dirname(__DIR__, 2) . "/resources/views/{$layout}.php";
    
    if (!file_exists($layoutPath)) {
        return "Layout file does not exist: $layoutPath";
    }

    // Iniciar el buffer de salida para el layout
    ob_start();
    include $layoutPath;
    $layoutContent = ob_get_clean();

    // Filtrar el contenido del layout según la autenticación
    $layoutContent = filterContentByAuth($layoutContent);

    // Reemplaza las secciones en el contenido del layout
    foreach ($sections as $section => $content) {
        $layoutContent = str_replace("@yield('{$section}')", $content, $layoutContent);
    }

    $layoutContent = str_replace('@csrf', csrf_field(), $layoutContent);

    return $layoutContent;
}

// Función para filtrar contenido basado en la autenticación
function filterContentByAuth($content)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Determina si el usuario está autenticado
    $isAuthenticated = isset($_SESSION['user']);

    // Filtrar contenido basado en la autenticación
    $content = preg_replace_callback('/@guest(.*?)@endguest/s', function ($matches) use ($isAuthenticated) {
        return !$isAuthenticated ? $matches[1] : '';
    }, $content);

    $content = preg_replace_callback('/@auth(.*?)@endauth/s', function ($matches) use ($isAuthenticated) {
        return $isAuthenticated ? $matches[1] : '';
    }, $content);

    return $content;
}

function isCurrentUrl($url)
{
    // Obtiene la URL actual desde el servidor
    $currentUrl = $_SERVER['REQUEST_URI'];

    // Normaliza ambas URLs eliminando las barras al inicio y al final
    $normalizedCurrentUrl = trim($currentUrl, '/');
    $normalizedProvidedUrl = trim($url, '/');

    // Compara la URL actual con la URL proporcionada
    return $normalizedCurrentUrl === $normalizedProvidedUrl;
}

function getCurrentLang()
{
    return Translation::getLang();
}

function has_flash($key)
{
    return Flash::has($key);
}

function get_flash($key)
{
    return Flash::get($key);
}