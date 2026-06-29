<div class="mb-6">
    <div class="flex items-center text-sm text-gray-500 mb-2">
        <a href="/admin/marketing/campaigns" class="hover:text-gray-900 transition-colors">Campañas</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-medium">Nueva Campaña</span>
    </div>
    <h2 class="text-2xl font-bold text-gray-800">Crear Campaña</h2>
</div>

<form action="/admin/marketing/campaigns" method="POST" class="max-w-3xl">
    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Información General</h3>
            <p class="text-sm text-gray-500 mt-1">Define los datos principales de tu evento comercial.</p>
        </div>
        <div class="p-6 space-y-6">
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Campaña *</label>
                <input type="text" id="name" name="name" required placeholder="Ej: Black Friday 2026" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="description" name="description" rows="3" placeholder="Detalles de la campaña comercial..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                    <input type="datetime-local" id="start_date" name="start_date"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin</label>
                    <input type="datetime-local" id="end_date" name="end_date"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado Inicial</label>
                <select id="status" name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                    <option value="draft">Borrador (No visible)</option>
                    <option value="scheduled">Programada (Se activará en fecha de inicio)</option>
                    <option value="active">Activa (Visible inmediatamente)</option>
                </select>
            </div>

        </div>
    </div>
    
    <div class="flex justify-end gap-4">
        <a href="/admin/marketing/campaigns" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            Cancelar
        </a>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            Guardar Campaña
        </button>
    </div>
</form>
