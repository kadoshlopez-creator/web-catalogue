<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Ofertas Especiales</h2>
        <p class="text-gray-500 text-sm mt-1">Descuentos directos aplicados al precio del producto.</p>
    </div>
    <a href="/admin/marketing/offers/create" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Nueva Oferta
    </a>
</div>

<div class="bg-white border border-gray-100 shadow-sm rounded-xl overflow-hidden">
    <?php if (empty($offers)): ?>
        <div class="p-12 text-center flex flex-col items-center">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center text-green-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No hay ofertas activas</h3>
            <p class="text-sm text-gray-500 mt-1 max-w-sm">Crea ofertas con descuentos directos en porcentaje o montos fijos para incentivar las ventas.</p>
            <a href="/admin/marketing/offers/create" class="mt-6 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                Crear primera oferta
            </a>
        </div>
    <?php else: ?>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                    <th class="p-4 font-medium">Nombre de Oferta</th>
                    <th class="p-4 font-medium">Descuento</th>
                    <th class="p-4 font-medium">Vigencia</th>
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
                foreach ($offers as $o): 
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4">
                        <p class="font-medium text-gray-900"><?= htmlspecialchars($o['name']) ?></p>
                        <?php if(!empty($o['campaign_id'])): ?>
                            <p class="text-xs text-blue-500 mt-0.5">Vinculado a Campaña ID: <?= htmlspecialchars((string)$o['campaign_id']) ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium bg-green-100 text-green-800">
                            <?= $o['discount_type'] === 'percentage' ? '-' . number_format((float)$o['discount_value'], 0) . '%' : '-$' . number_format((float)$o['discount_value'], 2) ?>
                        </span>
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                        <?= $o['start_date'] ? date('d M Y', strtotime($o['start_date'])) : 'Inmediato' ?> - 
                        <?= $o['end_date'] ? date('d M Y', strtotime($o['end_date'])) : 'Sin caducidad' ?>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $getStatusColor($o['status']) ?>">
                            <?= $formatStatus($o['status']) ?>
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="/admin/marketing/offers/<?= $o['id'] ?>/edit" class="text-gray-400 hover:text-blue-600 transition-colors mx-1">Editar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
