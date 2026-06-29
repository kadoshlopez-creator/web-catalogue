<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = [], ?string $layout = null): string
    {
        extract($data);
        
        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} not found.");
        }
        
        ob_start();
        include $viewPath;
        $viewContent = ob_get_clean();

        if ($layout) {
            $layoutPath = __DIR__ . '/../Views/layouts/' . str_replace('.', '/', $layout) . '.php';
            if (file_exists($layoutPath)) {
                ob_start();
                include $layoutPath;
                $layoutContent = ob_get_clean();
                return str_replace('{{content}}', $viewContent, $layoutContent);
            }
        }
        
        return $viewContent;
    }

    /**
     * Render a JSON-LD <script> block safely, preventing </script> injection.
     * Use this instead of raw echo when outputting schema_json in templates.
     */
    public static function renderJsonLd(?string $json): string
    {
        if (empty($json)) {
            return '';
        }
        // Re-codificar con JSON_HEX_TAG para que < se emita como < y > como >,
        // lo que hace imposible la secuencia </script> dentro del bloque de script.
        $decoded = json_decode($json, true);
        if ($decoded === null) {
            return ''; // JSON inválido: no renderizar nada
        }
        $safe = json_encode($decoded, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return '<script type="application/ld+json">' . PHP_EOL . $safe . PHP_EOL . '</script>' . PHP_EOL;
    }
}
