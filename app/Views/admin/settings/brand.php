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

    <!-- Credenciales de Acceso -->
    <div class="p-6 bg-white rounded-xl border-t border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            Credenciales de Acceso
        </h3>
        <p class="text-xs text-gray-500 mb-4">Actualiza el correo electrónico y la contraseña para ingresar a este panel de administración.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider mb-2">Usuario / Email</label>
                <input type="email" name="admin_email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <div x-data="passwordManager()">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider mb-2">Nueva Contraseña</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" name="admin_password" x-model="password" placeholder="Dejar en blanco para no cambiar" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm pr-20">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 gap-1">
                        <button type="button" @click="showPassword = !showPassword" class="p-1 text-gray-400 hover:text-gray-600 focus:outline-none" title="Mostrar/Ocultar contraseña">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showPassword" class="w-4 h-4" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                        <button type="button" @click="generatePassword()" class="p-1 text-blue-500 hover:text-blue-700 focus:outline-none" title="Generar contraseña segura">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </div>
                </div>
                
                <div x-show="password.length > 0" class="mt-3 space-y-1" style="display: none;">
                    <p class="text-xs font-semibold text-gray-700 mb-2">Requisitos de seguridad:</p>
                    <div class="flex items-center gap-2 text-xs" :class="password.length >= 8 ? 'text-green-600' : 'text-gray-500'">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        <span>Mínimo 8 caracteres</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs" :class="/[A-Z]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        <span>Al menos una letra mayúscula</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs" :class="/[a-z]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        <span>Al menos una letra minúscula</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs" :class="/[0-9]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        <span>Al menos un número</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs" :class="/[^A-Za-z0-9]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        <span>Al menos un carácter especial (!@#$%^&*)</span>
                    </div>
                </div>
                <p x-show="password.length === 0" class="text-[10px] text-gray-500 mt-1">Si no deseas cambiar la contraseña actual, deja este campo vacío.</p>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('passwordManager', () => ({
            password: '',
            showPassword: false,
            generatePassword() {
                const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=';
                let generated = '';
                // Asegurar al menos uno de cada tipo
                generated += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)];
                generated += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)];
                generated += '0123456789'[Math.floor(Math.random() * 10)];
                generated += '!@#$%^&*()_+~`|}{[]:;?><,./-='[Math.floor(Math.random() * 29)];
                
                // Rellenar hasta 16 caracteres
                for (let i = 0; i < 12; i++) {
                    generated += chars[Math.floor(Math.random() * chars.length)];
                }
                
                // Mezclar la contraseña
                this.password = generated.split('').sort(() => 0.5 - Math.random()).join('');
                this.showPassword = true;
            }
        }));
    });

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
