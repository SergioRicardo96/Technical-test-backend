<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;

class RedirectIfAuthenticated extends Controller
{
    public function handle($request, $next)
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/admin/tasks');
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
