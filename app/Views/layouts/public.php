<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - ' : '' ?>VITRINO Catálogo Web</title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Descubre nuestra increíble selección de productos de alta calidad al mejor precio.') ?>">
    
    <?php
    $settingModel = new \App\Models\Setting();
    $site_favicon = $settingModel->get('site_favicon', '');
    ?>
    <?php if (!empty($site_favicon)): ?>
        <link rel="icon" href="<?= htmlspecialchars($site_favicon) ?>">
    <?php endif; ?>
    
    <!-- Open Graph SEO -->
    <meta property="og:title" content="<?= isset($title) ? htmlspecialchars($title) . ' - VITRINO' : 'VITRINO Catálogo Web' ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_description ?? 'Descubre nuestra increíble selección de productos de alta calidad al mejor precio.') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($meta_image ?? '/default-og-image.jpg') ?>">
    <meta property="og:type" content="website">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased selection:bg-blue-600 selection:text-white flex flex-col min-h-screen">
    <?php require_once __DIR__ . '/preloader.php'; ?>

    <?php 
    // Check if there are active offers and promotions
    $db = \App\Core\Database::getConnection();
    $activeOffersCount = $db->query("SELECT COUNT(*) FROM offers WHERE status = 'active' AND (start_date IS NULL OR start_date <= NOW()) AND (end_date IS NULL OR end_date >= NOW())")->fetchColumn();
    $activePromotionsCount = $db->query("SELECT COUNT(*) FROM promotions WHERE status = 'active'")->fetchColumn();
    $freeShippingPromo = $db->query("SELECT name FROM promotions WHERE status = 'active' AND type = 'free_shipping' LIMIT 1")->fetchColumn();
    $hasFreeShipping = !empty($freeShippingPromo);
    ?>
    <!-- Navbar -->
    <header class="fixed w-full top-0 z-50 glass-nav transition-all duration-300">
        <?php if ($hasFreeShipping): ?>
            <div id="promo-ribbon" class="bg-gradient-to-r from-gray-700 to-gray-900 text-gray-100 text-[13px] tracking-wide py-2 px-4 shadow-sm relative flex justify-center items-center">
                <span><?= htmlspecialchars($freeShippingPromo) ?></span>
                <button onclick="document.getElementById('promo-ribbon').style.display='none'; document.getElementById('main-content').classList.replace('pt-28', 'pt-20');" class="absolute right-4 text-gray-300 hover:text-white transition-colors focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <!-- Temporalmente deshabilitado:
            <script>
                if (sessionStorage.getItem('hidePromoRibbon') === 'true') {
                    document.write('<style>#promo-ribbon { display: none !important; } #main-content { padding-top: 5rem !important; }</style>');
                }
            </script>
            -->
        <?php endif; ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <?php
                    $settingModel = new \App\Models\Setting();
                    $site_logo = $settingModel->get('site_logo', '');
                    $site_logo_height = $settingModel->get('site_logo_height', '48');
                    
                    // Fetch menus
                    $nav_menus = $settingModel->get('navigation_menus', []);
                    $main_menu = $nav_menus['main']['items'] ?? [
                        ['label' => 'Inicio', 'link' => '/'],
                        ['label' => 'Catálogo', 'link' => '/catalogo'],
                        ['label' => 'Contacto', 'link' => '#contacto']
                    ];
                    $footer_menu = $nav_menus['footer']['items'] ?? [
                        ['label' => 'Inicio', 'link' => '/'],
                        ['label' => 'Ver Catálogo', 'link' => '/catalogo']
                    ];
                    ?>
                    <a href="/" class="text-2xl font-extrabold tracking-tighter text-blue-600 flex items-center gap-2">
                        <?php if (!empty($site_logo)): ?>
                            <img src="<?= htmlspecialchars($site_logo) ?>" alt="VITRINO Logo" class="w-auto object-contain transition-all" style="height: <?= htmlspecialchars($site_logo_height) ?>px;">
                        <?php else: ?>
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            VITRINO
                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8 items-center">
                    <?php foreach ($main_menu as $item): ?>
                        <?php 
                        // Ocultar si es Promociones y no hay promociones activas
                        if (stripos(trim($item['label']), 'promocion') !== false && $activePromotionsCount == 0) {
                            continue;
                        }
                        $link = $item['link'];
                        if (stripos(trim($item['label']), 'promocion') !== false && (empty($link) || strpos($link, '#') !== false || strpos($link, 'oferta') !== false)) {
                            $link = '/promociones';
                        }
                        ?>
                        <a href="<?= htmlspecialchars($link) ?>" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    <?php endforeach; ?>

                    <?php if ($activeOffersCount > 0): ?>
                        <a href="/catalogo?trend=oferta" class="px-4 py-1.5 bg-red-600 text-white font-extrabold rounded-md shadow-md shadow-red-500/20 animate-pulse hover:bg-red-700 hover:shadow-red-500/40 hover:animate-none transition-all flex items-center gap-1.5 border border-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l4-4m-4 0h.01m3.99 4h.01" stroke-width="3" />
                            </svg>
                            ¡OFERTAS!
                        </a>
                    <?php endif; ?>
                </nav>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="text-gray-600 hover:text-gray-900 focus:outline-none">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content" class="flex-grow <?php echo $hasFreeShipping ? 'pt-28' : 'pt-20'; ?>">
        {{content}}
    </main>

    <!-- Footer -->
    <footer id="contacto" class="bg-gray-900 text-white py-12 mt-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <a href="/" class="text-2xl font-extrabold tracking-tighter text-white mb-4 flex items-center gap-2">
                        <?php if (!empty($site_logo)): ?>
                            <img src="<?= htmlspecialchars($site_logo) ?>" alt="VITRINO Logo" class="w-auto object-contain brightness-0 invert transition-all" style="height: <?= htmlspecialchars($site_logo_height) ?>px;">
                        <?php else: ?>
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            VITRINO
                        <?php endif; ?>
                    </a>
                    <p class="text-gray-400 text-sm">
                        La mejor selección de productos tecnológicos al alcance de tu mano. Calidad premium garantizada.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-100">Enlaces Rápidos</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <?php foreach ($footer_menu as $item): ?>
                            <li><a href="<?= htmlspecialchars($item['link']) ?>" class="hover:text-blue-400 transition-colors"><?= htmlspecialchars($item['label']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-100">Contacto</h3>
                    <p class="text-sm text-gray-400 mb-2">WhatsApp: <a href="https://wa.me/1234567890" target="_blank" class="text-blue-400 hover:underline">+1 234 567 890</a></p>
                    <p class="text-sm text-gray-400">Email: contacto@vitrino.com</p>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
                &copy; <?= date('Y') ?> VITRINO Catálogo Web. Todos los derechos reservados.
            </div>
        </div>
    </footer>

</body>
</html>
