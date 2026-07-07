<div class="mb-6">
    <div class="flex items-center text-sm text-gray-500 mb-2">
        <a href="/admin/marketing/promotions" class="hover:text-gray-900 transition-colors">Promociones</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-medium">Editar Promoción</span>
    </div>
    <h2 class="text-2xl font-bold text-gray-800">Editar Promoción: <?= htmlspecialchars($promotion['name']) ?></h2>
</div>

<form action="/admin/marketing/promotions/<?= $promotion['id'] ?>" method="POST" class="max-w-4xl">
    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Información General</h3>
        </div>
        <div class="p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Interno *</label>
                    <input type="text" id="name" name="name" required value="<?= htmlspecialchars($promotion['name']) ?>" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label for="campaign_id" class="block text-sm font-medium text-gray-700 mb-1">Vincular a Campaña</label>
                    <select id="campaign_id" name="campaign_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="">-- Ninguna --</option>
                        <?php if(isset($campaigns)): foreach($campaigns as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $promotion['campaign_id'] == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Mecánica Promocional</label>
                    <select id="type" name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="free_shipping" <?= $promotion['type'] == 'free_shipping' ? 'selected' : '' ?>>Envío Gratis</option>
                        <option value="gift" <?= $promotion['type'] == 'gift' ? 'selected' : '' ?>>Regalo por Compra</option>
                        <option value="bogo" <?= $promotion['type'] == 'bogo' ? 'selected' : '' ?>>Lleva 2 Paga 1 (BOGO)</option>
                        <option value="discount_code" <?= $promotion['type'] == 'discount_code' ? 'selected' : '' ?>>Código de Descuento</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado Inicial</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="active" <?= $promotion['status'] == 'active' ? 'selected' : '' ?>>Activa</option>
                        <option value="inactive" <?= $promotion['status'] == 'inactive' ? 'selected' : '' ?>>Inactiva</option>
                    </select>
                </div>
            </div>

        </div>

            <div class="border-t border-gray-100 pt-6 mt-2">
                <div class="flex items-start gap-4">
                    <div class="flex items-center h-6 mt-0.5">
                        <input type="hidden" name="show_in_menu" value="0">
                        <input
                            type="checkbox"
                            id="show_in_menu"
                            name="show_in_menu"
                            value="1"
                            <?= !empty($promotion['show_in_menu']) ? 'checked' : '' ?>
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer"
                        >
                    </div>
                    <div>
                        <label for="show_in_menu" class="text-sm font-semibold text-gray-800 cursor-pointer">Mostrar en Menú Público</label>
                        <p class="text-xs text-gray-500 mt-0.5">Al activar esta opción, la sección <strong>"Promociones"</strong> aparecerá en el menú de navegación del sitio web.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <div class="flex justify-end gap-4">
        <a href="/admin/marketing/promotions" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            Cancelar
        </a>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            Actualizar Promoción
        </button>
    </div>
</form>
