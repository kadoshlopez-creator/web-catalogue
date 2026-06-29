<div class="mb-6">
    <div class="flex items-center text-sm text-gray-500 mb-2">
        <a href="/admin/marketing/banners" class="hover:text-gray-900 transition-colors">Banners</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-medium">Nuevo Banner</span>
    </div>
    <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Banner</h2>
</div>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        <?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
    </div>
<?php endif; ?>

<form action="/admin/marketing/banners" method="POST" enctype="multipart/form-data" class="max-w-4xl">
    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Información del Banner</h3>
            <p class="text-sm text-gray-500 mt-1">Sube la imagen y configura los textos y enlaces asociados.</p>
        </div>
        <div class="p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título (Opcional)</label>
                    <input type="text" id="title" name="title" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="Ej: Rebajas de Verano">
                </div>
                <div>
                    <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtítulo (Opcional)</label>
                    <input type="text" id="subtitle" name="subtitle" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="Ej: Hasta 50% de descuento">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del Banner *</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                            <label for="image" class="relative cursor-pointer rounded-md bg-white font-semibold text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 hover:text-blue-500">
                                <span>Sube un archivo</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/jpeg,image/png,image/webp" required onchange="previewImage(event)">
                            </label>
                            <p class="pl-1">o arrastra y suelta</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 font-medium">Recomendado: 1856 x 576 píxeles</p>
                        <p class="text-xs text-gray-400">Formatos: PNG, JPG, WEBP. Tamaño máximo: 5MB</p>
                    </div>
                </div>
                <!-- Image Preview -->
                <div id="image_preview_container" class="mt-4 hidden rounded-lg overflow-hidden border border-gray-200">
                    <img id="image_preview" src="" alt="Vista previa" class="w-full h-auto object-cover max-h-64">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                <div>
                    <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Enlace / URL de destino (Opcional)</label>
                    <input type="text" id="link" name="link" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="https://ejemplo.com/producto">
                    <p class="text-xs text-gray-500 mt-1">¿A dónde debe ir el cliente al hacer clic?</p>
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Orden de aparición</label>
                    <input type="number" id="sort_order" name="sort_order" value="0" min="0" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    <p class="text-xs text-gray-500 mt-1">Menor número aparece primero en el carrusel.</p>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                    <div>
                        <span class="block text-sm font-medium text-gray-900">Activar Banner inmediatamente</span>
                        <span class="block text-xs text-gray-500">Si lo desmarcas, se guardará como borrador.</span>
                    </div>
                </label>
            </div>

        </div>
    </div>
    
    <div class="flex justify-end gap-4">
        <a href="/admin/marketing/banners" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            Cancelar
        </a>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            Crear Banner
        </button>
    </div>
</form>

<script>
function previewImage(event) {
    if(event.target.files && event.target.files.length > 0) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('image_preview');
            output.src = reader.result;
            document.getElementById('image_preview_container').classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
