<?php
/**
 * VITRINO Premium Preloader Integration
 */

// Cargar configuración base
$preloaderConfig = file_exists(__DIR__ . '/../../../config/preloader.php') 
    ? require __DIR__ . '/../../../config/preloader.php' 
    : [];

// Intentar obtener logo dinámico desde la BD si está disponible
try {
    if (class_exists('\App\Models\Setting')) {
        $settingModel = new \App\Models\Setting();
        $dbLogo = $settingModel->get('site_logo');
        if (!empty($dbLogo)) {
            $preloaderConfig['logo_url'] = $dbLogo;
        }
    }
} catch (Exception $e) {
    // Ignorar si no hay conexión a BD o modelo
}

// Valores por defecto
$logoUrl = $preloaderConfig['logo_url'] ?? '/default-logo.png';
$logoWidth = $preloaderConfig['logo_width'] ?? '140px';
$bgColor = $preloaderConfig['bg_color'] ?? '#FFFFFF';
$progStart = $preloaderConfig['progress_color_start'] ?? '#38BDF8';
$progEnd = $preloaderConfig['progress_color_end'] ?? '#2563EB';
$glow1 = $preloaderConfig['glow_color_1'] ?? '#3EC8FF';
$glow2 = $preloaderConfig['glow_color_2'] ?? '#0058C8';
$messages = $preloaderConfig['messages'] ?? [
    'Cargando interfaz...',
    'Preparando productos...',
    'Optimizando experiencia...',
    'Bienvenido a VITRINO'
];
$minDisplayTime = $preloaderConfig['min_display_time'] ?? 1800;
$fadeOutDuration = $preloaderConfig['fade_out_duration'] ?? 500;
?>

<!-- Carga de CSS -->
<link rel="stylesheet" href="/css/preloader.css">

<!-- Contenedor Principal del Preloader -->
<div id="vitrino-preloader" class="vitrino-preloader" style="
    --preloader-bg: <?= htmlspecialchars($bgColor) ?>;
    --preloader-progress-start: <?= htmlspecialchars($progStart) ?>;
    --preloader-progress-end: <?= htmlspecialchars($progEnd) ?>;
    --preloader-glow-1: <?= htmlspecialchars($glow1) ?>;
    --preloader-glow-2: <?= htmlspecialchars($glow2) ?>;
    --preloader-logo-width: <?= htmlspecialchars($logoWidth) ?>;
">
    <!-- Fondo Animado Sutil -->
    <div class="vitrino-preloader-bg">
        <div class="vitrino-preloader-orb vitrino-preloader-orb-1"></div>
        <div class="vitrino-preloader-orb vitrino-preloader-orb-2"></div>
    </div>

    <!-- Contenido Central -->
    <div class="vitrino-preloader-content">
        <!-- Logo -->
        <div class="vitrino-preloader-logo">
            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="VITRINO Loading">
        </div>

        <!-- Barra de Progreso Minimalista -->
        <div class="vitrino-preloader-bar-container">
            <div class="vitrino-preloader-bar"></div>
        </div>

        <!-- Textos Dinámicos -->
        <div class="vitrino-preloader-text-container">
            <span class="vitrino-preloader-text-label">Inicializando Catálogo Inteligente</span>
            <div class="relative w-full h-full mt-1">
                <?php foreach($messages as $index => $msg): ?>
                    <div class="vitrino-preloader-text <?= $index === 0 ? 'active' : '' ?>">
                        <?= htmlspecialchars($msg) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Configuración JS Inyectada -->
<script>
    window.vitrinoPreloaderConfig = {
        minDisplayTime: <?= (int)$minDisplayTime ?>,
        fadeOutDuration: <?= (int)$fadeOutDuration ?>
    };
</script>

<!-- Script Principal -->
<script src="/js/preloader.js" defer></script>
