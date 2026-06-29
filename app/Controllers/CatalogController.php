<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
    public function __construct()
    {
        $this->layout = 'public';
    }

    public function index()
    {
        $productModel = new Product();
        $categoryModel = new Category();
        $brandModel = new \App\Models\Brand();
        
        $q = $this->request->get('q') ?? '';
        $categorySlug = $this->request->get('category') ?? '';
        $brandSlug = $this->request->get('brand') ?? '';
        $trend = $this->request->get('trend') ?? '';

        $filters = [];
        if ($categorySlug) $filters['category'] = $categorySlug;
        if ($brandSlug) $filters['brand'] = $brandSlug;
        if ($trend) $filters['trend'] = $trend;

        $products = $productModel->searchCatalog($q, $filters, 50);
        
        $pricingService = new \App\Services\PricingService();
        foreach ($products as &$p) {
            $pricing = $pricingService->calculateFinalPrice($p);
            $p = array_merge($p, $pricing);
        }
        
        $categories = $categoryModel->getTree();
        $brands = $brandModel->allActive();

        return $this->render('public/catalog', [
            'title' => 'Catálogo Completo',
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'currentCategory' => $categorySlug,
            'currentBrand' => $brandSlug,
            'currentTrend' => $trend,
            'q' => $q
        ]);
    }

    public function show(string $slug)
    {
        $productModel = new Product();
        $product = $productModel->findBySlug($slug);

        if (!$product) {
            $this->response->setStatusCode(404);
            return $this->render('errors/404');
        }
        
        $pricingService = new \App\Services\PricingService();
        $pricing = $pricingService->calculateFinalPrice($product);
        $product = array_merge($product, $pricing);

        $imageModel = new \App\Models\ProductImage();
        $images = $imageModel->getByProductId($product['id']);

        return $this->render('public/detail', [
            'title' => $product['name'],
            'meta_description' => $product['short_description'] ?? 'Descubre este increíble producto: ' . $product['name'],
            'product' => $product,
            'images' => $images
        ]);
    }
}
