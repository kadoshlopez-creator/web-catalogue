<?php
use App\Core\Session;
?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Constructor de Inicio</h2>
    <button type="button" onclick="document.getElementById('home-builder-form').submit()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        Guardar Configuración
    </button>
</div>

<form id="delete-asset-form" action="/admin/settings/delete-asset" method="POST" class="hidden">
    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
    <input type="hidden" name="type" id="delete-asset-type" value="">
</form>

<form id="home-builder-form" action="/admin/settings/home" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200">
    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
    
    <!-- Configuración del Logo -->
    <div class="p-6 border-b border-gray-200 bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Logo del Sitio
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
                        <p class="text-xs text-gray-500">16×16 px o 32×32 px. (ICO, PNG, SVG)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="p-6 border-b border-gray-200 bg-gray-50/50">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            Barra de Anuncios (Ribbon)
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="flex items-center gap-2 cursor-pointer mb-4">
                    <input type="checkbox" name="ribbon[is_active]" value="1" <?= !empty($ribbon['is_active']) ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Habilitar Ribbon</span>
                </label>

                <label class="block text-sm font-medium text-gray-700 mb-1">Texto del Anuncio</label>
                <textarea name="ribbon[text]" rows="2" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Ej: Obtén un 10% de descuento adicional..."><?= htmlspecialchars($ribbon['text'] ?? '') ?></textarea>
            </div>
            
            <div class="space-y-4">
                <!-- Advanced Color & Gradient Picker -->
                <?php
                // Build a default CSS fallback if bg_css doesn't exist
                $defaultCss = $ribbon['bg_css'] ?? '';
                if (empty($defaultCss)) {
                    $grad = $ribbon['gradient'] ?? 'red';
                    if ($grad === 'blue') $defaultCss = 'linear-gradient(to right, #3b82f6, #1e40af)';
                    elseif ($grad === 'purple') $defaultCss = 'linear-gradient(to right, #a855f7, #6b21a8)';
                    elseif ($grad === 'orange') $defaultCss = 'linear-gradient(to right, #f97316, #9a3412)';
                    elseif ($grad === 'dark') $defaultCss = 'linear-gradient(to right, #374151, #111827)';
                    else $defaultCss = 'linear-gradient(to right, #ef4444, #991b1b)';
                }
                ?>
                <div x-data="colorPicker('<?= htmlspecialchars($defaultCss) ?>')">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color / Degradado (Ribbon)</label>
                    <input type="hidden" name="ribbon[bg_css]" x-model="outputCss">
                    
                    <!-- UI for Solid vs Gradient -->
                    <div class="flex bg-gray-100 rounded-lg p-1 mb-4 shadow-inner">
                        <button type="button" @click="type = 'solid'; updateCss();" :class="type === 'solid' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-1.5 text-sm font-medium rounded-md transition-colors">Solid</button>
                        <button type="button" @click="type = 'gradient'; updateCss();" :class="type === 'gradient' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-1.5 text-sm font-medium rounded-md transition-colors">Gradient</button>
                    </div>

                    <!-- Solid Settings -->
                    <div x-show="type === 'solid'" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-200 shadow-sm shrink-0">
                            <input type="color" x-model="solidColor" @input="updateCss()" class="w-16 h-16 -mt-2 -ml-2 cursor-pointer border-0 p-0">
                        </div>
                        <input type="text" x-model="solidColor" @input="updateCss()" class="flex-1 border-gray-300 rounded-lg text-sm uppercase font-mono px-3 py-2 focus:ring-blue-500 focus:border-blue-500" placeholder="#HEX">
                    </div>

                    <!-- Gradient Settings -->
                    <div x-show="type === 'gradient'" class="space-y-4">
                        <div class="flex gap-3">
                            <select x-model="gradType" @change="updateCss()" class="flex-1 border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="linear">Linear</option>
                                <option value="radial">Radial</option>
                            </select>
                            <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-2 bg-white" x-show="gradType === 'linear'">
                                <input type="number" x-model="gradAngle" @input="updateCss()" class="w-14 border-0 p-1 text-sm text-center focus:ring-0 font-mono" min="0" max="360">
                                <span class="text-gray-500 text-sm font-medium">°</span>
                            </div>
                        </div>
                        
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Stops</span>
                            <div class="space-y-3">
                                <!-- Stop 1 -->
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded border border-gray-200 overflow-hidden shadow-sm shrink-0">
                                        <input type="color" x-model="color1" @input="updateCss()" class="w-12 h-12 -mt-2 -ml-2 cursor-pointer border-0 p-0">
                                    </div>
                                    <input type="text" x-model="color1" @input="updateCss()" class="w-24 border-gray-300 rounded-lg text-sm uppercase font-mono px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-gray-400 font-medium">0%</span>
                                </div>
                                <!-- Stop 2 -->
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded border border-gray-200 overflow-hidden shadow-sm shrink-0">
                                        <input type="color" x-model="color2" @input="updateCss()" class="w-12 h-12 -mt-2 -ml-2 cursor-pointer border-0 p-0">
                                    </div>
                                    <input type="text" x-model="color2" @input="updateCss()" class="w-24 border-gray-300 rounded-lg text-sm uppercase font-mono px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-gray-400 font-medium">100%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Real-time Preview -->
                    <div class="mt-5 h-12 rounded-lg border border-gray-200 shadow-inner flex items-center justify-center text-white font-medium text-sm overflow-hidden" :style="`background: ${outputCss}`">
                        Previsualización
                    </div>
                </div>
                
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="ribbon[is_marquee]" value="1" <?= !empty($ribbon['is_marquee']) ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Animación de Marquesina (Texto en movimiento)</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- HERO SECTION (DYNAMIC SLIDES) -->
    <?php
    $settingModel = new \App\Models\Setting();
    $heroSettings = $settingModel->get('home_hero_settings', ['is_active' => false]);
    $heroSlides = $settingModel->get('home_hero_slides', []);
    
    // Migration from old single hero
    if (empty($heroSlides)) {
        $oldHero = $settingModel->get('home_hero');
        if (!empty($oldHero)) {
            $heroSettings['is_active'] = $oldHero['is_active'] ?? false;
            $heroSlides[] = [
                'title' => $oldHero['title'] ?? 'La mejor tecnología al mejor precio',
                'subtitle' => $oldHero['subtitle'] ?? '',
                'btn_text' => $oldHero['btn_text'] ?? 'Ver Catálogo',
                'btn_link' => $oldHero['btn_link'] ?? '/catalog',
                'layout' => 'text_left',
                'image_path' => ''
            ];
        } else {
            $heroSlides[] = [
                'title' => 'La mejor tecnología al mejor precio',
                'subtitle' => 'Descubre nuestra increíble selección de productos electrónicos de alta gama. Equipos listos para llevar tu productividad y entretenimiento al siguiente nivel.',
                'btn_text' => 'Ver Catálogo',
                'btn_link' => '/catalog',
                'layout' => 'text_left',
                'image_path' => ''
            ];
        }
    }
    ?>
    <div class="p-6 border-b border-gray-200 bg-gray-50/50">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Sección Principal (Hero Carrusel)
            </h3>
            <button type="button" onclick="addHeroSlide()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Añadir Diapositiva
            </button>
        </div>
        
        <label class="flex items-center gap-2 cursor-pointer mb-6">
            <input type="checkbox" name="hero_settings[is_active]" value="1" <?= !empty($heroSettings['is_active']) ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
            <span class="text-sm font-medium text-gray-700">Habilitar Sección Hero</span>
        </label>

        <div id="hero-slides-container" class="space-y-4">
            <!-- Hero slides injected via JS -->
        </div>
    </div>

    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Bloques de Productos
            </h3>
            <button type="button" onclick="addSection()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Añadir Sección
            </button>
        </div>
        <div id="sections-container" class="space-y-4">
            <!-- Sections will be injected here via JS -->
        </div>

        <div id="empty-state" class="text-center py-12 <?= !empty($sections) ? 'hidden' : '' ?>">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
            <p class="text-gray-500 text-lg">No hay secciones configuradas para la página de inicio.</p>
            <p class="text-gray-400 text-sm mt-1">Haz clic en "Añadir Sección" para comenzar a construir.</p>
        </div>
    </div>
    
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end rounded-b-xl">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Guardar Configuración
        </button>
    </div>
