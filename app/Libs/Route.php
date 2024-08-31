<?php

namespace App\Libs;

class Route
{
    private static $routes = [];
    private static $middlewares = [];

    public static function loadMiddlewareConfig($path)
    {
        if (file_exists($path)) {
            $middlewareConfig = include $path;
            self::$middlewares = $middlewareConfig;
        }
    }

    public static function get($uri, $callback, $middlewares = [])
    {
        $uri = trim($uri, '/');
        self::$routes['GET'][$uri] = ['callback' => $callback, 'middlewares' => $middlewares];
    }

    public static function post($uri, $callback, $middlewares = [])
    {
        $uri = trim($uri, '/');
        self::$routes['POST'][$uri] = ['callback' => $callback, 'middlewares' => $middlewares];
    }

    public static function put($uri, $callback, $middlewares = [])
    {
        $uri = trim($uri, '/');
        self::$routes['PUT'][$uri] = ['callback' => $callback, 'middlewares' => $middlewares];
    }

    public static function delete($uri, $callback, $middlewares = [])
    {
        $uri = trim($uri, '/');
        self::$routes['DELETE'][$uri] = ['callback' => $callback, 'middlewares' => $middlewares];
    }

    public static function middleware($name, $middleware)
    {
        self::$middlewares[$name] = $middleware;
    }

    public static function generateCsrfToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $newCsrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $newCsrfToken;
    }

    public static function dispatch()
    {
        $uri = trim(urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/');
        $method = $_SERVER['REQUEST_METHOD'];

        // Handle _method field for PUT and DELETE
        if (isset($_POST['_method']) && in_array($_POST['_method'], ['PUT', 'DELETE'])) {
            $method = $_POST['_method'];
        }

        if (isset(self::$routes[$method])) {
            foreach(self::$routes[$method] as $route => $routeData){
                // Convert route parameters to a regular expression pattern.
                $pattern = preg_replace('#\{[^}]+\}#u', '([^/]+)', $route);
                
                if (preg_match("#^$pattern$#u", $uri, $matches)) {
                    $params = array_slice($matches, 1);
                    $middlewares = $routeData['middlewares'] ?? [];
                    $callback = $routeData['callback'];

                    $request = ['uri' => $uri, 'method' => $method, 'params' => $params];

                    $middlewareQueue = array_map(function ($middlewareName) {
                        return self::$middlewares[$middlewareName] ?? null;
                    }, $middlewares);

                    $response = function($request) use ($callback) {
                        if (is_callable($callback)) {
                            return call_user_func_array($callback, $request['params']);
                        } else if (is_array($callback)) {
                            list($controller, $method) = $callback;
                            $controllerInstance = new $controller;
                            return call_user_func_array([$controllerInstance, $method], $request['params']);
                        }
                    };

                    foreach (array_reverse($middlewareQueue) as $middleware) {
                        if ($middleware) {
                            $middlewareInstance = new $middleware;
                            $response = function($request) use ($middlewareInstance, $response) {
                                return $middlewareInstance->handle($request, $response);
                            };
                        }
                    }

                    $response = $response($request);

                    if (is_array($response) || is_object($response)) {
                        header('Content-Type: application/json');
                        echo json_encode($response);
                    } else {
                        echo $response;
                    }
                    return;
                }
            }
        }

        // If no matching route is found, return 404
        http_response_code(404);
        echo '404 Not Found';
    }

    private static function validateCsrfToken($token)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}