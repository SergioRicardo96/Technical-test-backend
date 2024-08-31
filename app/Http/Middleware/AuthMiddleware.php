<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;

class AuthMiddleware extends Controller
{
    public function handle($request, $next)
    {
        // Check if user is not authenticated
        if(!$this->isAuthenticated()) {
            $this->redirect('/login');
        }
        
        return $next($request);
    }

    private function isAuthenticated()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user']);
    }
}
