<?php
use App\Core\Session;
?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900"><?= $title ?></h2>
    <button type="button" onclick="document.getElementById('brand-form').submit()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        Guardar Configuración
    </button>
</div>

<?php if (Session::has('success')): ?>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
        <p class="text-sm text-green-700"><?= htmlspecialchars(Session::get('success')) ?></p>
    </div>
    <?php Session::remove('success'); ?>
<?php endif; ?>
<?php if (Session::has('error')): ?>
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
        <p class="text-sm text-red-700"><?= htmlspecialchars(Session::get('error')) ?></p>
    </div>
    <?php Session::remove('error'); ?>
<?php endif; ?>

<form id="delete-asset-form" action="/admin/settings/delete-asset" method="POST" class="hidden">
    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
    <input type="hidden" name="type" id="delete-asset-type" value="">
</form>

<form id="brand-form" action="/admin/settings/brand" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200">
    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
    
    <!-- Configuración del Logo -->
    <div class="p-6 bg-white rounded-xl">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Logo y Favicon
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Logo -->
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Logo Principal</h4>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 relative">
                        <?php if (!empty($site_logo)): ?>
                            <img src="<?= htmlspecialchars($site_logo) ?>" alt="Logo actual" class="h-16 object-contain border border-gray-200 rounded p-2 bg-gray-50">
                            <button type="button" onclick="deleteAsset('logo')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md transition-colors" title="Eliminar Logo">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        <?php else: ?>
                            <div class="h-16 w-24 border border-dashed border-gray-300 rounded flex items-center justify-center text-gray-400 bg-gray-50 text-xs text-center">
                                Sin logo
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow">
                        <input type="file" name="logo" accept="image/png, image/jpeg, image/webp, image/svg+xml" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-2">
                        <div class="mb-1">
                            <label class="text-xs text-gray-600 font-medium flex justify-between">
                                Tamaño del logo (alto)
                                <span id="logo-height-val"><?= htmlspecialchars($site_logo_height ?? '48') ?>px</span>
                            </label>
                            <input type="range" name="logo_height" min="30" max="120" value="<?= htmlspecialchars($site_logo_height ?? '48') ?>" class="w-full h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="document.getElementById('logo-height-val').innerText = this.value + 'px';">
                        </div>
                        <p class="text-[10px] text-gray-500">Ajusta el tamaño con el deslizador (recomendado: 48px). (PNG, JPG, SVG, WEBP)</p>
                    </div>
                </div>
            </div>

            <!-- Favicon -->
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Favicon</h4>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 relative">
                        <?php if (!empty($site_favicon)): ?>
                            <img src="<?= htmlspecialchars($site_favicon) ?>" alt="Favicon actual" class="h-12 w-12 object-contain border border-gray-200 rounded bg-gray-50 p-1">
                            <button type="button" onclick="deleteAsset('favicon')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md transition-colors" title="Eliminar Favicon">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        <?php else: ?>
                            <div class="h-12 w-12 border border-dashed border-gray-300 rounded flex items-center justify-center text-gray-400 bg-gray-50 text-xs">
                                N/A
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow">
                        <input type="file" name="favicon" accept=".ico, image/png, image/svg+xml, image/webp" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-1">
                        <p class="text-[10px] text-gray-500 mt-2">Recomendado: 32x32px. Formatos: ICO, PNG, SVG, WEBP.</p>
                    </div>
                </div>
            </div>

            <!-- Login Logo -->
            <div class="md:col-span-2 mt-4 pt-6 border-t border-gray-100">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Logo de Inicio de Sesión</h4>
                <p class="text-xs text-gray-500 mb-4">Sube un logo específico para la página de Login (opcional). Si no subes uno, se usará el Logo Principal.</p>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 relative">
                        <?php if (!empty($login_logo)): ?>
                            <img src="<?= htmlspecialchars($login_logo) ?>" alt="Logo Login" class="h-16 object-contain border border-gray-200 rounded p-2 bg-gray-50">
                            <button type="button" onclick="deleteAsset('login_logo')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md transition-colors" title="Eliminar Logo de Login">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        <?php else: ?>
                            <div class="h-16 w-24 border border-dashed border-gray-300 rounded flex items-center justify-center text-gray-400 bg-gray-50 text-xs text-center">
                                Sin logo
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow">
                        <input type="file" name="login_logo" accept="image/png, image/jpeg, image/webp, image/svg+xml" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-2">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function deleteAsset(type) {
        const msg = type === 'logo' 
            ? '¿Está seguro que desea eliminar el logo actual?' 
            : '¿Está seguro que desea eliminar el favicon actual?';
            
        if (confirm(msg)) {
            document.getElementById('delete-asset-type').value = type;
            document.getElementById('delete-asset-form').submit();
        }
    }
</script>
