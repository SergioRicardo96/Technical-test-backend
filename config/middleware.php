<?php

return [
    'auth' => App\Http\Middleware\AuthMiddleware::class,
    'csrf' => App\Http\Middleware\CsrfMiddleware::class,
    'guest' => App\Http\Middleware\RedirectIfAuthenticated::class
];
