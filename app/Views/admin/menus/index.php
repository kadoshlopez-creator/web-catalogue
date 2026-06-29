<div class="p-6 max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Menús de Navegación</h1>
            <p class="text-sm text-gray-500 mt-1">Gestiona los menús de navegación de tu tienda virtual</p>
        </div>
    </div>

    <?php if (\App\Core\Session::has('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        <?= htmlspecialchars(\App\Core\Session::get('success')) ?>
                    </p>
                </div>
            </div>
        </div>
        <?php \App\Core\Session::remove('success'); ?>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="grid grid-cols-12 bg-gray-50 px-6 py-3 border-b border-gray-200 text-xs font-medium text-gray-500 uppercase tracking-wider">
            <div class="col-span-3">Menú</div>
            <div class="col-span-9">Elementos del menú</div>
        </div>
        
        <div class="divide-y divide-gray-200">
            <?php foreach ($menus as $key => $menu): ?>
            <a href="/admin/menus/<?= htmlspecialchars($key) ?>/edit" class="grid grid-cols-12 px-6 py-4 hover:bg-gray-50 transition-colors items-center group">
                <div class="col-span-3">
                    <span class="font-medium text-blue-600 group-hover:text-blue-800 transition-colors">
                        <?= htmlspecialchars($menu['name']) ?>
                    </span>
                </div>
                <div class="col-span-9 text-sm text-gray-500 flex items-center justify-between">
                    <div class="truncate pr-4">
                        <?php 
                        $labels = array_map(function($item) {
                            return htmlspecialchars($item['label']);
                        }, $menu['items'] ?? []);
                        echo implode(', ', $labels);
                        if (empty($labels)) {
                            echo '<span class="text-gray-400 italic">Sin elementos</span>';
                        }
                        ?>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
