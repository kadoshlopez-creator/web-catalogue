<?php if (!empty($ribbon) && !empty($ribbon['is_active'])): ?>
    <?php
    $bgStyle = $ribbon['bg_css'] ?? '';
    if (empty($bgStyle)) {
        $grad = $ribbon['gradient'] ?? 'red';
        if ($grad === 'blue') $bgStyle = 'linear-gradient(to right, #3b82f6, #2563eb, #1e40af)';
        elseif ($grad === 'purple') $bgStyle = 'linear-gradient(to right, #a855f7, #9333ea, #6b21a8)';
        elseif ($grad === 'orange') $bgStyle = 'linear-gradient(to right, #f97316, #ea580c, #9a3412)';
        elseif ($grad === 'dark') $bgStyle = 'linear-gradient(to right, #374151, #1f2937, #111827)';
        else $bgStyle = 'linear-gradient(to right, #ef4444, #dc2626, #991b1b)';
    }
    ?>
    <div style="background: <?= htmlspecialchars($bgStyle) ?>;" class="text-white py-3 overflow-hidden shadow-inner flex items-center <?= empty($ribbon['is_marquee']) ? 'justify-center' : '' ?>">
        <?php if (!empty($ribbon['is_marquee'])): ?>
            <div class="whitespace-nowrap inline-block animate-[marquee_15s_linear_infinite] hover:[animation-play-state:paused] font-medium text-sm md:text-base tracking-wide" style="padding-left: 100vw;">
                <?= htmlspecialchars($ribbon['text'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8') ?>
            </div>
            <!-- Tailwind custom animation keyframes defined directly in style since we are using browser tailwind -->
            <style>
                @keyframes marquee {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(-100%); }
                }
            </style>
        <?php else: ?>
            <div class="text-center font-medium text-sm md:text-base tracking-wide w-full px-4">
                <?= htmlspecialchars($ribbon['text'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Hero Section / Banners -->
<?php if (!empty($banners)): ?>
    <!-- 576 / 1856 = 0.3103448275... (31.034%) -> This mathematically forces the container to be exactly 1856x576 aspect ratio on all browsers -->
    <div class="relative bg-gray-900 overflow-hidden group w-full" id="banner-slider" style="padding-top: 31.03448%;">
        
        <?php foreach($banners as $index => $b): ?>
            <div class="banner-slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out <?= $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' ?>" id="slide-<?= $index ?>">
                
                <?php if (!empty($b['link'])): ?>
                    <a href="<?= htmlspecialchars($b['link']) ?>" class="block absolute inset-0 z-10"></a>
                <?php endif; ?>

                <?php $imgPath = dirname(__DIR__, 3) . '/public' . $b['image_path']; ?>
                <img src="<?= htmlspecialchars($b['image_path']) ?>?v=<?= file_exists($imgPath) ? filemtime($imgPath) : time() ?>" alt="<?= htmlspecialchars($b['title'] ?? 'Banner') ?>" class="absolute inset-0 w-full h-full object-fill">
                
                <?php if (!empty($b['title']) || !empty($b['subtitle'])): ?>
                    <!-- Gradient overlay only at the bottom for text readability -->
                    <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-gray-900/90 to-transparent pointer-events-none"></div>
                    
                    <div class="absolute bottom-4 right-4 sm:bottom-6 sm:right-8 lg:bottom-10 lg:right-12 z-20 pointer-events-none text-right">
                        <div class="max-w-xl md:max-w-2xl lg:max-w-3xl">
                                <?php if (!empty($b['title'])): ?>
                                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-2 md:mb-3 leading-tight drop-shadow-lg"><?= htmlspecialchars($b['title']) ?></h2>
                                <?php endif; ?>
                                <?php if (!empty($b['subtitle'])): ?>
                                    <p class="text-base md:text-lg lg:text-xl text-gray-200 mb-4 md:mb-6 drop-shadow-md font-medium"><?= htmlspecialchars($b['subtitle']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($b['link'])): ?>
                                    <span class="inline-block bg-blue-600/90 backdrop-blur-sm text-white font-bold py-2 px-6 md:py-3 md:px-8 rounded-full shadow-lg border border-blue-400/30">
                                        Ver Promoción
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <?php if (count($banners) > 1): ?>
            <!-- Controls -->
            <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/70 backdrop-blur-sm text-gray-900 p-3 rounded-full opacity-0 group-hover:opacity-100 transition-all z-20 shadow-lg border border-white/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/70 backdrop-blur-sm text-gray-900 p-3 rounded-full opacity-0 group-hover:opacity-100 transition-all z-20 shadow-lg border border-white/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </button>
            <!-- Fade Slider Script -->
            <script>
                let currentSlide = 0;
                const slides = document.querySelectorAll('.banner-slide');
                const totalSlides = slides.length;
                
                function showSlide(index) {
                    slides.forEach((slide, i) => {
                        if (i === index) {
                            slide.classList.remove('opacity-0', 'z-0');
                            slide.classList.add('opacity-100', 'z-10');
                        } else {
                            slide.classList.remove('opacity-100', 'z-10');
                            slide.classList.add('opacity-0', 'z-0');
                        }
                    });
                    currentSlide = index;
                }
                
                function nextSlide() {
                    showSlide((currentSlide + 1) % totalSlides);
                }
                
                function prevSlide() {
                    showSlide((currentSlide - 1 + totalSlides) % totalSlides);
                }
                
                setInterval(nextSlide, 5000);
            </script>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Default/Secondary Hero Section -->
<?php if (!empty($heroSettings) && !empty($heroSettings['is_active']) && !empty($heroSlides)): ?>
    <div class="relative bg-white overflow-hidden <?= !empty($banners) ? 'mt-8 lg:mt-12 border-t border-gray-100 shadow-sm' : '' ?>" id="secondary-hero-container">
        
        <?php foreach($heroSlides as $index => $slide): ?>
            <?php 
                $layoutClass = ($slide['layout'] ?? 'text_left') === 'text_right' ? 'lg:flex-row-reverse' : 'lg:flex-row';
                $opacityClass = $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 absolute inset-0';
            ?>
            <div class="hero-carousel-slide transition-opacity duration-1000 ease-in-out <?= $opacityClass ?> w-full bg-white flex flex-col <?= $layoutClass ?>" id="hero-slide-<?= $index ?>">
                
                <!-- Text Content -->
                <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-16 lg:p-20 xl:p-28">
                    <div class="max-w-xl text-center lg:text-left w-full">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl mb-6">
                            <?php 
                                $title = $slide['title'] ?? '';
                                $words = explode(' ', $title);
                                if (count($words) > 2) {
                                    $lastTwo = array_splice($words, -2);
                                    echo '<span class="block xl:inline">' . htmlspecialchars(implode(' ', $words)) . '</span> ';
                                    echo '<span class="block text-blue-600 xl:inline">' . htmlspecialchars(implode(' ', $lastTwo)) . '</span>';
                                } else {
                                    echo '<span class="block xl:inline">' . htmlspecialchars($title) . '</span>';
                                }
                            ?>
                        </h1>
                        <p class="text-base text-gray-500 sm:text-lg md:text-xl mb-8">
                            <?= htmlspecialchars($slide['subtitle'] ?? '') ?>
                        </p>
                        <?php if (!empty($slide['btn_text'])): ?>
                            <div class="sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-full shadow-lg shadow-blue-500/30">
                                    <a href="<?= htmlspecialchars($slide['btn_link'] ?? '#') ?>" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10 transition-colors">
                                        <?= htmlspecialchars($slide['btn_text']) ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Image Content -->
                <div class="w-full lg:w-1/2 bg-gray-100 flex items-center justify-center relative min-h-[300px] lg:min-h-full">
                    <?php if (!empty($slide['image_path'])): ?>
                        <img src="<?= htmlspecialchars($slide['image_path']) ?>" alt="<?= htmlspecialchars($slide['title'] ?? '') ?>" class="absolute inset-0 w-full h-full object-cover">
                    <?php else: ?>
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center">
                            <svg class="w-32 h-32 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
        
        <?php if (count($heroSlides) > 1): ?>
            <!-- Controls -->
            <button onclick="prevHeroSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/10 hover:bg-black/30 text-white p-3 rounded-full opacity-0 hover:opacity-100 transition-all z-20 shadow-lg" style="opacity: 0.5;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button onclick="nextHeroSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/10 hover:bg-black/30 text-white p-3 rounded-full opacity-0 hover:opacity-100 transition-all z-20 shadow-lg" style="opacity: 0.5;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </button>
            <!-- Hero Slider Script -->
            <script>
                let currentHeroSlide = 0;
                const heroSlidesArr = document.querySelectorAll('.hero-carousel-slide');
                const totalHeroSlides = heroSlidesArr.length;
                
                function showHeroSlide(index) {
                    heroSlidesArr.forEach((slide, i) => {
                        if (i === index) {
                            slide.classList.remove('opacity-0', 'z-0', 'absolute', 'inset-0');
                            slide.classList.add('opacity-100', 'z-10');
                        } else {
                            slide.classList.remove('opacity-100', 'z-10');
                            slide.classList.add('opacity-0', 'z-0', 'absolute', 'inset-0');
                        }
                    });
                    currentHeroSlide = index;
                }
                
                function nextHeroSlide() {
                    showHeroSlide((currentHeroSlide + 1) % totalHeroSlides);
                }
                
                function prevHeroSlide() {
                    showHeroSlide((currentHeroSlide - 1 + totalHeroSlides) % totalHeroSlides);
                }
                
                setInterval(nextHeroSlide, 6000); // 6 seconds for hero slides
            </script>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Dynamic Sections -->
<?php if (!empty($sections)): ?>
    <?php foreach ($sections as $index => $section): ?>
        <?php $bgClass = ($index % 2 === 0) ? 'bg-white py-16' : 'bg-gray-50 border-t border-b border-gray-200 py-16'; ?>
        
        <div class="<?= $bgClass ?>">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900"><?= htmlspecialchars($section['title']) ?></h2>
                        <?php if (!empty($section['subtitle'])): ?>
                            <p class="mt-4 text-lg text-gray-500"><?= htmlspecialchars($section['subtitle']) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($section['type'] !== 'featured' && $section['type'] !== 'promotions'): ?>
                        <a href="/catalogo" class="text-blue-600 hover:text-blue-800 font-medium hidden sm:block">Ver todo &rarr;</a>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php foreach ($section['products'] as $product): ?>
                        <a href="/producto/<?= htmlspecialchars($product['slug']) ?>" class="group block bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden transform hover:-translate-y-1">
                            <div class="h-48 bg-gray-100 flex items-center justify-center relative overflow-hidden">
                                <?php if ($product['label'] !== 'ninguno'): ?>
                                    <span class="absolute top-4 left-4 z-10 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                                        <?= htmlspecialchars($product['label']) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (isset($product['has_discount']) && $product['has_discount']): ?>
                                    <span class="absolute top-4 right-4 z-10 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                        -<?= $product['offer_details']['type'] === 'percentage' ? number_format($product['offer_details']['value'], 0).'%' : '$'.number_format($product['offer_details']['value'], 2) ?>
                                    </span>
                                <?php endif; ?>

                                <?php if (!empty($product['main_image'])): ?>
                                    <img src="<?= htmlspecialchars($product['main_image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <?php else: ?>
                                    <svg class="w-16 h-16 text-gray-300 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <?php endif; ?>
                            </div>
                            <div class="p-6 flex flex-col h-full">
                                <p class="text-sm font-medium text-blue-600 mb-1"><?= htmlspecialchars($product['category_name'] ?? 'General') ?></p>
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 mb-2 flex-grow">
                                    <?= htmlspecialchars($product['name']) ?>
                                </h3>
                                <div class="flex items-center justify-between mt-4">
                                    <div>
                                        <?php if (isset($product['has_discount']) && $product['has_discount']): ?>
                                            <span class="text-xs font-medium text-gray-400 line-through block">$<?= number_format($product['base_price'], 2) ?></span>
                                            <span class="text-xl font-extrabold text-red-600 flex items-baseline gap-1">$<?= number_format($product['final_price'], 2) ?><?= !empty($product['has_tax']) ? '<span class="text-xs font-medium text-gray-400">+ ITBMS</span>' : '' ?></span>
                                        <?php else: ?>
                                            <span class="text-xl font-extrabold text-gray-900 flex items-baseline gap-1">$<?= number_format($product['price'], 2) ?><?= !empty($product['has_tax']) ? '<span class="text-xs font-medium text-gray-400">+ ITBMS</span>' : '' ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-gray-400 group-hover:text-blue-600 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
