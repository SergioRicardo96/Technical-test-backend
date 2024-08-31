<?php

namespace App\Http\Middleware;

use App\Libs\CsrfTokenManager;

class CsrfMiddleware
{
    public function handle($request, $next)
    {
        if (!isset($_POST['csrf_token']) || !CsrfTokenManager::validateToken($_POST['csrf_token'])) {
            http_response_code(403);
            echo '403 Forbidden: Invalid CSRF token.';
            exit;
        }
        
        CsrfTokenManager::generateToken();

        return $next($request);
    }
}
