<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800"><?= $title ?></h2>
    <a href="/admin/products" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">
        &larr; Volver
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8">
        <?php $isEdit = isset($product); ?>
        <form action="/admin/products<?= $isEdit ? '/' . $product['id'] : '' ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna Izquierda -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto *</label>
                        <input type="text" id="name" name="name" required 
                            value="<?= $isEdit ? htmlspecialchars($product['name']) : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                            placeholder="Ej. iPhone 15 Pro">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                            <input type="text" id="sku" name="sku" required 
                                value="<?= $isEdit ? htmlspecialchars($product['sku']) : '' ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                                placeholder="PROD-001">
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio *</label>
                            <input type="number" id="price" name="price" step="0.01" required 
                                value="<?= $isEdit ? htmlspecialchars($product['price']) : '' ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                                placeholder="99.99">
                        </div>
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                        <select id="category_id" name="category_id" required 
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white">
                            <option value="">Selecciona una categoría</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($isEdit && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="brand_id" class="block text-sm font-medium text-gray-700">Marca del Producto (Opcional)</label>
                            <button type="button" onclick="document.getElementById('quick-brand-modal').classList.remove('hidden')" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Nueva Marca
                            </button>
                        </div>
                        <select id="brand_id" name="brand_id" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white">
                            <option value="">Sin Marca / Genérico</option>
                            <?php if (!empty($brands)): ?>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand['id'] ?>" <?= ($isEdit && $product['brand_id'] == $brand['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($brand['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="space-y-6">
                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción Corta</label>
                        <textarea id="short_description" name="short_description" rows="2" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                            placeholder="Resumen del producto..."><?= $isEdit ? htmlspecialchars($product['short_description']) : '' ?></textarea>
                    </div>

                    <div>
                        <label for="full_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción Completa</label>
                        <textarea id="full_description" name="full_description" rows="5" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                            placeholder="Detalles del producto..."><?= $isEdit ? htmlspecialchars($product['full_description']) : '' ?></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                            <?= (!$isEdit || $product['is_active']) ? 'checked' : '' ?>
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Producto Activo (Visible en el catálogo)
                        </label>
                    </div>

                    <div class="flex items-center mt-3">
                        <input type="checkbox" id="has_tax" name="has_tax" value="1" 
                            <?= (!$isEdit || $product['has_tax']) ? 'checked' : '' ?>
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="has_tax" class="ml-2 block text-sm text-gray-900">
                            Aplica 7% de ITBMS
                        </label>
                    </div>
                </div>
            </div>

            <!-- Sección de Imágenes -->
            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Imágenes del Producto</h3>
                
                <?php if ($isEdit && !empty($images)): ?>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-6" id="image_preview_container">
                        <?php foreach ($images as $img): ?>
                            <div class="relative group rounded-lg overflow-hidden border border-gray-200">
                                <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Producto" class="w-full h-32 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" onclick="deleteImage(<?= $img['id'] ?>)" class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subir Nuevas Imágenes</label>
                    <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp" 
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                    <p class="mt-2 text-xs text-gray-500">Puedes subir hasta 5 imágenes en total. Formatos permitidos: JPG, PNG, WEBP. Tamaño máximo: 2MB por imagen.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors">
                    <?= $isEdit ? 'Actualizar Producto' : 'Guardar Producto' ?>
                </button>
            </div>
            
        </form>
    </div>
</div>

<script>
function deleteImage(imageId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/products/images/' + imageId + '/delete';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_csrf_token';
        csrfToken.value = document.querySelector('input[name="_csrf_token"]').value;
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

// Handle Quick Brand Creation
function createQuickBrand() {
    const brandName = document.getElementById('quick_brand_name').value;
    const btn = document.getElementById('quick-brand-btn');
    const errDiv = document.getElementById('quick-brand-error');
    
    if (!brandName.trim()) {
        errDiv.textContent = 'El nombre es obligatorio.';
        errDiv.classList.remove('hidden');
        return;
    }
    
    btn.disabled = true;
    btn.textContent = 'Guardando...';
    errDiv.classList.add('hidden');
    
    const formData = new FormData();
    formData.append('name', brandName);
    formData.append('_csrf_token', document.querySelector('input[name="_csrf_token"]').value);
    
    fetch('/admin/brands/ajax', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add to select and select it
            const select = document.getElementById('brand_id');
            const option = document.createElement('option');
            option.value = data.brand.id;
            option.text = data.brand.name;
            option.selected = true;
            select.appendChild(option);
            
            // Close modal and reset
            document.getElementById('quick-brand-modal').classList.add('hidden');
            document.getElementById('quick_brand_name').value = '';
        } else {
            errDiv.textContent = data.message || 'Error al guardar la marca.';
            errDiv.classList.remove('hidden');
        }
    })
    .catch(error => {
        errDiv.textContent = 'Ocurrió un error en el servidor.';
        errDiv.classList.remove('hidden');
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Crear y Seleccionar';
    });
}
</script>

<!-- Quick Brand Modal -->
<div id="quick-brand-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-sm w-full mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Nueva Marca</h3>
            <button type="button" onclick="document.getElementById('quick-brand-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <label for="quick_brand_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Marca *</label>
                <input type="text" id="quick_brand_name" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Ej. Sony, Samsung...">
                <p id="quick-brand-error" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('quick-brand-modal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">Cancelar</button>
                <button type="button" id="quick-brand-btn" onclick="createQuickBrand()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">Crear y Seleccionar</button>
            </div>
        </div>
    </div>
</div>
