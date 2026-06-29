<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;

class RateLimitMiddleware implements Middleware
{
    private int $maxRequests;
    private int $timeWindowSeconds;
    private string $storageDir;

    public function __construct(int $maxRequests = 5, int $timeWindowSeconds = 60)
    {
        $this->maxRequests = $maxRequests;
        $this->timeWindowSeconds = $timeWindowSeconds;
        $this->storageDir = dirname(__DIR__, 2) . '/storage/rate_limits';
    }

    public function execute(): void
    {
        $request = new Request();
        if (strtoupper($request->getMethod()) !== 'POST') {
            return;
        }

        $ip = $this->getClientIp();
        $endpoint = hash('sha256', $request->getUri());
        $key = hash('sha256', $ip . '|' . $endpoint);

        $data = $this->loadData($key);
        $now = time();

        // Limpiar timestamps fuera de la ventana
        $data = array_values(array_filter($data, fn($ts) => ($now - $ts) < $this->timeWindowSeconds));

        if (count($data) >= $this->maxRequests) {
            $retryAfter = $this->timeWindowSeconds - ($now - $data[0]);
            http_response_code(429);
            header('Retry-After: ' . max(1, $retryAfter));
            header('X-RateLimit-Limit: ' . $this->maxRequests);
            header('X-RateLimit-Remaining: 0');
            die('Error 429: Demasiadas peticiones. Por favor, espera ' . max(1, $retryAfter) . ' segundos.');
        }

        $data[] = $now;
        $this->saveData($key, $data);

        header('X-RateLimit-Limit: ' . $this->maxRequests);
        header('X-RateLimit-Remaining: ' . ($this->maxRequests - count($data)));
    }

    private function getClientIp(): string
    {
        // Solo confiar en REMOTE_ADDR; los headers X-Forwarded-For son manipulables
        // si hay un proxy de confianza en la infraestructura, configurarlo explícitamente
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function loadData(string $key): array
    {
        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0750, true);
        }
        $file = $this->storageDir . '/' . $key . '.json';
        if (!file_exists($file)) {
            return [];
        }
        $content = file_get_contents($file);
        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function saveData(string $key, array $data): void
    {
        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0750, true);
        }
        $file = $this->storageDir . '/' . $key . '.json';
        file_put_contents($file, json_encode($data), LOCK_EX);
    }
}
