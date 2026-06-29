<div class="bg-white">
    <!-- SkyMall style search and nav header -->
    <div class="border-b border-gray-200 shadow-sm sticky top-0 bg-white z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Search Bar -->
            <form action="/catalogo" method="GET" class="relative max-w-4xl mx-auto">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="q" value="<?= htmlspecialchars($q ?? '') ?>" 
                    class="block w-full pl-12 pr-4 py-4 rounded-full border-2 border-blue-600 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-lg transition-shadow shadow-sm hover:shadow-md" 
                    placeholder="¿Qué estás buscando?">
                <!-- Preserve existing filters -->
                <?php if (!empty($currentCategory)): ?><input type="hidden" name="category" value="<?= htmlspecialchars($currentCategory) ?>"><?php endif; ?>
                <?php if (!empty($currentBrand)): ?><input type="hidden" name="brand" value="<?= htmlspecialchars($currentBrand) ?>"><?php endif; ?>
                <?php if (!empty($currentTrend)): ?><input type="hidden" name="trend" value="<?= htmlspecialchars($currentTrend) ?>"><?php endif; ?>
            </form>

            <!-- Horizontal Navigation -->
            <div class="mt-6">
                <nav class="flex flex-wrap gap-y-4 gap-x-6 px-2 pb-2">
                    <!-- Brands Dropdown -->
                    <div class="relative group">
                        <a href="#" class="text-gray-600 hover:text-blue-600 font-medium whitespace-nowrap flex items-center gap-1 transition-colors">
                            Comprar por marca
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <div class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                            <div class="py-1">
                                <?php foreach ($brands ?? [] as $brand): ?>
                                    <a href="/catalogo?brand=<?= htmlspecialchars($brand['slug']) ?>" class="block px-4 py-2 text-sm <?= (isset($currentBrand) && $currentBrand === $brand['slug']) ? 'text-blue-600 bg-gray-50' : 'text-gray-700 hover:bg-gray-100' ?>">
                                        <?= htmlspecialchars($brand['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <a href="/catalogo?trend=destacado" class="text-gray-600 hover:text-blue-600 font-medium whitespace-nowrap flex items-center gap-1 transition-colors <?= (isset($currentTrend) && $currentTrend === 'destacado') ? 'text-blue-600' : '' ?>">
                        🔥 Tendencias del momento
                    </a>

                    <?php foreach ($categories as $cat): ?>
                        <?php if ($cat['is_active']): ?>
                            <?php if (!empty($cat['children'])): ?>
                                <div class="relative group">
                                    <a href="/catalogo?category=<?= htmlspecialchars($cat['slug']) ?>" class="text-gray-600 hover:text-blue-600 font-medium whitespace-nowrap flex items-center gap-1 transition-colors <?= (isset($currentCategory) && $currentCategory === $cat['slug']) ? 'text-blue-600 border-b-2 border-blue-600' : '' ?>">
                                        <?= htmlspecialchars($cat['name']) ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </a>
                                    
                                    <div class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                        <div class="py-1">
                                            <?php foreach ($cat['children'] as $child): ?>
                                                <?php if ($child['is_active']): ?>
                                                    <?php if (!empty($child['children'])): ?>
                                                        <div class="relative group/sub">
                                                            <a href="/catalogo?category=<?= htmlspecialchars($child['slug']) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600 flex justify-between items-center <?= (isset($currentCategory) && $currentCategory === $child['slug']) ? 'text-blue-600 bg-gray-50' : '' ?>">
                                                                <?= htmlspecialchars($child['name']) ?>
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                            </a>
                                                            <div class="absolute left-full top-0 ml-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover/sub:opacity-100 group-hover/sub:visible transition-all z-50">
                                                                <div class="py-1">
                                                                    <?php foreach ($child['children'] as $subchild): ?>
                                                                        <?php if ($subchild['is_active']): ?>
                                                                            <a href="/catalogo?category=<?= htmlspecialchars($subchild['slug']) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600 <?= (isset($currentCategory) && $currentCategory === $subchild['slug']) ? 'text-blue-600 bg-gray-50' : '' ?>">
                                                                                <?= htmlspecialchars($subchild['name']) ?>
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <a href="/catalogo?category=<?= htmlspecialchars($child['slug']) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600 <?= (isset($currentCategory) && $currentCategory === $child['slug']) ? 'text-blue-600 bg-gray-50' : '' ?>">
                                                            <?= htmlspecialchars($child['name']) ?>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <a href="/catalogo?category=<?= htmlspecialchars($cat['slug']) ?>" class="text-gray-600 hover:text-blue-600 font-medium whitespace-nowrap transition-colors <?= (isset($currentCategory) && $currentCategory === $cat['slug']) ? 'text-blue-600 border-b-2 border-blue-600' : '' ?>">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if (!empty($q) || !empty($currentCategory) || !empty($currentBrand) || !empty($currentTrend)): ?>
                <div class="mb-8 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Resultados de búsqueda
                        <span class="text-gray-500 text-lg font-normal ml-2">(<?= count($products) ?> productos)</span>
                    </h2>
                    <a href="/catalogo" class="text-sm font-medium text-red-600 hover:text-red-800 bg-red-50 px-3 py-1.5 rounded-full transition-colors">Limpiar filtros</a>
                </div>
            <?php endif; ?>

            <!-- Product Grid -->
            <?php if (empty($products)): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No se encontraron productos</h3>
                    <p class="text-gray-500 max-w-md mx-auto">No hay resultados que coincidan con tu búsqueda actual. Intenta buscar otra cosa o elimina los filtros.</p>
                    <a href="/catalogo" class="mt-8 inline-block bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-8 rounded-full shadow-md hover:shadow-lg transition-all">
                        Ver todos los productos
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($products as $product): ?>
                        <a href="/producto/<?= htmlspecialchars($product['slug']) ?>" class="group flex flex-col bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden transform hover:-translate-y-1">
                            <div class="h-64 bg-gray-50 flex items-center justify-center relative overflow-hidden">
                                <?php if ($product['label'] !== 'ninguno'): ?>
                                    <span class="absolute top-4 left-4 z-10 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                        <?= htmlspecialchars($product['label']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (isset($product['has_discount']) && $product['has_discount']): ?>
                                    <span class="absolute top-4 right-4 z-10 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                        -<?= $product['offer_details']['type'] === 'percentage' ? number_format($product['offer_details']['value'], 0).'%' : '$'.number_format($product['offer_details']['value'], 2) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (!empty($product['main_image'])): ?>
                                    <img src="<?= htmlspecialchars($product['main_image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <?php else: ?>
                                    <svg class="w-20 h-20 text-gray-300 group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <?php endif; ?>
                            </div>
                            <div class="p-5 flex-1 flex flex-col">
                                <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">
                                    <?= htmlspecialchars($product['brand_name'] ?? $product['category_name'] ?? 'General') ?>
                                </p>
                                <h3 class="text-base font-bold text-gray-900 group-hover:text-red-600 transition-colors line-clamp-2 mb-3 flex-1 leading-snug">
                                    <?= htmlspecialchars($product['name']) ?>
                                </h3>
                                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <div>
                                        <?php if (isset($product['has_discount']) && $product['has_discount']): ?>
                                            <span class="text-sm font-medium text-gray-400 line-through block">$<?= number_format($product['base_price'], 2) ?></span>
                                            <span class="text-xl font-extrabold text-red-600 flex items-baseline gap-1">$<?= number_format($product['final_price'], 2) ?><?= !empty($product['has_tax']) ? '<span class="text-xs font-medium text-gray-400">+ ITBMS</span>' : '' ?></span>
                                        <?php else: ?>
                                            <span class="text-xl font-extrabold text-gray-900 flex items-baseline gap-1">$<?= number_format($product['price'], 2) ?><?= !empty($product['has_tax']) ? '<span class="text-xs font-medium text-gray-400">+ ITBMS</span>' : '' ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center group-hover:bg-red-600 transition-colors">
                                        <svg class="w-5 h-5 text-red-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
.hide-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>
