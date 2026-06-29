<?php
declare(strict_types=1);

namespace App\Services;

class SystemHealthService
{
    public function getSystemHealth(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'disk_free_space' => $this->formatBytes(disk_free_space("/")),
            'disk_total_space' => $this->formatBytes(disk_total_space("/")),
            'server_os' => php_uname('s'),
        ];
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
