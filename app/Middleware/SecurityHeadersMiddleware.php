<?php

namespace App\Middleware;

use App\Core\Middleware;

class SecurityHeadersMiddleware implements Middleware
{
    public function execute(): void
    {
        // Clickjacking
        header('X-Frame-Options: SAMEORIGIN');

        // MIME-type sniffing
        header('X-Content-Type-Options: nosniff');

        // No indexar páginas de error ni admin en buscadores
        header('X-Permitted-Cross-Domain-Policies: none');

        // Controlar cuánto referer se comparte en navegaciones cross-origin
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // Deshabilitar APIs de hardware no usadas
        header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()');

        // CSP: unsafe-inline y unsafe-eval son necesarios por Tailwind CDN y Alpine.js CDN.
        // Cuando se migre a bundles compilados, reemplazar por nonces o hashes.
        header(
            "Content-Security-Policy: " .
            "default-src 'self'; " .
            "script-src 'self' https://unpkg.com 'unsafe-inline' 'unsafe-eval'; " .
            "style-src 'self' https://unpkg.com https://fonts.googleapis.com 'unsafe-inline'; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "img-src 'self' data: https:; " .
            "connect-src 'self'; " .
            "frame-ancestors 'none';"
        );

        // HSTS: solo cuando hay HTTPS activo
        $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

        if ($isHttps) {
            // max-age 2 años; agregar "preload" cuando el dominio esté en la preload list de Chrome
            header('Strict-Transport-Security: max-age=63072000; includeSubDomains');
        }
    }
}