</form>

<!-- HERO SLIDE TEMPLATE -->
<template id="hero-slide-template">
    <div class="hero-slide-item bg-white border border-gray-200 rounded-xl p-5 shadow-sm relative group mb-4">
        <div class="absolute -left-3 top-1/2 -translate-y-1/2 bg-white border border-gray-200 rounded-full p-2 cursor-move shadow-sm text-gray-400 hover:text-gray-600 hero-drag-handle">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9h8M8 15h8"></path></svg>
        </div>
        
        <button type="button" onclick="this.closest('.hero-slide-item').remove()" class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pl-4">
            <!-- Text Content -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Título</label>
                    <input type="text" name="hero_slides[{index}][title]" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 hero-title-input" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Subtítulo</label>
                    <textarea name="hero_slides[{index}][subtitle]" rows="2" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 hero-subtitle-input"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Texto Botón</label>
                        <input type="text" name="hero_slides[{index}][btn_text]" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 hero-btn-text-input">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Link Botón</label>
                        <input type="text" name="hero_slides[{index}][btn_link]" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 hero-btn-link-input">
                    </div>
                </div>
            </div>
            
            <!-- Image & Layout -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Diseño (Posición)</label>
                    <select name="hero_slides[{index}][layout]" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 hero-layout-input">
                        <option value="text_left">Texto a la Izquierda / Imagen a la Derecha</option>
                        <option value="text_right">Imagen a la Izquierda / Texto a la Derecha</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Imagen (Opcional)</label>
                    <div class="flex items-center gap-4">
                        <input type="hidden" name="hero_slides[{index}][existing_image]" class="hero-existing-image-input">
                        <div class="relative hidden hero-image-preview-container flex-shrink-0">
                            <img src="" class="h-16 w-16 object-cover rounded bg-gray-100 hero-image-preview border border-gray-200">
                            <button type="button" onclick="removeHeroImage(this)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md transition-colors" title="Eliminar Imagen">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="flex-grow">
                            <input type="file" name="hero_images[{index}]" accept="image/*" onchange="previewHeroImage(this)" class="block w-full text-sm text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 hero-file-input mb-1">
                            <p class="text-[10px] text-gray-500 leading-tight">Dimensiones recomendadas: 800x800 px.<br>Formatos: PNG, WEBP (recomendado sin fondo).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

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

    const categories = <?= json_encode($categories) ?>;
    const brands = <?= json_encode($brands) ?>;
    const initialSections = <?= json_encode($sections) ?>;
    
    let sectionCount = 0;
    const container = document.getElementById('sections-container');
    const emptyState = document.getElementById('empty-state');

    function createSectionHTML(index, data = {}) {
        const type = data.type || 'latest';
        const title = data.title || '';
        const subtitle = data.subtitle || '';
        const limit = data.limit || 4;
        const catId = data.category_id || '';
        const brandId = data.brand_id || '';

        let catOptions = '<option value="">Seleccione una categoría...</option>';
        categories.forEach(c => {
            catOptions += `<option value="${c.id}" ${catId == c.id ? 'selected' : ''}>${c.name}</option>`;
        });

        let brandOptions = '<option value="">Seleccione una marca...</option>';
        brands.forEach(b => {
            brandOptions += `<option value="${b.id}" ${brandId == b.id ? 'selected' : ''}>${b.name}</option>`;
        });

        return `
        <div class="section-block bg-gray-50 border border-gray-200 rounded-lg p-5 relative group" id="section-${index}">
            <div class="absolute top-4 right-4 flex gap-2">
                <button type="button" onclick="moveUp(this)" class="text-gray-400 hover:text-blue-600" title="Mover arriba">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </button>
                <button type="button" onclick="moveDown(this)" class="text-gray-400 hover:text-blue-600" title="Mover abajo">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <button type="button" onclick="removeSection(${index})" class="text-gray-400 hover:text-red-600 ml-2" title="Eliminar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mr-16">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Sección</label>
                    <select name="sections[${index}][type]" onchange="toggleExtraFields(this, ${index})" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="latest" ${type === 'latest' ? 'selected' : ''}>Novedades (Últimos)</option>
                        <option value="featured" ${type === 'featured' ? 'selected' : ''}>Destacados</option>
                        <option value="promotions" ${type === 'promotions' ? 'selected' : ''}>Promociones (Ofertas)</option>
                        <option value="category" ${type === 'category' ? 'selected' : ''}>Por Categoría</option>
                        <option value="brand" ${type === 'brand' ? 'selected' : ''}>Por Marca</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Límite de Productos</label>
                    <input type="number" name="sections[${index}][limit]" value="${limit}" min="1" max="20" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                
                <div id="cat-field-${index}" style="${type === 'category' ? '' : 'display:none;'}">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select name="sections[${index}][category_id]" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        ${catOptions}
                    </select>
                </div>
                
                <div id="brand-field-${index}" style="${type === 'brand' ? '' : 'display:none;'}">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                    <select name="sections[${index}][brand_id]" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        ${brandOptions}
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título de la Sección</label>
                    <input type="text" name="sections[${index}][title]" value="${title}" placeholder="Ej: Top en Ventas" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo (Opcional)</label>
                    <input type="text" name="sections[${index}][subtitle]" value="${subtitle}" placeholder="Ej: Los más buscados de esta semana" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>
        </div>
        `;
    }

    function toggleExtraFields(selectEl, index) {
        const type = selectEl.value;
        document.getElementById(`cat-field-${index}`).style.display = (type === 'category') ? 'block' : 'none';
        document.getElementById(`brand-field-${index}`).style.display = (type === 'brand') ? 'block' : 'none';
    }

    function addSection(data = {}) {
        emptyState.classList.add('hidden');
        container.insertAdjacentHTML('beforeend', createSectionHTML(sectionCount, data));
        sectionCount++;
    }

    function removeSection(index) {
        if(confirm('¿Seguro que deseas eliminar esta sección?')) {
            document.getElementById(`section-${index}`).remove();
            if(container.children.length === 0) {
                emptyState.classList.remove('hidden');
            }
        }
    }

    function moveUp(btn) {
        const block = btn.closest('.section-block');
        if (block.previousElementSibling) {
            block.parentNode.insertBefore(block, block.previousElementSibling);
        }
    }

    function moveDown(btn) {
        const block = btn.closest('.section-block');
        if (block.nextElementSibling) {
            block.parentNode.insertBefore(block.nextElementSibling, block);
        }
    }

    // Initialize with existing data
    if (initialSections && initialSections.length > 0) {
        initialSections.forEach(sec => addSection(sec));
    }
    
    // --- HERO SLIDES LOGIC ---
    let heroSlideIndex = 0;
    const heroSlidesContainer = document.getElementById('hero-slides-container');
    const heroSlideTemplate = document.getElementById('hero-slide-template');
    const initialHeroSlides = <?= json_encode($heroSlides ?? []) ?>;

    function addHeroSlide(data = {}) {
        const clone = heroSlideTemplate.content.cloneNode(true);
        const item = clone.querySelector('.hero-slide-item');
        const index = heroSlideIndex++;

        // Update name indices
        clone.querySelectorAll('[name*="{index}"]').forEach(el => {
            el.name = el.name.replace('{index}', index);
        });

        // Set values if data exists
        if (data.title) clone.querySelector('.hero-title-input').value = data.title;
        if (data.subtitle) clone.querySelector('.hero-subtitle-input').value = data.subtitle;
        if (data.btn_text) clone.querySelector('.hero-btn-text-input').value = data.btn_text;
        if (data.btn_link) clone.querySelector('.hero-btn-link-input').value = data.btn_link;
        if (data.layout) clone.querySelector('.hero-layout-input').value = data.layout;
        
        if (data.image_path) {
            clone.querySelector('.hero-existing-image-input').value = data.image_path;
            const imgPreview = clone.querySelector('.hero-image-preview');
            imgPreview.src = data.image_path;
            clone.querySelector('.hero-image-preview-container').classList.remove('hidden');
        }

        heroSlidesContainer.appendChild(clone);
        initializeDragAndDropHero();
    }

    function removeHeroImage(btn) {
        if (confirm('¿En Realidad desea eliminar la imagen?')) {
            const container = btn.closest('.flex');
            container.querySelector('.hero-existing-image-input').value = '';
            container.querySelector('.hero-image-preview').src = '';
            container.querySelector('.hero-image-preview-container').classList.add('hidden');
            container.querySelector('.hero-file-input').value = '';
        }
    }

    function previewHeroImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = input.closest('.flex');
                container.querySelector('.hero-image-preview').src = e.target.result;
                container.querySelector('.hero-image-preview-container').classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    if (initialHeroSlides && initialHeroSlides.length > 0) {
        initialHeroSlides.forEach(slide => addHeroSlide(slide));
    }

    // Basic drag and drop for hero slides
    function initializeDragAndDropHero() {
        let draggedHero = null;
        const heroItems = heroSlidesContainer.querySelectorAll('.hero-slide-item');
        
        heroItems.forEach(item => {
            item.setAttribute('draggable', true);
            
            item.addEventListener('dragstart', function(e) {
                if(!e.target.closest('.hero-drag-handle')) {
                    e.preventDefault();
                    return;
                }
                draggedHero = this;
                this.classList.add('opacity-50');
            });
            
            item.addEventListener('dragover', function(e) {
                e.preventDefault();
            });
            
            item.addEventListener('drop', function(e) {
                e.preventDefault();
                if (this !== draggedHero) {
                    let all = Array.from(heroSlidesContainer.querySelectorAll('.hero-slide-item'));
                    let draggedIdx = all.indexOf(draggedHero);
                    let targetIdx = all.indexOf(this);
                    if (draggedIdx < targetIdx) {
                        this.parentNode.insertBefore(draggedHero, this.nextSibling);
                    } else {
                        this.parentNode.insertBefore(draggedHero, this);
                    }
                }
            });
            
            item.addEventListener('dragend', function() {
                this.classList.remove('opacity-50');
                draggedHero = null;
            });
        });
    }
    
    // Add AlpineJS component for Color Picker
    document.addEventListener('alpine:init', () => {
        Alpine.data('colorPicker', (initialCss) => {
            // Helper to parse initial CSS
            let type = 'solid';
            let solidColor = '#ef4444';
            let gradType = 'linear';
            let gradAngle = 90;
            let color1 = '#ef4444';
            let color2 = '#991b1b';
            
            if (initialCss.includes('gradient')) {
                type = 'gradient';
                if (initialCss.includes('radial-gradient')) gradType = 'radial';
                
                // Extremely basic parser for existing simple gradients
                const hexMatches = initialCss.match(/#[0-9a-fA-F]{3,6}/g);
                if (hexMatches && hexMatches.length >= 2) {
                    color1 = hexMatches[0];
                    color2 = hexMatches[1];
                }
            } else if (initialCss.startsWith('#')) {
                solidColor = initialCss;
            }
            
            return {
                type: type,
                solidColor: solidColor,
                gradType: gradType,
                gradAngle: gradAngle,
                color1: color1,
                color2: color2,
                outputCss: initialCss,
                
                updateCss() {
                    if (this.type === 'solid') {
                        this.outputCss = this.solidColor;
                    } else {
                        if (this.gradType === 'linear') {
                            this.outputCss = `linear-gradient(${this.gradAngle}deg, ${this.color1}, ${this.color2})`;
                        } else {
                            this.outputCss = `radial-gradient(circle, ${this.color1}, ${this.color2})`;
                        }
                    }
                }
            }
        });
    });
</script>
