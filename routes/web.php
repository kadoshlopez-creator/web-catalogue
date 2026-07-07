<?php

use App\Core\Application;

$router = Application::$app->router;

// Global Middlewares
$router->registerGlobalMiddleware(new \App\Middleware\SecurityHeadersMiddleware());
$router->registerGlobalMiddleware(new \App\Middleware\CsrfMiddleware());

$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/catalogo', [\App\Controllers\CatalogController::class, 'index']);
$router->get('/producto/{slug}', [\App\Controllers\CatalogController::class, 'show']);
$router->get('/promociones', [\App\Controllers\HomeController::class, 'promotions']);

// Autenticación
$router->get('/login', [\App\Controllers\AuthController::class, 'showLogin']);
$router->post('/login', [\App\Controllers\AuthController::class, 'login']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);

// Panel de Administración
$router->get('/admin/dashboard', [\App\Controllers\AdminController::class, 'index']);
$router->get('/admin/dashboard/metrics', [\App\Controllers\AdminController::class, 'getMetrics']);
$router->get('/admin/dashboard/system-health', [\App\Controllers\AdminController::class, 'getSystemHealth']);
$router->get('/admin/dashboard/tasks', [\App\Controllers\AdminController::class, 'getTasks']);
$router->post('/admin/dashboard/preferences', [\App\Controllers\AdminController::class, 'savePreferences']);

// Categorías
$router->get('/admin/categories', [\App\Controllers\CategoryController::class, 'index']);
$router->get('/admin/categories/create', [\App\Controllers\CategoryController::class, 'create']);
$router->post('/admin/categories', [\App\Controllers\CategoryController::class, 'store']);
$router->get('/admin/categories/{id}/edit', [\App\Controllers\CategoryController::class, 'edit']);
$router->post('/admin/categories/{id}', [\App\Controllers\CategoryController::class, 'update']);
$router->post('/admin/categories/{id}/delete', [\App\Controllers\CategoryController::class, 'delete']);

// Marketing Module
$router->get('/admin/marketing/dashboard', [\App\Controllers\MarketingDashboardController::class, 'index']);
$router->get('/admin/marketing/campaigns', [\App\Controllers\CampaignController::class, 'index']);
$router->get('/admin/marketing/campaigns/create', [\App\Controllers\CampaignController::class, 'create']);
$router->post('/admin/marketing/campaigns', [\App\Controllers\CampaignController::class, 'store']);
$router->get('/admin/marketing/campaigns/{id}/edit', [\App\Controllers\CampaignController::class, 'edit']);
$router->post('/admin/marketing/campaigns/{id}', [\App\Controllers\CampaignController::class, 'update']);
$router->get('/admin/marketing/promotions', [\App\Controllers\PromotionController::class, 'index']);
$router->get('/admin/marketing/promotions/create', [\App\Controllers\PromotionController::class, 'create']);
$router->post('/admin/marketing/promotions', [\App\Controllers\PromotionController::class, 'store']);
$router->get('/admin/marketing/promotions/{id}/edit', [\App\Controllers\PromotionController::class, 'edit']);
$router->post('/admin/marketing/promotions/{id}', [\App\Controllers\PromotionController::class, 'update']);
$router->post('/admin/marketing/promotions/{id}/delete', [\App\Controllers\PromotionController::class, 'delete']);
$router->get('/admin/marketing/offers', [\App\Controllers\OfferController::class, 'index']);
$router->get('/admin/marketing/offers/create', [\App\Controllers\OfferController::class, 'create']);
$router->post('/admin/marketing/offers', [\App\Controllers\OfferController::class, 'store']);
$router->get('/admin/marketing/offers/{id}/edit', [\App\Controllers\OfferController::class, 'edit']);
$router->post('/admin/marketing/offers/{id}', [\App\Controllers\OfferController::class, 'update']);
$router->get('/admin/marketing/banners', [\App\Controllers\BannerController::class, 'index']);
$router->get('/admin/marketing/banners/create', [\App\Controllers\BannerController::class, 'create']);
$router->post('/admin/marketing/banners', [\App\Controllers\BannerController::class, 'store']);
$router->get('/admin/marketing/banners/{id}/edit', [\App\Controllers\BannerController::class, 'edit']);
$router->post('/admin/marketing/banners/{id}', [\App\Controllers\BannerController::class, 'update']);
$router->post('/admin/marketing/banners/{id}/delete', [\App\Controllers\BannerController::class, 'delete']);

// Categorías - SEO Module
$router->get('/admin/categories/{id}/seo', [\App\Controllers\CategorySeoController::class, 'edit']);
$router->post('/admin/categories/{id}/seo', [\App\Controllers\CategorySeoController::class, 'update']);

// Marcas
$router->get('/admin/brands', [\App\Controllers\BrandController::class, 'index']);
$router->get('/admin/brands/create', [\App\Controllers\BrandController::class, 'create']);
$router->post('/admin/brands', [\App\Controllers\BrandController::class, 'store']);
$router->post('/admin/brands/ajax', [\App\Controllers\BrandController::class, 'storeAjax']);
$router->get('/admin/brands/{id}/edit', [\App\Controllers\BrandController::class, 'edit']);
$router->post('/admin/brands/{id}', [\App\Controllers\BrandController::class, 'update']);
$router->post('/admin/brands/{id}/delete', [\App\Controllers\BrandController::class, 'delete']);

// Productos
$router->get('/admin/products', [\App\Controllers\ProductController::class, 'index']);
$router->get('/admin/products/create', [\App\Controllers\ProductController::class, 'create']);
$router->post('/admin/products', [\App\Controllers\ProductController::class, 'store']);
$router->get('/admin/products/{id}/edit', [\App\Controllers\ProductController::class, 'edit']);
$router->post('/admin/products/{id}', [\App\Controllers\ProductController::class, 'update']);
$router->post('/admin/products/{id}/delete', [\App\Controllers\ProductController::class, 'delete']);

// Productos - SEO Module
$router->get('/admin/products/{id}/seo', [\App\Controllers\ProductSeoController::class, 'edit']);
$router->post('/admin/products/{id}/seo', [\App\Controllers\ProductSeoController::class, 'update']);
$router->post('/admin/products/images/{id}/delete', [\App\Controllers\ProductController::class, 'deleteImage']);

// Configuración
$router->get('/admin/settings/home', [\App\Controllers\SettingController::class, 'homeBuilder']);
$router->post('/admin/settings/home', [\App\Controllers\SettingController::class, 'saveHomeBuilder']);
$router->get('/admin/settings/brand', [\App\Controllers\SettingController::class, 'brand']);
$router->post('/admin/settings/brand', [\App\Controllers\SettingController::class, 'saveBrand']);
$router->post('/admin/settings/delete-asset', [\App\Controllers\SettingController::class, 'deleteAsset']);

// Páginas Personalizadas
$router->get('/p/{slug}', [\App\Controllers\HomeController::class, 'page']);
