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
    try {
        $activePromotionsCount = $db->query("SELECT COUNT(*) FROM promotions WHERE status = 'active' AND show_in_menu = 1")->fetchColumn();
    } catch (\Exception $e) {
        // Fallback si la columna show_in_menu aún no existe en la BD (pendiente de migración)
        $activePromotionsCount = 0;
    }
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
                    $footer_settings = $settingModel->get('footer_settings', []);
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

    <?php 
    $flashSuccess = \App\Core\Session::getFlash('success');
    $flashError = \App\Core\Session::getFlash('error');
    if ($flashSuccess || $flashError): 
    ?>
    <div class="fixed top-24 left-1/2 transform -translate-x-1/2 z-[100] w-full max-w-md px-4 pointer-events-none" id="flash-messages-container">
        <?php if ($flashSuccess): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg relative flex items-center justify-between pointer-events-auto" role="alert">
            <span class="block sm:inline font-medium"><?= htmlspecialchars($flashSuccess) ?></span>
            <button class="text-green-700 hover:text-green-900 focus:outline-none" onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <?php endif; ?>
        <?php if ($flashError): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg relative flex items-center justify-between pointer-events-auto mt-2" role="alert">
            <span class="block sm:inline font-medium"><?= htmlspecialchars($flashError) ?></span>
            <button class="text-red-700 hover:text-red-900 focus:outline-none" onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <?php endif; ?>
        <script>
            setTimeout(() => {
                const container = document.getElementById('flash-messages-container');
                if (container) {
                    container.style.transition = 'opacity 0.5s ease-out';
                    container.style.opacity = '0';
                    setTimeout(() => container.remove(), 500);
                }
            }, 5000);
        </script>
    </div>
    <?php endif; ?>

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
                        <?= !empty($footer_settings['text']) ? nl2br(htmlspecialchars($footer_settings['text'])) : 'La mejor selección de productos al alcance de tu mano. Calidad premium garantizada.' ?>
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
                    <?php if (!empty($footer_settings['phone'])): ?>
                        <p class="text-sm text-gray-400 mb-2">Teléfono: <a href="tel:<?= htmlspecialchars($footer_settings['phone']) ?>" class="text-blue-400 hover:underline"><?= htmlspecialchars($footer_settings['phone']) ?></a></p>
                    <?php endif; ?>
                    <?php if (!empty($footer_settings['email'])): ?>
                        <p class="text-sm text-gray-400 mb-2">Email: <a href="mailto:<?= htmlspecialchars($footer_settings['email']) ?>" class="text-blue-400 hover:underline"><?= htmlspecialchars($footer_settings['email']) ?></a></p>
                    <?php endif; ?>
                    <?php if (!empty($footer_settings['address'])): ?>
                        <p class="text-sm text-gray-400 mb-4"><?= htmlspecialchars($footer_settings['address']) ?></p>
                    <?php endif; ?>
                    
                    <div class="flex gap-4 mt-4">
                        <?php if (!empty($footer_settings['facebook'])): ?>
                            <a href="<?= htmlspecialchars($footer_settings['facebook']) ?>" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($footer_settings['instagram'])): ?>
                            <a href="<?= htmlspecialchars($footer_settings['instagram']) ?>" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($footer_settings['twitter'])): ?>
                            <a href="<?= htmlspecialchars($footer_settings['twitter']) ?>" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
                &copy; <?= date('Y') ?> VITRINO Catálogo Web. Todos los derechos reservados.
            </div>
        </div>
    </footer>

</body>
</html>
