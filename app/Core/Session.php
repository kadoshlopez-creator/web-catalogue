<?php

namespace App\Core;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Detectar HTTPS también detrás de proxies inversos (nginx → PHP-FPM)
            $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

            session_set_cookie_params([
                'lifetime' => 0,        // cookie de sesión (expira al cerrar el navegador)
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isHttps, // solo enviar por HTTPS cuando esté disponible
                'httponly' => true,      // inaccesible desde JavaScript
                'samesite' => 'Strict',  // previene envío cross-site (CSRF adicional)
            ]);
            session_start();
        }
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
    }

    public static function flash(string $key, $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key)
    {
        if (isset($_SESSION['_flash'][$key])) {
            $value = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $value;
        }
        return null;
    }
    
    public static function clearFlash(): void
    {
        unset($_SESSION['_flash']);
    }

    public static function csrfToken(): string
    {
        if (!self::has('_csrf_token')) {
            self::set('_csrf_token', bin2hex(random_bytes(32)));
        }
        return self::get('_csrf_token');
    }

    public static function rotateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        self::set('_csrf_token', $token);
        return $token;
    }

    public static function validateCsrfToken(?string $token): bool
    {
        if (!$token || !self::has('_csrf_token')) {
            return false;
        }
        return hash_equals(self::get('_csrf_token'), $token);
    }
}
