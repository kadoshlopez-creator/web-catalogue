<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - VITRINO</title>
    
    <?php
    $settingModel = new \App\Models\Setting();
    $site_favicon = $settingModel->get('site_favicon', '');
    ?>
    <?php if (!empty($site_favicon)): ?>
        <link rel="icon" href="<?= htmlspecialchars($site_favicon) ?>">
    <?php endif; ?>
    
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f6f6f7] text-gray-800 flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- Sidebar -->
    <aside x-show="sidebarOpen" 
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="w-[240px] bg-[#ebebeb] border-r border-[#d4d4d4] text-gray-800 flex flex-col h-full shrink-0 z-20 hidden md:flex absolute md:relative">
        <div class="p-5 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">VITRINO ADMIN</h2>
        </div>
        
        <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto">
            <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <a href="/admin/categories" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/categories') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Categorías
            </a>
            <a href="/admin/products" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/products') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Productos
            </a>
            
            <a href="/admin/brands" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/brands') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Marcas
            </a>
            

            <div class="pt-3 mt-3 border-t border-[#d4d4d4]"></div>
            <div class="px-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1 mt-2">Marketing (Nuevo)</div>
            <a href="/admin/marketing/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/marketing/dashboard') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                Dashboard
            </a>
            <a href="/admin/marketing/campaigns" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/marketing/campaigns') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Campañas
            </a>
            <a href="/admin/marketing/promotions" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/marketing/promotions') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                Promociones
            </a>
            <a href="/admin/marketing/offers" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/marketing/offers') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>
                Ofertas
            </a>
            <a href="/admin/marketing/banners" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/marketing/banners') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Banners
            </a>
            
            <div class="pt-3 mt-3 border-t border-[#d4d4d4]"></div>
            <div class="px-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1 mt-2">Configuración</div>
            <a href="/admin/settings/brand" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings/brand') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors mb-1 text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Identidad
            </a>
            <a href="/admin/settings/home" class="flex items-center gap-3 px-3 py-2 rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings/home') !== false ? 'bg-[#d4d4d4] text-gray-900 font-medium' : 'text-gray-700 hover:bg-[#d4d4d4] hover:text-gray-900' ?> transition-colors text-[14px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Constructor de Inicio
            </a>
        </nav>
        
        <div class="p-3 border-t border-[#d4d4d4]">
            <a href="/logout" class="flex items-center gap-3 px-3 py-2 text-[14px] text-gray-600 hover:text-gray-900 hover:bg-[#d4d4d4] rounded-md transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Cerrar Sesión
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-white relative">
        
        <!-- Top Header -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-10 px-6 py-3 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-4">
                <!-- Sidebar Toggle button -->
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:bg-gray-100 p-1.5 rounded-md transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-900"><?= $title ?? 'Dashboard' ?></h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="/" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 font-medium hidden sm:block">
                    Ver tienda
                </a>
                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border border-blue-200">
                    <?= strtoupper(substr(\App\Core\Session::get('user_name', 'A'), 0, 1)) ?>
                </div>
            </div>
        </header>
        
        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-6">
            <?php
            use App\Core\Session;
            $success = Session::getFlash('success');
            if ($success): 
            ?>
                <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 flex items-start shadow-sm">
                    <svg class="w-5 h-5 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span><?= htmlspecialchars($success) ?></span>
                </div>
            <?php endif; ?>

            <?php
            $error = Session::getFlash('error');
            if ($error): 
            ?>
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 flex items-start shadow-sm">
                    <svg class="w-5 h-5 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            {{content}}
            
        </div>
    </main>

</body>
</html>
