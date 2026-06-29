<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        
        <!-- Breadcrumbs -->
        <nav class="flex mb-8 text-sm font-medium text-gray-500">
            <a href="/" class="hover:text-blue-600">Inicio</a>
            <span class="mx-2">/</span>
            <a href="/catalogo" class="hover:text-blue-600">Catálogo</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900"><?= htmlspecialchars($product['category_name'] ?? 'General') ?></span>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
            
            <!-- Product Image -->
            <div class="mb-10 lg:mb-0">
                <div class="aspect-w-1 aspect-h-1 bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 shadow-inner relative flex items-center justify-center h-96 lg:h-[600px] <?= empty($images) ? 'p-12' : '' ?>">
                    <?php if ($product['label'] !== 'ninguno'): ?>
                        <span class="absolute top-6 left-6 z-10 bg-blue-600 text-white text-sm font-bold px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg">
                            <?= htmlspecialchars($product['label']) ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($images)): ?>
                        <img src="<?= htmlspecialchars($images[0]['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <svg class="w-48 h-48 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <?php endif; ?>
                </div>

                <?php if (!empty($images) && count($images) > 1): ?>
                    <div class="mt-4 grid grid-cols-4 gap-4">
                        <?php foreach ($images as $img): ?>
                            <div class="aspect-w-1 aspect-h-1 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover cursor-pointer hover:opacity-80 transition-opacity">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="flex flex-col justify-center">
                <div class="mb-6">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 tracking-tight mb-2">
                        <?= htmlspecialchars($product['name']) ?>
                    </h1>
                    <p class="text-sm text-gray-400 font-mono">SKU: <?= htmlspecialchars($product['sku']) ?></p>
                </div>

                <div class="mb-8 flex items-baseline gap-4 flex-wrap">
                    <?php if (isset($product['has_discount']) && $product['has_discount']): ?>
                        <div class="flex flex-col">
                            <span class="text-xl text-gray-400 font-medium line-through mb-1">
                                $<?= number_format($product['base_price'], 2) ?>
                            </span>
                            <div class="flex items-center gap-4">
                                <span class="text-4xl sm:text-5xl font-extrabold text-red-600 flex items-baseline">
                                    $<?= number_format($product['final_price'], 2) ?>
                                    <?= !empty($product['has_tax']) ? '<span class="text-2xl text-gray-400 font-medium ml-2">+ ITBMS</span>' : '' ?>
                                </span>
                                <span class="text-lg font-bold text-white bg-green-500 px-3 py-1 rounded-full">
                                    -<?= $product['offer_details']['type'] === 'percentage' ? number_format($product['offer_details']['value'], 0).'%' : '$'.number_format($product['offer_details']['value'], 2) ?>
                                </span>
                            </div>
                        </div>
                    <?php else: ?>
                        <span class="text-4xl sm:text-5xl font-extrabold text-gray-900 flex items-baseline">
                            $<?= number_format($product['price'], 2) ?>
                            <?= !empty($product['has_tax']) ? '<span class="text-2xl text-gray-400 font-medium ml-2">+ ITBMS</span>' : '' ?>
                        </span>
                    <?php endif; ?>
                    <span class="text-lg text-green-600 font-semibold bg-green-50 px-3 py-1 rounded-full ml-auto">En Stock</span>
                </div>

                <?php if (!empty($product['short_description'])): ?>
                    <div class="mb-8">
                        <p class="text-lg text-gray-600 leading-relaxed">
                            <?= nl2br(htmlspecialchars($product['short_description'])) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="mb-10">
                    <?php 
                        $whatsappNumber = '1234567890'; // Placeholder
                        $message = urlencode("Hola, me interesa el producto: " . $product['name'] . " (SKU: " . $product['sku'] . ")");
                        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$message}";
                    ?>
                    <a href="<?= $whatsappUrl ?>" target="_blank" class="w-full sm:w-auto flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-2xl text-lg font-bold shadow-lg shadow-green-500/30 transition-all transform hover:-translate-y-1">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                        Consultar por WhatsApp
                    </a>
                    <p class="text-sm text-gray-500 mt-4 text-center sm:text-left">
                        Respuesta rápida garantizada por uno de nuestros asesores.
                    </p>
                </div>

                <?php if (!empty($product['full_description'])): ?>
                    <div class="border-t border-gray-100 pt-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Detalles del Producto</h3>
                        <div class="prose prose-blue text-gray-600 max-w-none">
                            <?= nl2br(htmlspecialchars($product['full_description'])) ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
