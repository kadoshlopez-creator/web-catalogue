<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="/admin/products" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-semibold text-gray-800">
            SEO: <?= htmlspecialchars($entity['name']) ?>
        </h2>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="flex flex-col items-end">
            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">SEO Score</span>
            <div class="flex items-center gap-2">
                <div class="w-32 bg-gray-200 rounded-full h-2.5">
                    <div id="seoScoreBar" class="h-2.5 rounded-full <?= $seoScore > 70 ? 'bg-green-500' : ($seoScore > 40 ? 'bg-yellow-400' : 'bg-red-500') ?>" style="width: <?= $seoScore ?>%"></div>
                </div>
                <span id="seoScoreText" class="text-sm font-bold <?= $seoScore > 70 ? 'text-green-600' : ($seoScore > 40 ? 'text-yellow-600' : 'text-red-600') ?>"><?= $seoScore ?>/100</span>
            </div>
        </div>
    </div>
</div>

<?php if (\App\Core\Session::has('success')): ?>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
        <p class="text-sm text-green-700"><?= htmlspecialchars(\App\Core\Session::get('success')) ?></p>
    </div>
    <?php \App\Core\Session::remove('success'); ?>
<?php endif; ?>

<?php if (\App\Core\Session::has('error')): ?>
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
        <p class="text-sm text-red-700"><?= htmlspecialchars(\App\Core\Session::get('error')) ?></p>
    </div>
    <?php \App\Core\Session::remove('error'); ?>
<?php endif; ?>

<form action="/admin/products/<?= $entity['id'] ?>/seo" method="POST" id="seoForm">
    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
    
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Left Column: Main Inputs -->
        <div class="xl:col-span-2 space-y-6">
            
            <!-- Basico SEO Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-medium text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Información Básica SEO
                    </h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <label class="block text-sm font-medium text-gray-700">Meta Título</label>
                            <span id="titleCounter" class="text-xs text-gray-400">0/60</span>
                        </div>
                        <input type="text" id="meta_title" name="meta_title" 
                            value="<?= htmlspecialchars($entity['meta_title'] ?? '') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                            placeholder="Ej. Laptops y Computadoras Portátiles - Vitrino">
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <label class="block text-sm font-medium text-gray-700">Meta Descripción</label>
                            <span id="descCounter" class="text-xs text-gray-400">0/160</span>
                        </div>
                        <textarea id="meta_description" name="meta_description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                            placeholder="Descubre nuestra amplia selección de laptops y computadoras portátiles al mejor precio..."><?= htmlspecialchars($entity['meta_description'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Slug</label>
                        <div class="flex items-center">
                            <span class="bg-gray-100 border border-gray-300 border-r-0 rounded-l-lg px-3 py-2 text-gray-500 text-sm">
                                misitio.com/producto/
                            </span>
                            <input type="text" id="slug" name="slug" 
                                value="<?= htmlspecialchars($entity['slug'] ?? '') ?>"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Si lo cambias, generaremos automáticamente una redirección 301 desde el antiguo hacia el nuevo.</p>
                    </div>
                </div>
            </div>

            <!-- Open Graph & Twitter Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-medium text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        Redes Sociales (Open Graph / Twitter)
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Facebook / LinkedIn (OG)</h4>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">OG Title</label>
                            <input type="text" name="open_graph_title" value="<?= htmlspecialchars($entity['open_graph_title'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">OG Description</label>
                            <textarea name="open_graph_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"><?= htmlspecialchars($entity['open_graph_description'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Twitter Card</h4>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Twitter Title</label>
                            <input type="text" name="twitter_title" value="<?= htmlspecialchars($entity['twitter_title'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Twitter Description</label>
                            <textarea name="twitter_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"><?= htmlspecialchars($entity['twitter_description'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical SEO -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-medium text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        SEO Técnico & Avanzado
                    </h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Canónica</label>
                        <input type="url" name="canonical_url" value="<?= htmlspecialchars($entity['canonical_url'] ?? '') ?>" placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">JSON-LD Schema</label>
                        <textarea name="schema_json" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm font-mono text-xs" placeholder='{"@context": "https://schema.org","@type": "Product"}'><?= htmlspecialchars($entity['schema_json'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Previews & Settings -->
        <div class="space-y-6">
            
            <!-- Save Action -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col gap-3">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg shadow-sm transition-colors text-center">
                    Guardar Configuración SEO
                </button>
                <a href="/admin/products" class="w-full text-center text-gray-600 hover:text-gray-900 py-2 text-sm font-medium">
                    Cancelar
                </a>
            </div>

            <!-- SERP Preview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-medium text-gray-800 text-sm">SERP Preview (Google)</h3>
                </div>
                <div class="p-6">
                    <div class="font-sans">
                        <div class="text-[14px] text-[#202124] flex items-center gap-2 mb-1">
                            <div class="w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center text-xs font-bold text-gray-500">V</div>
                            <div>
                                <div class="text-sm leading-tight">Vitrino</div>
                                <div class="text-[12px] text-[#4d5156] leading-tight" id="serpUrl">https://misitio.com/producto/<?= htmlspecialchars($entity['slug'] ?? 'mi-producto') ?></div>
                            </div>
                        </div>
                        <div class="text-[20px] text-[#1a0dab] hover:underline cursor-pointer mb-1 leading-snug" id="serpTitle">
                            <?= htmlspecialchars($entity['meta_title'] ?: $entity['name']) ?>
                        </div>
                        <div class="text-[14px] text-[#4d5156] leading-snug" id="serpDesc">
                            <?= htmlspecialchars($entity['meta_description'] ?: 'Sin descripción SEO configurada. Google tomará contenido aleatorio de la página.') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Robots & Crawling -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-medium text-gray-800 text-sm">Indexación & Crawling</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Indexar Página</p>
                            <p class="text-xs text-gray-500">Permitir que Google la muestre</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="robots_index" value="0">
                            <input type="checkbox" name="robots_index" value="1" class="sr-only peer" <?= ($entity['robots_index'] ?? 1) ? 'checked' : '' ?>>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Seguir Enlaces (Follow)</p>
                            <p class="text-xs text-gray-500">Los bots seguirán los links de esta página</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="robots_follow" value="0">
                            <input type="checkbox" name="robots_follow" value="1" class="sr-only peer" <?= ($entity['robots_follow'] ?? 1) ? 'checked' : '' ?>>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-gray-100">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Prioridad Sitemap (0.0 - 1.0)</label>
                        <input type="number" step="0.1" min="0" max="1" name="priority" value="<?= htmlspecialchars($entity['priority'] ?? '0.8') ?>" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm">
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script src="/js/seo-module.js"></script>
