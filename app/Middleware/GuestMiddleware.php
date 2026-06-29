<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Session;

class GuestMiddleware implements Middleware
{
    public function execute(): void
    {
        if (Session::has('user_id')) {
            header('Location: /');
            exit;
        }
    }
}
