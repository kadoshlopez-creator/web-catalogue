<?php

namespace App\Core;

class Request
{
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($uri, '?');
        
        if ($position !== false) {
            $uri = substr($uri, 0, $position);
        }
        
        return $uri;
    }

    public function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }
    
    public function all(): array
    {
        return array_merge($_GET, $_POST);
    }
    
    public function file(string $key)
    {
        return $_FILES[$key] ?? null;
    }
}
