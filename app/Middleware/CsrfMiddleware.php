<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\Session;

class CsrfMiddleware implements Middleware
{
    public function execute(): void
    {
        $request = new Request();
        $method = strtoupper($request->getMethod());

        // Solo validamos peticiones que mutan estado
        if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
            $token = $request->post('_csrf_token');
            
            if (!Session::validateCsrfToken($token)) {
                http_response_code(403);
                die('Error 403: Token CSRF inválido o expirado. Por favor, recarga la página e inténtalo de nuevo.');
            }
        }
    }
}
