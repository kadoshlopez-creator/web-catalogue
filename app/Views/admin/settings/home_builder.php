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

<form id="home-builder-form" action="/admin/settings/home" method="POST" enctype="multipart/form-data" novalidate>

    <!-- Asistente de Creación de Páginas (Modal) -->
    <div x-data="{
        open: false,
        step: 1,
        selectedCategory: '',
        selectedTemplate: '',
        pageTitle: '',
        pageSlug: '',
        pageMetaDesc: '',
        addToMenu: false,
        menuTarget: 'main',
        menuPosition: 'after',
        menuReferenceItem: '',
        menuItemsList: [],
        
        init() {
            this.$watch('pageTitle', (value) => {
                // Auto-generate slug
                if(value) {
                    this.pageSlug = '/' + value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
                } else {
                    this.pageSlug = '';
                }
            });
            window.addEventListener('open-assistant', () => {
                this.open = true;
                this.step = 1;
                this.selectedCategory = '';
                this.selectedTemplate = '';
                this.pageTitle = '';
                this.pageSlug = '';
                this.pageMetaDesc = '';
                this.addToMenu = false;
            });
            this.$watch('addToMenu', (value) => {
                if(value) {
                    let menuDataRaw = document.getElementById('menu-data-main');
                    if(menuDataRaw && menuDataRaw.value) {
                        try {
                            this.menuItemsList = JSON.parse(menuDataRaw.value);
                            if(this.menuItemsList.length > 0) {
                                this.menuReferenceItem = this.menuItemsList[0].label;
                            }
                        } catch(e) {}
                    }
                }
            });
        },
        closeAssistant() {
            this.open = false;
        },
        getTemplatesForCategory() {
            if(this.selectedCategory === 'contacto') {
                return [
                    {id: 'contact_1', name: 'Clásica', desc: 'Formulario simple y directo'},
                    {id: 'contact_2', name: 'Corporativa', desc: 'Con información de contacto destacada'},
                    {id: 'contact_3', name: 'Ubicación', desc: 'Enfocada en mapa y direcciones'}
                ];
            } else if (this.selectedCategory === 'nosotros') {
                return [
                    {id: 'about_1', name: 'Historia', desc: 'Centrada en la trayectoria'},
                    {id: 'about_2', name: 'Equipo', desc: 'Destacando a los profesionales'},
                    {id: 'about_3', name: 'Misión y Visión', desc: 'Enfoque corporativo'}
                ];
            } else if (this.selectedCategory === 'servicios') {
                return [
                    {id: 'services_1', name: 'Tarjetas', desc: 'Servicios en cuadrícula'},
                    {id: 'services_2', name: 'Lista Detallada', desc: 'Con precios y características'},
                    {id: 'services_3', name: 'Hero + Features', desc: 'Impacto visual fuerte'}
                ];
            } else {
                return [
                    {id: 'blank_1', name: 'En Blanco', desc: 'Empieza desde cero'}
                ];
            }
        },
        getCategoryName() {
            const map = {
                'contacto': 'Contacto',
                'nosotros': 'Sobre Nosotros',
                'servicios': 'Servicios',
                'vacia': 'Página Vacía'
            };
            return map[this.selectedCategory] || '';
        },
        prepareDetails() {
            if(this.selectedCategory !== 'vacia') {
                this.pageTitle = this.getCategoryName();
            }
            this.step = 3;
        },
        generatePage() {
            const htmlContent = getTemplateHTML(this.selectedTemplate);
            const cleanSlug = this.pageSlug.replace(/^\/+/, '');
            
            // Generate standard item using the custom page system
            addCustomPage({
                title: this.pageTitle,
                slug: cleanSlug,
                meta_description: this.pageMetaDesc,
                content: htmlContent
            });
            
            // Siempre agregar la nueva pgina al footer automticamente
            this.$dispatch('add-to-menu', {
                menu: 'footer',
                label: this.pageTitle,
                link: '/p/' + cleanSlug,
                position: 'after',
                reference: null
            });
            
            if (this.addToMenu) {
                this.$dispatch('add-to-menu', {
                    menu: this.menuTarget,
                    label: this.pageTitle,
                    link: '/p/' + cleanSlug,
                    position: this.menuPosition,
                    reference: this.menuReferenceItem
                });
            }
        }
    }">
        
        <!-- Backdrop -->
        <div x-show="open" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-[100] transition-opacity backdrop-blur-sm" style="display: none;"></div>
        
        <!-- Modal -->
        <div x-show="open" class="fixed inset-0 z-[101] flex items-center justify-center p-4 sm:p-6" style="display: none;">
            <div @click.away="closeAssistant()" class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
                
                <!-- Header -->
                <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Asistente Inteligente de Páginas
                    </h3>
                    <button @click="closeAssistant()" class="text-indigo-200 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Progress Bar -->
                <div class="bg-indigo-50 px-6 py-3 border-b border-indigo-100 flex items-center gap-4">
                    <div class="flex items-center gap-2" :class="step >= 1 ? 'text-indigo-700' : 'text-gray-400'">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold" :class="step >= 1 ? 'bg-indigo-600 text-white' : 'bg-gray-300 text-white'">1</div>
                        <span class="text-sm font-medium">Categoría</span>
                    </div>
                    <div class="w-8 h-[2px]" :class="step >= 2 ? 'bg-indigo-600' : 'bg-gray-300'"></div>
                    <div class="flex items-center gap-2" :class="step >= 2 ? 'text-indigo-700' : 'text-gray-400'">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold" :class="step >= 2 ? 'bg-indigo-600 text-white' : 'bg-gray-300 text-white'">2</div>
                        <span class="text-sm font-medium">Plantilla</span>
                    </div>
                    <div class="w-8 h-[2px]" :class="step >= 3 ? 'bg-indigo-600' : 'bg-gray-300'"></div>
                    <div class="flex items-center gap-2" :class="step >= 3 ? 'text-indigo-700' : 'text-gray-400'">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold" :class="step >= 3 ? 'bg-indigo-600 text-white' : 'bg-gray-300 text-white'">3</div>
                        <span class="text-sm font-medium">Detalles</span>
                    </div>
                </div>
                
                <!-- Body -->
                <div class="p-6 overflow-y-auto flex-grow bg-gray-50/50">
                    
                    <!-- Step 1: Select Category -->
                    <div x-show="step === 1">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">¿Qué tipo de página deseas crear?</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- Contacto -->
                            <div @click="selectedCategory = 'contacto'" class="border rounded-xl p-4 cursor-pointer hover:border-indigo-500 transition-all text-center group bg-white" :class="{'border-indigo-500 ring-2 ring-indigo-500 bg-indigo-50': selectedCategory === 'contacto', 'border-gray-200': selectedCategory !== 'contacto'}">
                                <div class="w-12 h-12 rounded-full mx-auto flex items-center justify-center mb-3" :class="{'bg-indigo-200 text-indigo-700': selectedCategory === 'contacto', 'bg-gray-100 text-gray-500 group-hover:bg-indigo-100 group-hover:text-indigo-600': selectedCategory !== 'contacto'}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <h5 class="font-bold text-gray-900">Contacto</h5>
                            </div>
                            
                            <!-- Nosotros -->
                            <div @click="selectedCategory = 'nosotros'" class="border rounded-xl p-4 cursor-pointer hover:border-indigo-500 transition-all text-center group bg-white" :class="{'border-indigo-500 ring-2 ring-indigo-500 bg-indigo-50': selectedCategory === 'nosotros', 'border-gray-200': selectedCategory !== 'nosotros'}">
                                <div class="w-12 h-12 rounded-full mx-auto flex items-center justify-center mb-3" :class="{'bg-indigo-200 text-indigo-700': selectedCategory === 'nosotros', 'bg-gray-100 text-gray-500 group-hover:bg-indigo-100 group-hover:text-indigo-600': selectedCategory !== 'nosotros'}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <h5 class="font-bold text-gray-900">Sobre Nosotros</h5>
                            </div>
                            
                            <!-- Servicios -->
                            <div @click="selectedCategory = 'servicios'" class="border rounded-xl p-4 cursor-pointer hover:border-indigo-500 transition-all text-center group bg-white" :class="{'border-indigo-500 ring-2 ring-indigo-500 bg-indigo-50': selectedCategory === 'servicios', 'border-gray-200': selectedCategory !== 'servicios'}">
                                <div class="w-12 h-12 rounded-full mx-auto flex items-center justify-center mb-3" :class="{'bg-indigo-200 text-indigo-700': selectedCategory === 'servicios', 'bg-gray-100 text-gray-500 group-hover:bg-indigo-100 group-hover:text-indigo-600': selectedCategory !== 'servicios'}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <h5 class="font-bold text-gray-900">Servicios</h5>
                            </div>
                            
                            <!-- Vacia -->
                            <div @click="selectedCategory = 'vacia'" class="border rounded-xl p-4 cursor-pointer hover:border-indigo-500 transition-all text-center group bg-white" :class="{'border-indigo-500 ring-2 ring-indigo-500 bg-indigo-50': selectedCategory === 'vacia', 'border-gray-200': selectedCategory !== 'vacia'}">
                                <div class="w-12 h-12 rounded-full mx-auto flex items-center justify-center mb-3" :class="{'bg-indigo-200 text-indigo-700': selectedCategory === 'vacia', 'bg-gray-100 text-gray-500 group-hover:bg-indigo-100 group-hover:text-indigo-600': selectedCategory !== 'vacia'}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h5 class="font-bold text-gray-900">En Blanco</h5>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Select Template -->
                    <div x-show="step === 2" style="display: none;">
                        <h4 class="text-lg font-medium text-gray-900 mb-1">Elige un diseño inicial</h4>
                        <p class="text-sm text-gray-500 mb-4">Plantillas optimizadas para <span class="font-bold text-indigo-600" x-text="getCategoryName()"></span></p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <template x-for="tpl in getTemplatesForCategory()" :key="tpl.id">
                                <div @click="selectedTemplate = tpl.id" class="border rounded-lg p-3 cursor-pointer hover:border-indigo-500 transition-all" :class="{'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500': selectedTemplate === tpl.id, 'border-gray-200': selectedTemplate !== tpl.id}">
                                    <div class="h-32 bg-gray-50 rounded mb-3 flex items-center justify-center border border-gray-100">
                                        <!-- Simulacion visual de wireframe -->
                                        <div class="w-3/4 space-y-2 opacity-50">
                                            <div class="h-2 bg-indigo-300 rounded w-1/2 mx-auto mb-4"></div>
                                            <div class="h-1 bg-gray-300 rounded w-full"></div>
                                            <div class="h-1 bg-gray-300 rounded w-5/6 mx-auto"></div>
                                            <div class="h-1 bg-gray-300 rounded w-4/6 mx-auto"></div>
                                        </div>
                                    </div>
                                    <h5 class="font-bold text-sm text-gray-900" x-text="tpl.name"></h5>
                                    <p class="text-xs text-gray-500 mt-1" x-text="tpl.desc"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Step 3: Details -->
                    <div x-show="step === 3" style="display: none;">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Detalles finales</h4>
                        
                        <div class="space-y-4 max-w-2xl">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Página *</label>
                                <input type="text" x-model="pageTitle" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Enlace / Slug *</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        /p
                                    </span>
                                    <input type="text" x-model="pageSlug" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Este enlace se genera automáticamente basado en el título.</p>
                            </div>
                            
                            <div class="mt-4 p-4 border border-gray-200 rounded-lg bg-white">
                                <div class="flex items-center gap-2 mb-3">
                                    <input type="checkbox" id="add_to_menu_check" x-model="addToMenu" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="add_to_menu_check" class="text-sm font-medium text-gray-700">Agregar esta página al menú principal</label>
                                </div>
                                <div x-show="addToMenu" class="space-y-3 pl-6 mt-2 border-l-2 border-indigo-100" style="display: none;">
                                    <div x-show="menuItemsList.length > 0">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">¿Dónde quieres agregarla?</label>
                                        <div class="flex gap-2">
                                            <select x-model="menuPosition" class="rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm w-1/3">
                                                <option value="after">Después de...</option>
                                                <option value="before">Antes de...</option>
                                            </select>
                                            <select x-model="menuReferenceItem" class="rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm flex-1">
                                                <template x-for="item in menuItemsList">
                                                    <option :value="item.label" x-text="item.label"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">Selecciona la posición en el menú actual.</p>
                                    </div>
                                    <div x-show="menuItemsList.length === 0" class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-200 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        El menú principal está actualmente vacío. Esta página se añadirá como el primer elemento del menú.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Buttons -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <button type="button" @click="closeAssistant()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">
                        Cancelar
                    </button>
                    
                    <div class="flex gap-2">
                        <button type="button" x-show="step > 1" @click="step--" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors" style="display: none;">
                            Anterior
                        </button>
                        
                        <button type="button" x-show="step === 1" @click="step = 2" :disabled="!selectedCategory" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Siguiente
                        </button>
                        
                        <button type="button" x-show="step === 2" @click="prepareDetails()" :disabled="!selectedTemplate" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed" style="display: none;">
                            Siguiente
                        </button>
                        
                        <button type="button" x-show="step === 3" @click="generatePage(); closeAssistant();" :disabled="!pageTitle" class="px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2" style="display: none;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Generar Página
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
    
    

    <div x-data="{ activeTab: 'menu' }" class="w-full">

        <!-- Menú de Thumbnails -->
        <div x-show="activeTab === 'menu'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div @click="activeTab = 'logo'" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md cursor-pointer transition-all flex flex-col items-center text-center gap-4 hover:border-blue-300 group">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                <div><h3 class="text-lg font-bold text-gray-900">Logo y Favicon</h3><p class="text-sm text-gray-500 mt-1">Configura la identidad visual de tu tienda.</p></div>
            </div>
            <div @click="activeTab = 'seo'" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md cursor-pointer transition-all flex flex-col items-center text-center gap-4 hover:border-blue-300 group">
                <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg></div>
                <div><h3 class="text-lg font-bold text-gray-900">Páginas y SEO</h3><p class="text-sm text-gray-500 mt-1">Optimización, metaetiquetas y páginas personalizadas.</p></div>
            </div>
            <div @click="activeTab = 'ribbon'" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md cursor-pointer transition-all flex flex-col items-center text-center gap-4 hover:border-blue-300 group">
                <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg></div>
                <div><h3 class="text-lg font-bold text-gray-900">Barra de Anuncios</h3><p class="text-sm text-gray-500 mt-1">Cinta superior para promociones o envíos.</p></div>
            </div>
            <div @click="activeTab = 'hero'" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md cursor-pointer transition-all flex flex-col items-center text-center gap-4 hover:border-blue-300 group">
                <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                <div><h3 class="text-lg font-bold text-gray-900">Carrusel Principal</h3><p class="text-sm text-gray-500 mt-1">Banners principales (Hero) de la tienda.</p></div>
            </div>
            <div @click="activeTab = 'products'" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md cursor-pointer transition-all flex flex-col items-center text-center gap-4 hover:border-blue-300 group">
                <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></div>
                <div><h3 class="text-lg font-bold text-gray-900">Bloques de Productos</h3><p class="text-sm text-gray-500 mt-1">Destacados, novedades y colecciones.</p></div>
            </div>
            <div @click="activeTab = 'footer'" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md cursor-pointer transition-all flex flex-col items-center text-center gap-4 hover:border-blue-300 group">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-600 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg></div>
                <div><h3 class="text-lg font-bold text-gray-900">Pie de Página</h3><p class="text-sm text-gray-500 mt-1">Configura el menú y datos del footer.</p></div>
            </div>
        </div>

        <!-- Botón Volver al Menú -->
        <div x-show="activeTab !== 'menu'" class="mb-4" style="display: none;">
            <button type="button" @click="activeTab = 'menu'" class="text-gray-500 hover:text-gray-900 flex items-center gap-2 text-sm font-medium transition-colors bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Menú de Secciones
            </button>
        </div>
        
        <!-- Secciones -->
