<div class="mb-6">
    <div class="flex items-center text-sm text-gray-500 mb-2">
        <a href="/admin/marketing/offers" class="hover:text-gray-900 transition-colors">Ofertas</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-medium">Editar Oferta</span>
    </div>
    <h2 class="text-2xl font-bold text-gray-800">Editar Oferta: <?= htmlspecialchars($offer['name']) ?></h2>
</div>

<form action="/admin/marketing/offers/<?= $offer['id'] ?>" method="POST" class="max-w-4xl">
    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Información General</h3>
            <p class="text-sm text-gray-500 mt-1">Configura el nombre y el valor del descuento directo.</p>
        </div>
        <div class="p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Interno *</label>
                    <input type="text" id="name" name="name" required value="<?= htmlspecialchars($offer['name']) ?>" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label for="campaign_id" class="block text-sm font-medium text-gray-700 mb-1">Vincular a Campaña</label>
                    <select id="campaign_id" name="campaign_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="">-- Ninguna --</option>
                        <?php if(isset($campaigns)): foreach($campaigns as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $offer['campaign_id'] == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                <div>
                    <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Descuento</label>
                    <select id="discount_type" name="discount_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="percentage" <?= $offer['discount_type'] == 'percentage' ? 'selected' : '' ?>>Porcentaje (%)</option>
                        <option value="fixed_amount" <?= $offer['discount_type'] == 'fixed_amount' ? 'selected' : '' ?>>Monto Fijo ($)</option>
                    </select>
                </div>
                <div>
                    <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-1">Valor del Descuento *</label>
                    <input type="number" step="0.01" id="discount_value" name="discount_value" required value="<?= htmlspecialchars((string)$offer['discount_value']) ?>" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-gray-100 pt-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Inicio (Opcional)</label>
                    <input type="datetime-local" id="start_date" name="start_date" value="<?= $offer['start_date'] ? date('Y-m-d\TH:i', strtotime($offer['start_date'])) : '' ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Fin (Opcional)</label>
                    <input type="datetime-local" id="end_date" name="end_date" value="<?= $offer['end_date'] ? date('Y-m-d\TH:i', strtotime($offer['end_date'])) : '' ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="active" <?= $offer['status'] == 'active' ? 'selected' : '' ?>>Activa</option>
                        <option value="inactive" <?= $offer['status'] == 'inactive' ? 'selected' : '' ?>>Inactiva</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 mt-2">
                <h4 class="text-sm font-semibold text-gray-800 mb-4">¿A qué aplica esta oferta?</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="target_type" class="block text-sm font-medium text-gray-700 mb-1">Alcance de la Oferta</label>
                        <select id="target_type" name="target_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white" onchange="toggleTargetSelect()">
                            <option value="global" <?= $offer['target_type'] == 'global' ? 'selected' : '' ?>>Toda la Tienda (Global)</option>
                            <option value="category" <?= $offer['target_type'] == 'category' ? 'selected' : '' ?>>Categoría Específica</option>
                            <option value="brand" <?= $offer['target_type'] == 'brand' ? 'selected' : '' ?>>Marca Específica</option>
                            <option value="product" <?= $offer['target_type'] == 'product' ? 'selected' : '' ?>>Producto Específico</option>
                        </select>
                    </div>

                    <div id="target_id_container" style="display: none;">
                        <label id="target_id_label" for="target_id" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Elemento</label>
                        <p class="text-xs text-gray-500 mb-2">Mantén presionado CTRL (o CMD en Mac) para seleccionar varios elementos.</p>
                        
                        <!-- Categorías -->
                        <select id="target_category" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white hidden-select h-32">
                            <?php if(isset($categories)): foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($offer['target_type'] == 'category' && in_array($cat['id'], $offer['target_ids'] ?? [])) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>

                        <!-- Marcas -->
                        <select id="target_brand" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white hidden-select h-32">
                            <?php if(isset($brands)): foreach($brands as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= ($offer['target_type'] == 'brand' && in_array($b['id'], $offer['target_ids'] ?? [])) ? 'selected' : '' ?>><?= htmlspecialchars($b['name']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>

                        <!-- Productos -->
                        <select id="target_product" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white hidden-select h-32">
                            <?php if(isset($products)): foreach($products as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= ($offer['target_type'] == 'product' && in_array($p['id'], $offer['target_ids'] ?? [])) ? 'selected' : '' ?>><?= htmlspecialchars($p['name']) ?> (<?= htmlspecialchars($p['sku']) ?>)</option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <div class="flex justify-end gap-4 mt-6">
        <a href="/admin/marketing/offers" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            Cancelar
        </a>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            Actualizar Oferta
        </button>
    </div>
</form>

<script>
function toggleTargetSelect() {
    const type = document.getElementById('target_type').value;
    const container = document.getElementById('target_id_container');
    const label = document.getElementById('target_id_label');
    
    document.querySelectorAll('.hidden-select').forEach(el => {
        el.style.display = 'none';
        el.removeAttribute('name');
    });

    if (type === 'global') {
        container.style.display = 'none';
    } else {
        container.style.display = 'block';
        let selectEl = null;
        
        if (type === 'category') {
            label.innerText = 'Seleccionar Categoría (Múltiple)';
            selectEl = document.getElementById('target_category');
        } else if (type === 'brand') {
            label.innerText = 'Seleccionar Marca (Múltiple)';
            selectEl = document.getElementById('target_brand');
        } else if (type === 'product') {
            label.innerText = 'Seleccionar Producto (Múltiple)';
            selectEl = document.getElementById('target_product');
        }

        if (selectEl) {
            selectEl.style.display = 'block';
            selectEl.setAttribute('name', 'target_ids[]');
        }
    }
}
// Init
toggleTargetSelect();
</script>
