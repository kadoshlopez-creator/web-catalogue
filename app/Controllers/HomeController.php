<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->layout = 'public';
    }

    public function index()
    {
        $productModel = new Product();
        $settingModel = new \App\Models\Setting();
        $categoryModel = new \App\Models\Category();
        $brandModel = new \App\Models\Brand();

        $sectionsConfig = $settingModel->get('home_sections');
        
        // Default sections if not configured
        if (empty($sectionsConfig)) {
            $sectionsConfig = [
                ['type' => 'latest', 'title' => 'Novedades', 'subtitle' => 'Descubre las últimas adiciones a nuestro catálogo.', 'limit' => 4],
                ['type' => 'featured', 'title' => 'Productos Destacados', 'subtitle' => 'Nuestra mejor selección para ti.', 'limit' => 4]
            ];
        }

        $homeSections = [];
        
        foreach ($sectionsConfig as $config) {
            $filters = [];
            $type = $config['type'];
            
            if ($type === 'latest') {
                // No filters, just order by default (DESC)
            } elseif ($type === 'featured') {
                $filters['trend'] = 'destacado';
            } elseif ($type === 'promotions') {
                $filters['trend'] = 'oferta'; // Assuming 'oferta' falls into trend logic or we adjust searchCatalog
            } elseif ($type === 'category' && !empty($config['category_id'])) {
                $cat = $categoryModel->find($config['category_id']);
                if ($cat) $filters['category'] = $cat['slug'];
            } elseif ($type === 'brand' && !empty($config['brand_id'])) {
                $brand = $brandModel->find($config['brand_id']);
                if ($brand) $filters['brand'] = $brand['slug'];
            }
            
            // special check for promotions
            if ($type === 'promotions') {
                 // The searchCatalog trend filter catches 'oferta'. But to be explicit we might need a custom query or just rely on searchCatalog's trend behavior.
                 // For now trend includes oferta, nuevo, destacado
                 $filters['trend'] = 'oferta';
            }

            $products = $productModel->searchCatalog('', $filters, $config['limit'] ?? 4);
            
            if (!empty($products)) {
                $pricingService = new \App\Services\PricingService();
                foreach ($products as &$p) {
                    $pricing = $pricingService->calculateFinalPrice($p);
                    $p = array_merge($p, $pricing);
                }

                $homeSections[] = [
                    'title' => $config['title'],
                    'subtitle' => $config['subtitle'] ?? '',
                    'products' => $products,
                    'type' => $type
                ];
            }
        }
        
        $ribbon = $settingModel->get('home_ribbon', ['is_active' => false]);
        
        $heroSettings = $settingModel->get('home_hero_settings', ['is_active' => false]);
        $heroSlides = $settingModel->get('home_hero_slides', []);
        
        // Migration fallback for public view
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
            }
        }
        
        $bannerService = new \App\Services\BannerService();
        $banners = $bannerService->getActiveBanners();

        return $this->render('public/home', [
            'title' => 'Inicio',
            'sections' => $homeSections,
            'ribbon' => $ribbon,
            'heroSettings' => $heroSettings,
            'heroSlides' => $heroSlides,
            'banners' => $banners
        ]);
    }

    public function promotions()
    {
        $promotionService = new \App\Services\PromotionService();
        $promotions = $promotionService->getAllPromotions();
        
        // Filter only active promotions
        $activePromotions = array_filter($promotions, function($p) {
            return $p['status'] === 'active';
        });

        return $this->render('public/promotions', [
            'title' => 'Promociones Activas',
            'promotions' => $activePromotions
        ]);
    }
}
