<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Interno - VITRINO</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="max-w-xl w-full px-6">
        <div class="bg-white p-8 rounded-xl shadow-xl text-center border border-gray-100">
            <h1 class="text-6xl font-extrabold text-red-500 mb-4">500</h1>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Error Interno del Servidor</h2>
            <p class="text-gray-600 mb-8">Lo sentimos, ha ocurrido un error inesperado al procesar tu solicitud. Por favor, inténtalo de nuevo más tarde.</p>
            
            <?php if (isset($exception) && (defined('APP_DEBUG') && APP_DEBUG === true || $_SERVER['HTTP_HOST'] === 'web-catalogue.test')): ?>
                <div class="text-left bg-gray-100 p-4 rounded-lg overflow-x-auto text-sm text-gray-800 mb-8">
                    <p class="font-bold mb-2"><?= htmlspecialchars($exception->getMessage()) ?></p>
                    <pre><?= htmlspecialchars($exception->getTraceAsString()) ?></pre>
                </div>
            <?php endif; ?>
            
            <a href="/" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition-colors">
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
