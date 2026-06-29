<div class="p-6 max-w-4xl mx-auto" x-data="menuEditor()">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="/admin/menus" class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-full hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h1>
        </div>
        <button type="button" @click="$refs.form.submit()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Guardar
        </button>
    </div>

    <?php if (\App\Core\Session::has('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
            <p class="text-sm text-green-700"><?= htmlspecialchars(\App\Core\Session::get('success')) ?></p>
        </div>
        <?php \App\Core\Session::remove('success'); ?>
    <?php endif; ?>

    <form x-ref="form" action="/admin/menus/<?= htmlspecialchars($menu_id) ?>" method="POST" class="space-y-6">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
        <input type="hidden" name="items" :value="JSON.stringify(items)">
        
        <!-- Info del Menú -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" value="<?= htmlspecialchars($menu['name']) ?>" disabled class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500 shadow-sm px-4 py-2 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Identificador (Handle)</label>
                <input type="text" value="<?= htmlspecialchars($menu_id) ?>" disabled class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500 shadow-sm px-4 py-2 border">
            </div>
        </div>

        <!-- Elementos del Menú -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-base font-semibold text-gray-800">Elementos del menú</h2>
            </div>
            
            <div class="p-6">
                <!-- Lista de elementos -->
                <div class="space-y-4 mb-6">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg bg-gray-50 group">
                            <div class="mt-2 text-gray-400 cursor-move">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-grow">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Etiqueta (Label)</label>
                                    <input type="text" x-model="item.label" class="w-full rounded-lg border-gray-300 shadow-sm px-3 py-2 border focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ej: Inicio">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Enlace (Link)</label>
                                    <input type="text" x-model="item.link" class="w-full rounded-lg border-gray-300 shadow-sm px-3 py-2 border focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ej: / o /catalogo">
                                </div>
                            </div>
                            <button type="button" @click="removeItem(index)" class="mt-6 text-gray-400 hover:text-red-500 transition-colors p-1" title="Eliminar elemento">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </template>
                    
                    <div x-show="items.length === 0" class="text-center py-6 text-gray-500 text-sm">
                        No hay elementos en este menú.
                    </div>
                </div>

                <!-- Botón Añadir -->
                <button type="button" @click="addItem()" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Añadir elemento al menú
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function menuEditor() {
    return {
        items: <?= json_encode(array_values($menu['items'] ?? [])) ?>,
        
        addItem() {
            this.items.push({ label: '', link: '' });
        },
        
        removeItem(index) {
            if(confirm('¿Seguro que deseas eliminar este elemento?')) {
                this.items.splice(index, 1);
            }
        }
    }
}
</script>
