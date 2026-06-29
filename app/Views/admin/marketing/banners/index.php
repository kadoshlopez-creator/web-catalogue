<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Banners</h2>
        <p class="text-sm text-gray-500 mt-1">Configura las imágenes principales que aparecerán en el carrusel de la página de inicio.</p>
    </div>
    <a href="/admin/marketing/banners/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nuevo Banner
    </a>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-100 flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        <?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <?php if (empty($banners)): ?>
        <div class="p-10 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No hay banners configurados</h3>
            <p class="text-gray-500 mb-4">Comienza creando tu primer banner para el carrusel principal.</p>
            <a href="/admin/marketing/banners/create" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                Crear ahora &rarr;
            </a>
        </div>
    <?php else: ?>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="p-4 font-semibold text-gray-600 text-sm">Banner</th>
                    <th class="p-4 font-semibold text-gray-600 text-sm">Información</th>
                    <th class="p-4 font-semibold text-gray-600 text-sm text-center">Orden</th>
                    <th class="p-4 font-semibold text-gray-600 text-sm text-center">Estado</th>
                    <th class="p-4 font-semibold text-gray-600 text-sm text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($banners as $b): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4">
                        <div class="w-32 h-16 rounded overflow-hidden bg-gray-100 flex items-center justify-center border border-gray-200">
                            <?php if(!empty($b['image_path'])): ?>
                                <img src="<?= htmlspecialchars($b['image_path']) ?>" alt="Banner" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-xs text-gray-400">Sin imagen</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="p-4">
                        <p class="font-bold text-gray-800"><?= htmlspecialchars($b['title'] ?: 'Sin título') ?></p>
                        <?php if(!empty($b['subtitle'])): ?>
                            <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($b['subtitle']) ?></p>
                        <?php endif; ?>
                        <?php if(!empty($b['link'])): ?>
                            <a href="<?= htmlspecialchars($b['link']) ?>" target="_blank" class="text-xs text-blue-500 hover:underline flex items-center gap-1 mt-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                Enlace adjunto
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 text-center">
                        <span class="inline-block bg-gray-100 text-gray-700 text-xs font-bold px-2 py-1 rounded">
                            <?= (int)$b['sort_order'] ?>
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <?php if ($b['is_active']): ?>
                            <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 bg-gray-50 text-gray-600 text-xs font-medium px-2.5 py-1 rounded-full border border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/admin/marketing/banners/<?= $b['id'] ?>/edit" class="text-blue-600 hover:bg-blue-50 p-2 rounded transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="/admin/marketing/banners/<?= $b['id'] ?>/delete" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este banner permanentemente?');" class="inline">
                                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
                                <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded transition-colors" title="Eliminar">
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
