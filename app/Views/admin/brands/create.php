<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="/admin/brands" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-semibold text-gray-800">
            <?= $isEdit ? 'Editar Marca' : 'Nueva Marca' ?>
        </h2>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6">
        <form action="/admin/brands<?= $isEdit ? '/' . $brand['id'] : '' ?>" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-2xl">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Marca <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="nameInput" required 
                           value="<?= htmlspecialchars($brand['name'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug (URL) <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" id="slugInput" required 
                           value="<?= htmlspecialchars($brand['slug'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors bg-gray-50">
                    <p class="text-xs text-gray-500 mt-1">Identificador único para la URL. Solo letras minúsculas, números y guiones.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo de la Marca</label>
                
                <?php if ($isEdit && !empty($brand['logo'])): ?>
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-2">Logo actual:</p>
                        <div class="h-20 w-20 rounded border border-gray-200 bg-gray-50 flex items-center justify-center p-2">
                            <img src="<?= htmlspecialchars($brand['logo']) ?>" alt="Logo actual" class="max-h-full max-w-full object-contain">
                        </div>
                    </div>
                <?php endif; ?>
                
                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/svg+xml"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-2">Formatos soportados: JPG, PNG, WEBP, SVG. <?= $isEdit ? 'Sube un archivo solo si deseas reemplazar el actual.' : '' ?></p>
            </div>

            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?= ($brand['is_active'] ?? 1) ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-700">Marca Activa</span>
                </label>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <a href="/admin/brands" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition-colors">
                    <?= $isEdit ? 'Guardar Cambios' : 'Crear Marca' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('nameInput');
    const slugInput = document.getElementById('slugInput');
    
    // Auto-generate slug only on create
    <?php if (!$isEdit): ?>
    nameInput.addEventListener('input', function() {
        let slug = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '') // Remove non-word chars
            .replace(/[\s_-]+/g, '-') // Swap spaces and dashes for a single dash
            .replace(/^-+|-+$/g, ''); // Trim leading/trailing dashes
        slugInput.value = slug;
    });
    <?php endif; ?>
});
</script>
