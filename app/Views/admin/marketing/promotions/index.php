<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Promociones Comerciales</h2>
        <p class="text-gray-500 text-sm mt-1">Configura reglas como "Envío Gratis", "Regalo por compra" o "2x1".</p>
    </div>
    <a href="/admin/marketing/promotions/create" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Nueva Promoción
    </a>
</div>

<div class="bg-white border border-gray-100 shadow-sm rounded-xl overflow-hidden">
    <?php if (empty($promotions)): ?>
        <div class="p-12 text-center flex flex-col items-center">
            <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center text-purple-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No hay promociones activas</h3>
            <p class="text-sm text-gray-500 mt-1 max-w-sm">Crea incentivos de compra basados en reglas sin alterar directamente el precio del producto.</p>
            <a href="/admin/marketing/promotions/create" class="mt-6 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                Crear primera promoción
            </a>
        </div>
    <?php else: ?>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                    <th class="p-4 font-medium">Promoción</th>
                    <th class="p-4 font-medium">Mecánica (Tipo)</th>
                    <th class="p-4 font-medium">Estado</th>
                    <th class="p-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php 
                $getStatusColor = function($status) {
                    return match(strtolower($status)) {
                        'active' => 'bg-green-100 text-green-800',
                        'draft', 'scheduled', 'inactive' => 'bg-yellow-100 text-yellow-800',
                        'ended', 'suspended' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                };
                $formatStatus = function($status) {
                    $map = ['active' => 'Activa', 'draft' => 'Borrador', 'scheduled' => 'Programada', 'inactive' => 'Inactiva', 'ended' => 'Finalizada', 'suspended' => 'Suspendida'];
                    return $map[strtolower($status)] ?? ucfirst($status);
                };
                foreach ($promotions as $p): 
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4">
                        <p class="font-medium text-gray-900"><?= htmlspecialchars($p['name']) ?></p>
                        <?php if(!empty($p['campaign_id'])): ?>
                            <p class="text-xs text-blue-500 mt-0.5">Campaña: <?= htmlspecialchars((string)$p['campaign_id']) ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium bg-purple-100 text-purple-800 uppercase tracking-wide text-xs">
                            <?= htmlspecialchars(str_replace('_', ' ', $p['type'])) ?>
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $getStatusColor($p['status']) ?>">
                            <?= $formatStatus($p['status']) ?>
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="/admin/marketing/promotions/<?= $p['id'] ?>/edit" class="text-gray-400 hover:text-blue-600 transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="/admin/marketing/promotions/<?= $p['id'] ?>/delete" method="POST" class="inline m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta promoción?');">
                                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors bg-transparent border-0 p-0 cursor-pointer" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
