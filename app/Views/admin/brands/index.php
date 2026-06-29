<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h2 class="text-xl font-semibold text-gray-800">Listado de Marcas</h2>
    <div class="flex items-center gap-4 w-full sm:w-auto">
        <form action="/admin/brands" method="GET" class="relative w-full sm:w-64">
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Buscar marcas..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </form>
        <a href="/admin/brands/create" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-sm transition-colors whitespace-nowrap">
            + Nueva Marca
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3">Logo</th>
                    <th class="px-5 py-3">Nombre</th>
                    <th class="px-5 py-3">Slug</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                <?php if (empty($brands)): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-500">
                            No hay marcas registradas o que coincidan con la búsqueda.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($brands as $brand): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="h-10 w-10 rounded bg-gray-50 border border-gray-200 flex items-center justify-center p-1">
                                    <?php if (!empty($brand['logo'])): ?>
                                        <img src="<?= htmlspecialchars($brand['logo']) ?>" alt="<?= htmlspecialchars($brand['name']) ?>" class="max-h-full max-w-full object-contain">
                                    <?php else: ?>
                                        <span class="text-[10px] text-gray-400 font-medium">N/A</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-900"><?= htmlspecialchars($brand['name']) ?></div>
                            </td>
                            <td class="px-5 py-3 text-gray-500">
                                <?= htmlspecialchars($brand['slug']) ?>
                            </td>
                            <td class="px-5 py-3">
                                <?php if ($brand['is_active']): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-800 uppercase tracking-wider">Activo</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-800 uppercase tracking-wider">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-3 text-right font-medium flex justify-end gap-3 items-center h-full mt-2">
                                <a href="/admin/brands/<?= $brand['id'] ?>/edit" class="text-blue-600 hover:text-blue-900">Editar</a>
                                <form action="/admin/brands/<?= $brand['id'] ?>/delete" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta marca?');" class="inline">
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
                <a href="/admin/brands?page=<?= $page - 1 ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>" class="px-3 py-1 bg-white border border-gray-200 rounded text-sm hover:bg-gray-50">Anterior</a>
            <?php else: ?>
                <span class="px-3 py-1 bg-gray-100 border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed">Anterior</span>
            <?php endif; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="/admin/brands?page=<?= $page + 1 ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>" class="px-3 py-1 bg-white border border-gray-200 rounded text-sm hover:bg-gray-50">Siguiente</a>
            <?php else: ?>
                <span class="px-3 py-1 bg-gray-100 border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed">Siguiente</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