<div x-show="activeTab === 'logo'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
<!-- Configuración del Logo -->
    
</div>
<div x-show="activeTab === 'seo'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
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
    
    
    <!-- Creador de Páginas Personalizadas -->
    <div class="p-6 border-t border-gray-200 bg-gray-50">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Páginas Personalizadas
            </h3>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-assistant'))" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Añadir Página
            </button>
        </div>
        <p class="text-xs text-gray-500 mb-4">Crea páginas adicionales como "Quiénes Somos" o "Contacto" que luego podrás agregar al menú principal.</p>
        
        <div id="custom-pages-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Pages will be rendered here by JS -->
        </div>

        <!-- ═══ MENÚ ACTIVO ═══ -->
        <div class="mt-8 border-t border-gray-200 pt-6"
             x-data="activeMenuManager()"
             x-init="init()">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h7"/>
                    </svg>
                    Menú de Navegación Activo
                </h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium"
                      x-text="items.filter(i => !i.disabled).length + ' enlace(s) visible(s)'"></span>
            </div>
            <p class="text-xs text-gray-500 mb-5">
                Estos son los enlaces del menú principal de tu sitio. Puedes
                <strong>desactivarlos</strong> temporalmente (se ocultan sin borrarse) o
                <strong>eliminarlos</strong> definitivamente.
                Los cambios se sincronizan con el editor de menú al guardar.
            </p>

            <div class="space-y-2">
                <template x-if="items.length === 0">
                    <div class="text-center py-8 text-gray-400 border border-dashed border-gray-200 rounded-xl bg-gray-50">
                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <p class="text-sm">No hay ítems en el menú principal.</p>
                    </div>
                </template>

                <template x-for="(item, index) in items" :key="index">
                    <div class="flex items-center gap-3 p-3 rounded-xl border transition-all"
                         :class="item.disabled ? 'bg-gray-50 border-gray-200 opacity-60' : 'bg-white border-gray-200 shadow-sm hover:border-blue-200 hover:shadow-md'">
                        <div class="flex-shrink-0">
                            <span class="w-2.5 h-2.5 rounded-full block"
                                  :class="item.disabled ? 'bg-gray-300' : 'bg-green-400'"></span>
                        </div>
                        <div class="flex-grow min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate" x-text="item.label"></p>
                            <p class="text-xs text-gray-400 truncate" x-text="item.link"></p>
                        </div>
                        <span class="flex-shrink-0 text-xs px-2 py-0.5 rounded-full font-medium"
                              :class="item.disabled ? 'bg-gray-100 text-gray-500' : 'bg-green-50 text-green-700'"
                              x-text="item.disabled ? 'Desactivado' : 'Activo'"></span>
                        <button type="button"
                                @click="toggleItem(index)"
                                class="flex-shrink-0 text-xs px-3 py-1.5 rounded-lg font-medium border transition-all"
                                :class="item.disabled ? 'border-green-300 text-green-700 hover:bg-green-50' : 'border-orange-300 text-orange-600 hover:bg-orange-50'"
                                :title="item.disabled ? 'Activar en el menú' : 'Desactivar del menú'"
                                x-text="item.disabled ? '✓ Activar' : '⊘ Desactivar'">
                        </button>
                        <button type="button"
                                @click="removeItem(index)"
                                class="flex-shrink-0 p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                                title="Eliminar permanentemente del menú">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>

            <p class="text-xs text-amber-600 mt-4 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Los cambios se aplican al presionar <strong class="ml-1">Guardar Configuración</strong>.
            </p>
        </div>

        <!-- Page Editor Modal -->

        <div x-data="pageEditor()" @open-page-editor.window="openEditor($event.detail)" x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" style="display: none;">
            <div @click.away="closeEditor()" class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col mx-4">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Editor de Página
                    </h3>
                    <button type="button" @click="closeEditor()" class="text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto flex-1 bg-white">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Título de la Página</label>
                                <input type="text" x-model="title" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">URL / Slug</label>
                                <input type="text" x-model="slug" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Meta Descripción</label>
                                <textarea x-model="metaDesc" rows="3" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <div class="lg:col-span-2 flex flex-col h-full min-h-[500px]">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">Contenido Visual (Editable Directamente)</label>
                                <div class="flex bg-gray-100 rounded-lg p-0.5 border border-gray-200">
                                    <button type="button" @click="setMode('visual')" :class="mode === 'visual' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1 text-xs font-bold rounded-md transition-all">Editor Visual</button>
                                    <button type="button" @click="setMode('html')" :class="mode === 'html' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1 text-xs font-bold rounded-md transition-all">Código HTML</button>
                                </div>
                            </div>
                            <div class="flex-1 border border-gray-200 rounded-lg overflow-hidden bg-white shadow-inner flex flex-col">
                                <div x-show="mode === 'html'" class="flex-1 w-full h-full">
                                    <textarea x-model="content" class="w-full h-full border-0 p-4 text-xs font-mono focus:ring-0 bg-gray-50 resize-none"></textarea>
                                </div>
                                <div x-show="mode === 'visual'" class="flex-1 w-full h-full" wire:ignore>
                                    <textarea id="tinymce-editor" x-model="content" class="w-full h-full border-0"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                    <button type="button" @click="closeEditor()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm">Cancelar</button>
                    <button type="button" @click="saveEditor()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Aplicar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
