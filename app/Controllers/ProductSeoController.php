<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\DTOs\SeoDTO;
use App\Repositories\ProductRepository;
use App\Repositories\ProductSlugHistoryRepository;
use App\Services\SeoService;
use App\Services\SlugService;
use App\Validators\SeoValidator;
use App\Middleware\AuthMiddleware;

/**
 * Class ProductSeoController
 * 
 * Handles the HTTP requests for the Product SEO administration module.
 * 
 * @package App\Controllers
 */
class ProductSeoController extends Controller
{
    private SeoService $seoService;
    private ProductRepository $productRepository;
    private SeoValidator $validator;

    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());

        $this->productRepository = new ProductRepository();
        $slugHistoryRepository = new ProductSlugHistoryRepository();
        $slugService = new SlugService($this->productRepository);
        
        $this->seoService = new SeoService(
            $slugService,
            $this->productRepository,
            $slugHistoryRepository
        );
        $this->validator = new SeoValidator();
    }

    /**
     * Show the SEO edit form for a product.
     *
     * @param int $id
     * @return mixed
     */
    public function edit(int $id)
    {
        $product = $this->productRepository->findById($id);
        
        if (!$product) {
            Session::setFlash('error', 'Producto no encontrado.');
            $this->redirect('/admin/products');
            return;
        }

        $seoScore = $this->seoService->calculateSeoScore($product);

        return $this->render('admin/products/seo', [
            'title' => 'Configuración SEO: ' . htmlspecialchars($product['name']),
            'entity' => $product,
            'entityType' => 'product',
            'seoScore' => $seoScore
        ]);
    }

    /**
     * Handle the update request for SEO parameters.
     *
     * @param int $id
     * @return mixed
     */
    public function update(int $id)
    {
        if (!Session::validateCsrf()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect("/admin/products/{$id}/seo");
            return;
        }

        $product = $this->productRepository->findById($id);
        if (!$product) {
            Session::setFlash('error', 'Producto no encontrado.');
            $this->redirect('/admin/products');
            return;
        }

        $dto = new SeoDTO([
            'slug' => $this->request->post('slug'),
            'meta_title' => $this->request->post('meta_title'),
            'meta_description' => $this->request->post('meta_description'),
            'meta_keywords' => $this->request->post('meta_keywords'),
            'canonical_url' => $this->request->post('canonical_url'),
            'robots_index' => $this->request->post('robots_index', '1'),
            'robots_follow' => $this->request->post('robots_follow', '1'),
            'schema_json' => $this->request->post('schema_json'),
            'open_graph_title' => $this->request->post('open_graph_title'),
            'open_graph_description' => $this->request->post('open_graph_description'),
            'open_graph_image' => $this->request->post('open_graph_image'),
            'twitter_title' => $this->request->post('twitter_title'),
            'twitter_description' => $this->request->post('twitter_description'),
            'twitter_image' => $this->request->post('twitter_image'),
            'priority' => $this->request->post('priority', '0.8'),
            'changefreq' => $this->request->post('changefreq', 'daily')
        ]);

        $errors = $this->validator->validate($dto);
        if (!empty($errors)) {
            Session::setFlash('error', 'Revisa los errores en el formulario.');
            Session::set('errors', $errors);
            $this->redirect("/admin/products/{$id}/seo");
            return;
        }

        if ($this->seoService->updateSeo($id, $dto)) {
            Session::setFlash('success', 'Configuración SEO guardada correctamente. Historial de slugs actualizado.');
        } else {
            Session::setFlash('error', 'Hubo un error al guardar la configuración.');
        }

        $this->redirect("/admin/products/{$id}/seo");
    }
}
