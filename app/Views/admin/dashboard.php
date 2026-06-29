<!-- Include DashboardManager Script -->
<script src="/assets/js/admin/DashboardManager.js" defer></script>

<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Business Intelligence Center</h2>
    <p class="text-gray-500">Resumen general de tu plataforma y recomendaciones inteligentes.</p>
</div>

<!-- KPIs / StatCards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" x-data="{ metrics: { totalProducts: '...', totalCategories: '...', visits: '...', seoScore: '...' } }" x-init="fetch('/admin/dashboard/metrics').then(r => r.json()).then(d => metrics = d)">
    
    <?php include __DIR__ . '/../components/dashboard/StatCard.php'; ?>
    <?php 
    $title = 'Productos Activos';
    $value = '<span x-text="metrics.totalProducts"></span>';
    $icon = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>';
    $color = 'blue';
    $trend = '+5%';
    include __DIR__ . '/../components/dashboard/StatCard.php'; 
    ?>

    <?php 
    $title = 'Categorías';
    $value = '<span x-text="metrics.totalCategories"></span>';
    $icon = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>';
    $color = 'purple';
    $trend = '';
    include __DIR__ . '/../components/dashboard/StatCard.php'; 
    ?>

    <?php 
    $title = 'Visitas Semanales';
    $value = '<span x-text="metrics.visits"></span>';
    $icon = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
    $color = 'green';
    $trend = '+12%';
    include __DIR__ . '/../components/dashboard/StatCard.php'; 
    ?>

    <?php 
    $title = 'Salud SEO';
    $value = '<span x-text="metrics.seoScore + \'% \'"></span>';
    $icon = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    $color = 'orange';
    $trend = 'Bueno';
    include __DIR__ . '/../components/dashboard/StatCard.php'; 
    ?>
</div>

<!-- Command Center Grid -->
<?php
// Handle layout preferences
$layoutOrder = !empty($preferences['layout_json']) ? json_decode($preferences['layout_json'], true) : [
    'widget-seo', 'widget-tasks', 'widget-timeline', 'widget-system'
];

$widgets = [
    'widget-seo' => [
        'id' => 'widget-seo',
        'title' => 'Salud SEO',
        'colSpan' => 'col-span-1 lg:col-span-1',
        'content' => function() { include __DIR__ . '/../components/dashboard/SeoHealthCard.php'; }
    ],
    'widget-tasks' => [
        'id' => 'widget-tasks',
        'title' => 'Tareas Inteligentes',
        'colSpan' => 'col-span-1 lg:col-span-2',
        'content' => function() { include __DIR__ . '/../components/dashboard/TaskCard.php'; }
    ],
    'widget-timeline' => [
        'id' => 'widget-timeline',
        'title' => 'Actividad Reciente',
        'colSpan' => 'col-span-1 lg:col-span-2',
        'content' => function() { include __DIR__ . '/../components/dashboard/ActivityTimeline.php'; }
    ],
    'widget-system' => [
        'id' => 'widget-system',
        'title' => 'Estado del Sistema',
        'colSpan' => 'col-span-1 lg:col-span-1',
        'content' => function() {
            echo '<div x-data="{ health: {} }" x-init="fetch(\'/admin/dashboard/system-health\').then(r => r.json()).then(d => health = d)" class="space-y-4 text-sm">';
            echo '<div class="flex justify-between border-b pb-2"><span class="text-gray-500">PHP Version</span><span class="font-medium" x-text="health.php_version"></span></div>';
            echo '<div class="flex justify-between border-b pb-2"><span class="text-gray-500">Memory Usage</span><span class="font-medium" x-text="health.memory_usage"></span></div>';
            echo '<div class="flex justify-between border-b pb-2"><span class="text-gray-500">Free Disk</span><span class="font-medium" x-text="health.disk_free_space"></span></div>';
            echo '<div class="flex justify-between border-b pb-2"><span class="text-gray-500">OS</span><span class="font-medium" x-text="health.server_os"></span></div>';
            echo '</div>';
        }
    ]
];
?>

<div id="dashboard-grid" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <?php 
    // Render widgets based on user preferred order
    foreach ($layoutOrder as $widgetId) {
        if (isset($widgets[$widgetId])) {
            $id = $widgets[$widgetId]['id'];
            $title = $widgets[$widgetId]['title'];
            $colSpan = $widgets[$widgetId]['colSpan'];
            
            ob_start();
            $widgets[$widgetId]['content']();
            $content = ob_get_clean();
            
            include __DIR__ . '/../components/dashboard/WidgetContainer.php';
            unset($widgets[$widgetId]); // Remove rendered
        }
    }
    
    // Render any new widgets not in the saved layout yet
    foreach ($widgets as $widget) {
        $id = $widget['id'];
        $title = $widget['title'];
        $colSpan = $widget['colSpan'];
        
        ob_start();
        $widget['content']();
        $content = ob_get_clean();
        
        include __DIR__ . '/../components/dashboard/WidgetContainer.php';
    }
    ?>
</div>