<div x-show="activeTab === 'ribbon'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
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

    
</div>
<div x-show="activeTab === 'hero'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
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

    
</div>
<div x-show="activeTab === 'products'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
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
    
    
</div>
<div x-show="activeTab === 'footer'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">

        <div class="p-6 border-b border-gray-200 bg-white flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                Información del Pie de Página
            </h3>
        </div>
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sobre Nosotros (Footer)</label>
                    <textarea name="footer[about_text]" rows="4" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Breve descripción de la tienda..."><?= htmlspecialchars($settings['footer']['about_text'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Información de Contacto</label>
                    <div class="space-y-3">
                        <input type="text" name="footer[contact_email]" value="<?= htmlspecialchars($settings['footer']['contact_email'] ?? '') ?>" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm" placeholder="Email de contacto">
                        <input type="text" name="footer[contact_phone]" value="<?= htmlspecialchars($settings['footer']['contact_phone'] ?? '') ?>" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm" placeholder="Teléfono">
                        <input type="text" name="footer[contact_address]" value="<?= htmlspecialchars($settings['footer']['contact_address'] ?? '') ?>" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm" placeholder="Dirección física">
                    </div>
                </div>
            </div>
            
            <!-- Redes Sociales -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Redes Sociales</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Facebook</label>
                        <input type="url" name="footer[social_facebook]" value="<?= htmlspecialchars($settings['footer']['social_facebook'] ?? '') ?>" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm" placeholder="URL del perfil">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Instagram</label>
                        <input type="url" name="footer[social_instagram]" value="<?= htmlspecialchars($settings['footer']['social_instagram'] ?? '') ?>" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm" placeholder="URL del perfil">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Twitter/X</label>
                        <input type="url" name="footer[social_twitter]" value="<?= htmlspecialchars($settings['footer']['social_twitter'] ?? '') ?>" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm" placeholder="URL del perfil">
                    </div>
                </div>
            </div>

            <!-- Menú Principal -->
            <input type="hidden" id="menu-data-main" value="<?= htmlspecialchars(json_encode(array_values($navigationMenus['main']['items'] ?? []))) ?>">
            <div class="border-t border-gray-200 pt-6" x-data="menuEditor('main', <?= htmlspecialchars(json_encode(array_values($navigationMenus['main']['items'] ?? []))) ?>)" @add-to-menu.window="handleAddToMenu($event)" @active-menu-sync.window="items = $event.detail.items">
                <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    Menú Principal
                </h4>
                <input type="hidden" name="navigation_menus[main][items]" :value="JSON.stringify(items)">
                <div class="space-y-3 mb-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="flex-grow grid grid-cols-2 gap-3">
                                <input type="text" x-model="item.label" class="w-full rounded-lg border-gray-300 border px-3 py-1.5 text-sm" placeholder="Etiqueta">
                                <input type="text" x-model="item.link" class="w-full rounded-lg border-gray-300 border px-3 py-1.5 text-sm" placeholder="Enlace">
                            </div>
                            <button type="button" @click="removeItem(index)" class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="addItem()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">+ Añadir Enlace</button>
            </div>

            <!-- Menú Footer -->
            <input type="hidden" id="menu-data-footer" value="<?= htmlspecialchars(json_encode(array_values($navigationMenus['footer']['items'] ?? []))) ?>">
            <div class="border-t border-gray-200 pt-6" x-data="menuEditor('footer', <?= htmlspecialchars(json_encode(array_values($navigationMenus['footer']['items'] ?? []))) ?>)" @add-to-menu.window="handleAddToMenu($event)">
                <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    Menú Pie de Página (Legales, Soporte)
                </h4>
                <input type="hidden" name="navigation_menus[footer][items]" :value="JSON.stringify(items)">
                <div class="space-y-3 mb-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="flex-grow grid grid-cols-2 gap-3">
                                <input type="text" x-model="item.label" class="w-full rounded-lg border-gray-300 border px-3 py-1.5 text-sm" placeholder="Etiqueta">
                                <input type="text" x-model="item.link" class="w-full rounded-lg border-gray-300 border px-3 py-1.5 text-sm" placeholder="Enlace">
                            </div>
                            <button type="button" @click="removeItem(index)" class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="addItem()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">+ Añadir Enlace</button>
            </div>
        </div>

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


    // ---- TEMPLATE HTML: returns starter HTML for each template type ----
    function getTemplateHTML(templateId) {
        const templates = {
            // CONTACTO
            contact_1: `<section class="py-16 px-4 max-w-2xl mx-auto"><h1 class="text-3xl font-bold text-gray-900 mb-4">Contáctanos</h1><p class="text-gray-600 mb-8">Completa el formulario y te responderemos a la brevedad.</p><form class="space-y-4"><div><label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label><input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Tu nombre"></div><div><label class="block text-sm font-medium text-gray-700 mb-1">Email</label><input type="email" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="tu@email.com"></div><div><label class="block text-sm font-medium text-gray-700 mb-1">Mensaje</label><textarea rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Escribe tu mensaje..."></textarea></div><button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors">Enviar Mensaje</button></form></section>`,
            contact_2: `<section class="py-16 px-4"><div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12"><div><h1 class="text-3xl font-bold text-gray-900 mb-4">Información de Contacto</h1><div class="space-y-4 text-gray-600"><p>📍 Dirección: Tu dirección aquí</p><p>📞 Teléfono: +1 234 567 890</p><p>✉️ Email: contacto@tuempresa.com</p><p>🕒 Horario: Lun-Vie 9am - 6pm</p></div></div><div><h2 class="text-2xl font-bold text-gray-900 mb-4">Escríbenos</h2><form class="space-y-4"><input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Nombre"><input type="email" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Email"><textarea rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Mensaje"></textarea><button class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium">Enviar</button></form></div></div></section>`,
            contact_3: `<section class="py-16 px-4 max-w-3xl mx-auto text-center"><h1 class="text-3xl font-bold text-gray-900 mb-4">¿Cómo llegar?</h1><p class="text-gray-600 mb-8">Encuéntranos fácilmente en nuestra ubicación.</p><div class="bg-gray-200 rounded-2xl h-64 flex items-center justify-center mb-8"><p class="text-gray-500">Mapa aquí</p></div><div class="grid md:grid-cols-2 gap-4 text-left"><div class="bg-gray-50 p-4 rounded-xl"><strong>Dirección</strong><p class="text-gray-600 mt-1">Tu dirección completa aquí</p></div><div class="bg-gray-50 p-4 rounded-xl"><strong>Horarios</strong><p class="text-gray-600 mt-1">Lun-Vie: 9am - 6pm<br>Sáb: 10am - 2pm</p></div></div></section>`,
            // NOSOTROS
            about_1: `<section class="py-16 px-4 max-w-4xl mx-auto"><h1 class="text-3xl font-bold text-gray-900 mb-4">Nuestra Historia</h1><p class="text-gray-600 text-lg mb-8">Desde nuestros inicios, hemos trabajado con pasión para ofrecer productos y servicios de la más alta calidad.</p><div class="space-y-6 text-gray-600"><p>Párrafo sobre los inicios de la empresa...</p><p>Párrafo sobre el crecimiento y evolución...</p><p>Párrafo sobre la actualidad y futuro...</p></div></section>`,
            about_2: `<section class="py-16 px-4 max-w-4xl mx-auto"><h1 class="text-3xl font-bold text-gray-900 mb-4">Nuestro Equipo</h1><p class="text-gray-600 mb-10">Conoce a las personas que hacen posible todo lo que ofrecemos.</p><div class="grid md:grid-cols-3 gap-8"><div class="text-center"><div class="w-24 h-24 bg-indigo-100 rounded-full mx-auto mb-4 flex items-center justify-center text-indigo-600 text-2xl font-bold">A</div><h3 class="font-bold text-gray-900">Ana García</h3><p class="text-sm text-gray-500">Directora General</p></div><div class="text-center"><div class="w-24 h-24 bg-indigo-100 rounded-full mx-auto mb-4 flex items-center justify-center text-indigo-600 text-2xl font-bold">C</div><h3 class="font-bold text-gray-900">Carlos López</h3><p class="text-sm text-gray-500">Director de Operaciones</p></div><div class="text-center"><div class="w-24 h-24 bg-indigo-100 rounded-full mx-auto mb-4 flex items-center justify-center text-indigo-600 text-2xl font-bold">M</div><h3 class="font-bold text-gray-900">María Torres</h3><p class="text-sm text-gray-500">Directora Creativa</p></div></div></section>`,
            about_3: `<section class="py-16 px-4 max-w-4xl mx-auto"><h1 class="text-3xl font-bold text-gray-900 mb-10 text-center">Sobre Nosotros</h1><div class="grid md:grid-cols-2 gap-8"><div class="bg-indigo-50 p-8 rounded-2xl"><h2 class="text-xl font-bold text-indigo-900 mb-3">Misión</h2><p class="text-indigo-800">Proporcionar productos y servicios excepcionales que mejoren la vida de nuestros clientes, con compromiso, innovación y excelencia.</p></div><div class="bg-gray-50 p-8 rounded-2xl"><h2 class="text-xl font-bold text-gray-900 mb-3">Visión</h2><p class="text-gray-700">Ser la empresa referente en nuestra industria, reconocida por la calidad, confianza y el impacto positivo en nuestra comunidad.</p></div><div class="bg-green-50 p-8 rounded-2xl"><h2 class="text-xl font-bold text-green-900 mb-3">Valores</h2><ul class="text-green-800 space-y-1"><li>✓ Integridad</li><li>✓ Innovación</li><li>✓ Compromiso</li><li>✓ Excelencia</li></ul></div><div class="bg-orange-50 p-8 rounded-2xl"><h2 class="text-xl font-bold text-orange-900 mb-3">Experiencia</h2><p class="text-orange-800">Más de 10 años de trayectoria avalan nuestra experiencia y la confianza de cientos de clientes satisfechos.</p></div></div></section>`,
            // SERVICIOS
            services_1: `<section class="py-16 px-4 max-w-5xl mx-auto"><h1 class="text-3xl font-bold text-gray-900 mb-4 text-center">Nuestros Servicios</h1><p class="text-gray-600 text-center mb-10">Soluciones diseñadas para satisfacer tus necesidades.</p><div class="grid md:grid-cols-3 gap-6"><div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow"><div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">⭐</div><h3 class="font-bold text-gray-900 mb-2">Servicio Premium</h3><p class="text-gray-600 text-sm">Descripción del servicio premium que ofrecemos a nuestros clientes.</p></div><div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow"><div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">🚀</div><h3 class="font-bold text-gray-900 mb-2">Servicio Estándar</h3><p class="text-gray-600 text-sm">Descripción del servicio estándar disponible para todos los clientes.</p></div><div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow"><div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">💡</div><h3 class="font-bold text-gray-900 mb-2">Servicio Básico</h3><p class="text-gray-600 text-sm">Descripción del servicio básico ideal para comenzar.</p></div></div></section>`,
            services_2: `<section class="py-16 px-4 max-w-4xl mx-auto"><h1 class="text-3xl font-bold text-gray-900 mb-10">Nuestros Servicios</h1><div class="space-y-6"><div class="flex gap-6 p-6 bg-white border border-gray-200 rounded-2xl"><div class="flex-shrink-0 w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl">⭐</div><div class="flex-grow"><div class="flex justify-between items-start"><h3 class="font-bold text-gray-900 text-lg">Servicio Premium</h3><span class="text-indigo-600 font-bold">$99/mes</span></div><p class="text-gray-600 mt-1">Descripción completa del servicio premium con todas sus características.</p><ul class="text-sm text-gray-500 mt-3 space-y-1"><li>✓ Característica 1</li><li>✓ Característica 2</li><li>✓ Característica 3</li></ul></div></div><div class="flex gap-6 p-6 bg-white border border-gray-200 rounded-2xl"><div class="flex-shrink-0 w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center text-2xl">🚀</div><div class="flex-grow"><div class="flex justify-between items-start"><h3 class="font-bold text-gray-900 text-lg">Servicio Estándar</h3><span class="text-green-600 font-bold">$49/mes</span></div><p class="text-gray-600 mt-1">Descripción del servicio estándar.</p><ul class="text-sm text-gray-500 mt-3 space-y-1"><li>✓ Característica A</li><li>✓ Característica B</li></ul></div></div></div></section>`,
            services_3: `<section class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white py-20 px-4 text-center"><h1 class="text-4xl font-bold mb-4">Servicios que Transforman</h1><p class="text-indigo-100 text-lg max-w-2xl mx-auto mb-10">Ofrecemos soluciones integrales diseñadas para llevar tu negocio al siguiente nivel.</p><a href="#servicios" class="bg-white text-indigo-700 px-8 py-3 rounded-full font-bold hover:bg-indigo-50 transition-colors">Ver Servicios</a></section><section class="py-16 px-4 max-w-5xl mx-auto"><div class="grid md:grid-cols-2 gap-8"><div class="flex gap-4"><div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">💎</div><div><h3 class="font-bold text-gray-900">Calidad Garantizada</h3><p class="text-gray-600 text-sm mt-1">Todos nuestros servicios cuentan con garantía de satisfacción.</p></div></div><div class="flex gap-4"><div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">⚡</div><div><h3 class="font-bold text-gray-900">Entrega Rápida</h3><p class="text-gray-600 text-sm mt-1">Cumplimos con los tiempos acordados sin comprometer la calidad.</p></div></div></div></section>`,
            // PÁGINA EN BLANCO
            blank_1: `<section class="py-16 px-4 max-w-4xl mx-auto"><h1 class="text-3xl font-bold text-gray-900 mb-4">Título de la Página</h1><p class="text-gray-600">Comienza a editar esta página desde el editor de contenido.</p></section>`,
        };
        return templates[templateId] || templates['blank_1'];
    }

    // ---- CUSTOM PAGES: Add a new page card ----

    let customPageIndex = document.querySelectorAll('.custom-page-item').length;

    function addCustomPage(data = {}, autoScroll = true) {
        const container = document.getElementById('custom-pages-container');
        const index = customPageIndex++;
        const safeTitle = (data.title || '').replace(/"/g, '&quot;');
        const safeSlug = (data.slug || '').replace(/"/g, '&quot;');
        const safeMeta = (data.meta_description || '').replace(/"/g, '&quot;');
        const content   = data.content || '';

        const cardHtml = `
        <div class="custom-page-item bg-white border border-gray-200 hover:border-indigo-500 rounded-2xl shadow-sm hover:shadow-md transition-all cursor-pointer overflow-hidden flex flex-col h-56 group relative" id="cp-card-${index}">
            
            <div class="bg-indigo-50/50 flex-1 flex flex-col items-center justify-center cursor-pointer" onclick="window.dispatchEvent(new CustomEvent('open-page-editor', { detail: ${index} }))">
                <svg class="w-12 h-12 text-indigo-300 mb-3 group-hover:scale-110 group-hover:text-indigo-400 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"></path></svg>
                <span class="text-xs font-semibold text-indigo-700 bg-indigo-100 px-3 py-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">Editar Página</span>
            </div>
            
            <div class="p-4 border-t border-gray-100 bg-white" onclick="window.dispatchEvent(new CustomEvent('open-page-editor', { detail: ${index} }))">
                <h4 class="font-bold text-gray-800 text-sm truncate thumb-title">${safeTitle || 'Nueva Página'}</h4>
                <p class="text-xs text-gray-400 font-mono truncate mt-0.5 thumb-slug">${safeSlug || ''}</p>
            </div>
            
            <button type="button" onclick="this.closest('.custom-page-item').remove()" class="absolute top-2 right-2 bg-red-500 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity z-10 hover:bg-red-600 shadow-sm" title="Eliminar Página">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="hidden">
                <input type="hidden" name="custom_pages[${index}][title]" value="${safeTitle}">
                <input type="hidden" name="custom_pages[${index}][slug]" value="${safeSlug}">
                <input type="hidden" name="custom_pages[${index}][meta_description]" value="${safeMeta}">
                <textarea name="custom_pages[${index}][content]" class="hidden">${content.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</textarea>
            </div>
        </div>`;

        container.insertAdjacentHTML('beforeend', cardHtml);
        const newCard = document.getElementById('cp-card-' + index);

        // Scroll to card if requested
        if (autoScroll) {
            newCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            // Automatically open editor for newly added pages
            window.dispatchEvent(new CustomEvent('open-page-editor', { detail: index }));
        }
    }

    function pageEditor() {
        return {
            open: false,
            index: null,
            title: '',
            slug: '',
            metaDesc: '',
            content: '',
            mode: 'visual',
            
            initEditor() {
                if (typeof tinymce === 'undefined') {
                    const script = document.createElement('script');
                    script.src = '/assets/js/tinymce/tinymce.min.js';
                    script.onload = () => {
                        this.initTinyMCE();
                    };
                    script.onerror = () => {
                        console.error("Failed to load local TinyMCE");
                        document.getElementById('tinymce-editor').value = "Error: No se pudo cargar el Editor Visual localmente.";
                    };
                    document.head.appendChild(script);
                } else {
                    this.initTinyMCE();
                }
            },
            
            initTinyMCE() {
                tinymce.init({
                    selector: '#tinymce-editor',
                    height: '100%',
                    menubar: false,
                    plugins: 'code lists link image media table',
                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | code',
                    valid_elements: '*[*]',
                    extended_valid_elements: '*[*]',
                    verify_html: false,
                    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 14px; } svg { max-width: 48px; max-height: 48px; display: block; vertical-align: middle; }',
                    setup: (editor) => {
                        editor.on('init', () => {
                            // Load Tailwind CSS into the iframe
                            const script = editor.getDoc().createElement('script');
                            script.src = 'https://cdn.tailwindcss.com';
                            editor.getDoc().head.appendChild(script);
                            editor.setContent(this.content);
                        });
                        editor.on('input change keyup', () => {
                            this.content = editor.getContent();
                        });
                    }
                });
            },

            openEditor(idx) {
                this.index = idx;
                const card = document.getElementById('cp-card-' + idx);
                this.title = card.querySelector('input[name$="[title]"]').value;
                this.slug = card.querySelector('input[name$="[slug]"]').value;
                this.metaDesc = card.querySelector('input[name$="[meta_description]"]').value;
                this.content = card.querySelector('textarea[name$="[content]"]').value;
                this.mode = 'visual';
                this.open = true;
                this.refreshPreview();
                
                // Initialize or update TinyMCE
                setTimeout(() => {
                    if (typeof tinymce !== 'undefined' && tinymce.get('tinymce-editor')) {
                        tinymce.get('tinymce-editor').setContent(this.content);
                    } else {
                        this.initEditor();
                    }
                }, 100);
            },
            
            setMode(newMode) {
                this.mode = newMode;
                if (newMode === 'visual') {
                    if (typeof tinymce !== 'undefined' && tinymce.get('tinymce-editor')) {
                        tinymce.get('tinymce-editor').setContent(this.content);
                    }
                } else if (newMode === 'html') {
                    if (typeof tinymce !== 'undefined' && tinymce.get('tinymce-editor')) {
                        this.content = tinymce.get('tinymce-editor').getContent();
                    }
                }
            },
            
            closeEditor() {
                this.open = false;
            },
            
            saveEditor() {
                if (this.mode === 'visual' && typeof tinymce !== 'undefined' && tinymce.get('tinymce-editor')) {
                    this.content = tinymce.get('tinymce-editor').getContent();
                }
                
                const card = document.getElementById('cp-card-' + this.index);
                card.querySelector('input[name$="[title]"]').value = this.title;
                card.querySelector('input[name$="[slug]"]').value = this.slug;
                card.querySelector('input[name$="[meta_description]"]').value = this.metaDesc;
                card.querySelector('textarea[name$="[content]"]').value = this.content;
                
                card.querySelector('.thumb-title').textContent = this.title || 'Nueva Página';
                card.querySelector('.thumb-slug').textContent = this.slug;
                this.open = false;
            },
            
            refreshPreview() {
                // Ya no hay iframe de vista previa, TinyMCE se encarga directamente.
            }
        }
    }

    // Initialize existing pages on load
    document.addEventListener('DOMContentLoaded', () => {
        const existingPages = <?= json_encode(array_values($customPages ?? [])) ?>;
        existingPages.forEach(p => addCustomPage(p, false));
    });

    function menuEditor(menuId, initialItems = []) {
        return {
            items: initialItems,
            addItem() {
                this.items.push({ label: '', link: '' });
            },
            removeItem(index) {
                this.items.splice(index, 1);
            },
            handleAddToMenu(e) {
                if (e.detail.menu === menuId) {
                    const newItem = { label: e.detail.label, link: e.detail.link };
                    let insertIndex = this.items.length;
                    if (e.detail.reference) {
                        const refIdx = this.items.findIndex(i => i.label === e.detail.reference);
                        if (refIdx !== -1) {
                            insertIndex = e.detail.position === 'before' ? refIdx : refIdx + 1;
                        }
                    }
                    this.items.splice(insertIndex, 0, newItem);
                }
            }
        }
    }

    // ── activeMenuManager: gestión visual del menú principal desde "Páginas y SEO" ──
    function activeMenuManager() {
        return {
            items: [],

            init() {
                // Leer ítems iniciales desde el input oculto que ya alimenta el menuEditor
                this.loadItems();

                // Escuchar cuando el menuEditor agrega ítems (desde asistente de páginas)
                window.addEventListener('add-to-menu', () => {
                    this.$nextTick(() => this.loadItems());
                });
            },

            loadItems() {
                const raw = document.getElementById('menu-data-main');
                if (!raw || !raw.value) return;
                try {
                    const parsed = JSON.parse(raw.value);
                    // Preservar flag 'disabled' si ya existe, de lo contrario añadirlo en false
                    this.items = parsed.map(item => ({
                        ...item,
                        disabled: item.disabled ?? false
                    }));
                } catch(e) {
                    this.items = [];
                }
            },

            toggleItem(index) {
                this.items[index].disabled = !this.items[index].disabled;
                this.syncToMenuEditor();
            },

            removeItem(index) {
                this.items.splice(index, 1);
                this.syncToMenuEditor();
            },

            // Sincroniza el estado de este componente hacia el menuEditor
            // filtrando los ítems desactivados para que no se guarden en el menú
            syncToMenuEditor() {
                // Actualizar el input oculto con sólo los ítems activos
                const activeItems = this.items
                    .filter(i => !i.disabled)
                    .map(({ label, link }) => ({ label, link }));

                const raw = document.getElementById('menu-data-main');
                if (raw) raw.value = JSON.stringify(activeItems);

                // Forzar a Alpine a re-leer el valor del menuEditor principal
                // disparando un evento custom que el menuEditor de "Pie de Página" también usa
                window.dispatchEvent(new CustomEvent('active-menu-sync', {
                    detail: { items: activeItems }
                }));
            }
        };
    }

</script>



<!-- TinyMCE CDN para el Creador de Páginas -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
