<div class="mb-6">
    <div class="flex items-center text-sm text-gray-500 mb-2">
        <a href="/admin/marketing/promotions" class="hover:text-gray-900 transition-colors">Promociones</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-medium">Nueva Promoción</span>
    </div>
    <h2 class="text-2xl font-bold text-gray-800">Crear Regla Promocional</h2>
</div>

<form action="/admin/marketing/promotions" method="POST" class="max-w-4xl">
    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Mecánica de la Promoción</h3>
            <p class="text-sm text-gray-500 mt-1">Configura el tipo de incentivo (BOGO, Envío Gratis, Regalo).</p>
        </div>
        <div class="p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Interno *</label>
                    <input type="text" id="name" name="name" required placeholder="Ej: Envío Gratis en todo el país" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label for="campaign_id" class="block text-sm font-medium text-gray-700 mb-1">Vincular a Campaña</label>
                    <select id="campaign_id" name="campaign_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="">-- Ninguna --</option>
                        <?php if(isset($campaigns)): foreach($campaigns as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
            </div>
            
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Promoción</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300">
                        <input type="radio" name="type" value="free_shipping" class="sr-only" checked>
                        <span class="flex flex-1">
                            <span class="flex flex-col">
                                <span class="block text-sm font-medium text-gray-900">Envío Gratis</span>
                                <span class="mt-1 flex items-center text-sm text-gray-500">Aplica 100% de descuento al costo de envío.</span>
                            </span>
                        </span>
                        <svg class="h-5 w-5 text-blue-600 hidden check-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </label>
                    
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 opacity-50">
                        <input type="radio" name="type" value="bogo" class="sr-only" disabled>
                        <span class="flex flex-1">
                            <span class="flex flex-col">
                                <span class="block text-sm font-medium text-gray-900">BOGO (Próximamente)</span>
                                <span class="mt-1 flex items-center text-sm text-gray-500">Buy One Get One (Lleva 2 paga 1, etc.)</span>
                            </span>
                        </span>
                    </label>
                </div>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select id="status" name="status" class="w-full sm:w-1/3 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                    <option value="active">Activa</option>
                    <option value="inactive">Inactiva</option>
                </select>
            </div>

            </div>

            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-start gap-4">
                    <div class="flex items-center h-6 mt-0.5">
                        <input type="hidden" name="show_in_menu" value="0">
                        <input
                            type="checkbox"
                            id="show_in_menu"
                            name="show_in_menu"
                            value="1"
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
            Guardar Promoción
        </button>
    </div>
</form>

<style>
    input[type="radio"]:checked + span + svg.check-icon {
        display: block;
    }
    input[type="radio"]:checked ~ span {
        border-color: #2563eb;
    }
</style>
