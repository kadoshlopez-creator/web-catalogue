<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h2 class="text-xl font-semibold text-gray-800">Listado de Categorías</h2>
    <div class="flex items-center gap-4 w-full sm:w-auto">
        <form action="/admin/categories" method="GET" class="relative w-full sm:w-64">
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Buscar categorías..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </form>
        <a href="/admin/categories/create" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-sm transition-colors whitespace-nowrap">
            + Nueva Categoría
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3">ID</th>
                    <th class="px-5 py-3">Nombre</th>
                    <th class="px-5 py-3">Slug</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-500">
                            No hay categorías registradas o que coincidan con la búsqueda.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-600">#<?= $cat['id'] ?></td>
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-900">
                                    <?php if ($cat['level'] > 1): ?>
                                        <span class="text-gray-400 mr-1"><?= str_repeat('— ', $cat['level'] - 1) ?></span>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </div>
                                <div class="text-[11px] text-gray-400 mt-0.5">Nivel <?= $cat['level'] ?? 1 ?></div>
                            </td>
                            <td class="px-5 py-3 text-gray-500">
                                <?= htmlspecialchars($cat['slug']) ?>
                            </td>
                            <td class="px-5 py-3">
                                <?php if ($cat['is_active']): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-800 uppercase tracking-wider">Activo</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-800 uppercase tracking-wider">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/admin/categories/<?= $cat['id'] ?>/seo" class="text-green-600 hover:text-green-900 mr-3">SEO</a>
                                <a href="/admin/categories/<?= $cat['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                <form action="/admin/categories/<?= $cat['id'] ?>/delete" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');" class="inline">
                                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Paginator -->
    <?php if (($totalPages ?? 1) > 1): ?>
    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
        <div class="text-sm text-gray-500">
            Página <?= htmlspecialchars($page) ?> de <?= htmlspecialchars($totalPages) ?>
        </div>
        <div class="flex gap-1">
            <?php if ($page > 1): ?>
                <a href="/admin/categories?page=<?= $page - 1 ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>" class="px-3 py-1 bg-white border border-gray-200 rounded text-sm hover:bg-gray-50">Anterior</a>
            <?php else: ?>
                <span class="px-3 py-1 bg-gray-100 border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed">Anterior</span>
            <?php endif; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="/admin/categories?page=<?= $page + 1 ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>" class="px-3 py-1 bg-white border border-gray-200 rounded text-sm hover:bg-gray-50">Siguiente</a>
            <?php else: ?>
                <span class="px-3 py-1 bg-gray-100 border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed">Siguiente</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
