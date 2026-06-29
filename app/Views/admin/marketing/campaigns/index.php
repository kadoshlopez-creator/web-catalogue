<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Campañas</h2>
        <p class="text-gray-500 text-sm mt-1">Administra los eventos comerciales de tu plataforma.</p>
    </div>
    <a href="/admin/marketing/campaigns/create" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Nueva Campaña
    </a>
</div>

<div class="bg-white border border-gray-100 shadow-sm rounded-xl overflow-hidden">
    <?php if (empty($campaigns)): ?>
        <div class="p-12 text-center flex flex-col items-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No hay campañas</h3>
            <p class="text-sm text-gray-500 mt-1 max-w-sm">No has creado ninguna campaña comercial todavía. Empieza creando tu primer evento como "Black Friday" o "Liquidación de Verano".</p>
            <a href="/admin/marketing/campaigns/create" class="mt-6 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                Crear mi primera campaña
            </a>
        </div>
    <?php else: ?>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                    <th class="p-4 font-medium">Nombre</th>
                    <th class="p-4 font-medium">Fechas</th>
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
                foreach ($campaigns as $c): 
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4">
                        <p class="font-medium text-gray-900"><?= htmlspecialchars($c['name']) ?></p>
                        <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($c['slug']) ?></p>
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                        <?= $c['start_date'] ? date('d M Y', strtotime($c['start_date'])) : 'Sin fecha' ?> - 
                        <?= $c['end_date'] ? date('d M Y', strtotime($c['end_date'])) : 'Sin fecha' ?>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $getStatusColor($c['status']) ?>">
                            <?= $formatStatus($c['status']) ?>
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="/admin/marketing/campaigns/<?= $c['id'] ?>/edit" class="text-gray-400 hover:text-blue-600 transition-colors mx-1">Editar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
