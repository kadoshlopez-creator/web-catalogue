<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-16">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4">Promociones y Beneficios</h1>
        <p class="text-xl text-gray-500 max-w-2xl mx-auto">Aprovecha estos incentivos exclusivos en tus próximas compras.</p>
    </div>

    <?php if (empty($promotions)): ?>
        <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100 max-w-3xl mx-auto">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No hay promociones activas</h3>
            <p class="text-gray-500 mb-8">Actualmente no tenemos promociones adicionales, pero te invitamos a visitar nuestro catálogo para ver nuestras ofertas directas.</p>
            <a href="/catalogo" class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                Ir al Catálogo
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($promotions as $promo): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300 group">
                    <div class="h-32 bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center p-6 text-center relative overflow-hidden">
                        <svg class="absolute w-64 h-64 text-white opacity-5 -top-10 -right-10 transform rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                        <h3 class="text-2xl font-bold text-white relative z-10 leading-tight"><?= htmlspecialchars($promo['name']) ?></h3>
                    </div>
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto -mt-16 mb-6 shadow-md border-4 border-white relative z-20">
                            <?php if ($promo['type'] === 'free_shipping'): ?>
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <?php else: ?>
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                            <?php endif; ?>
                        </div>
                        
                        <p class="text-gray-600 mb-8 leading-relaxed">
                            <?php if ($promo['type'] === 'free_shipping'): ?>
                                Disfruta de envío 100% gratuito en tus compras mientras esta promoción esté vigente. ¡Aprovecha ahora!
                            <?php else: ?>
                                Promoción especial activa por tiempo limitado.
                            <?php endif; ?>
                        </p>
                        
                        <a href="/catalogo" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gray-900 text-white font-medium rounded-xl hover:bg-black transition-colors">
                            Comprar Ahora
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
