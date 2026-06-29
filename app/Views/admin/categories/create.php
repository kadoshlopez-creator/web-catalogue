<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800"><?= $title ?></h2>
    <a href="/admin/categories" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">
        &larr; Volver
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8">
        <?php $isEdit = isset($category); ?>
        <form action="/admin/categories<?= $isEdit ? '/' . $category['id'] : '' ?>" method="POST" class="space-y-6 max-w-2xl">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Categoría *</label>
                <input type="text" id="name" name="name" required 
                    value="<?= $isEdit ? htmlspecialchars($category['name']) : '' ?>"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                    placeholder="Ej. Zapatillas">
            </div>

            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría Padre (Opcional)</label>
                <select id="parent_id" name="parent_id" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white">
                    <option value="">-- Principal (Nivel 1) --</option>
                    <?php if (isset($parents) && !empty($parents)): ?>
                        <?php foreach ($parents as $parent): ?>
                            <?php $selected = ($isEdit && $category['parent_id'] == $parent['id']) ? 'selected' : ''; ?>
                            <option value="<?= $parent['id'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($parent['display_name']) ?> (Nivel <?= $parent['level'] ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p class="text-xs text-gray-500 mt-1">Solo se permiten hasta 3 niveles (Principal > Subcategoría > Subsubcategoría).</p>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="description" name="description" rows="4" 
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                    placeholder="Breve descripción de la categoría..."><?= $isEdit ? htmlspecialchars($category['description']) : '' ?></textarea>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" 
                    <?= (!$isEdit || $category['is_active']) ? 'checked' : '' ?>
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Categoría Activa (Visible en el catálogo)
                </label>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors">
                    <?= $isEdit ? 'Actualizar Categoría' : 'Guardar Categoría' ?>
                </button>
            </div>
            
        </form>
    </div>
</div>
