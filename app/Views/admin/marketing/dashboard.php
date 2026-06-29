<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Marketing Dashboard</h2>
    <p class="text-gray-500">Métricas y accesos rápidos de tus acciones comerciales.</p>
</div>

<!-- KPIs -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <?php 
    $title = 'Campañas Activas';
    $value = $activeCampaigns ?? 0;
    $icon = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>';
    $color = 'blue';
    $trend = '';
    include __DIR__ . '/../../components/dashboard/StatCard.php'; 
    ?>

    <?php 
    $title = 'Promociones';
    $value = $activePromotions ?? 0;
    $icon = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>';
    $color = 'purple';
    $trend = '';
    include __DIR__ . '/../../components/dashboard/StatCard.php'; 
    ?>

    <?php 
    $title = 'Ofertas Activas';
    $value = $activeOffers ?? 0;
    $icon = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>';
    $color = 'green';
    $trend = '';
    include __DIR__ . '/../../components/dashboard/StatCard.php'; 
    ?>

    <?php 
    $title = 'Banners Mostrados';
    $value = $activeBanners ?? 0;
    $icon = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
    $color = 'orange';
    $trend = '';
    include __DIR__ . '/../../components/dashboard/StatCard.php'; 
    ?>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 text-center py-12">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-500 mb-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
    </div>
    <h3 class="text-xl font-semibold text-gray-800 mb-2">Bienvenido al nuevo Módulo de Marketing</h3>
    <p class="text-gray-500 max-w-lg mx-auto">
        Comienza a organizar tus ventas creando tu primera Campaña, asignando Banners y definiendo Promociones y Ofertas específicas.
    </p>
    <div class="mt-6 flex justify-center gap-4">
        <a href="/admin/marketing/campaigns/create" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            Crear Campaña
        </a>
        <a href="/admin/marketing/promotions" class="px-6 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
            Ver Promociones
        </a>
    </div>
</div>
