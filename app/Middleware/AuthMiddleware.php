<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Session;

class AuthMiddleware implements Middleware
{
    public function execute(): void
    {
        if (!Session::has('user_id')) {
            Session::flash('error', 'Debes iniciar sesión para acceder a esta área.');
            header('Location: /login');
            exit;
        }
    }
}
