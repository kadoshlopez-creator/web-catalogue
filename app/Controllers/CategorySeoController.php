<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\DTOs\SeoDTO;
use App\Repositories\CategoryRepository;
use App\Repositories\CategorySlugHistoryRepository;
use App\Services\SeoService;
use App\Services\SlugService;
use App\Validators\SeoValidator;
use App\Middleware\AuthMiddleware;

/**
 * Class CategorySeoController
 * 
 * Handles the HTTP requests for the Category SEO administration module.
 * Delegates business logic to the SeoService.
 * 
 * @package App\Controllers
 */
class CategorySeoController extends Controller
{
    private SeoService $seoService;
    private CategoryRepository $categoryRepository;
    private SeoValidator $validator;

    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());

        // Simple Dependency Injection for now
        $this->categoryRepository = new CategoryRepository();
        $slugHistoryRepository = new CategorySlugHistoryRepository();
        $slugService = new SlugService($this->categoryRepository);
        
        $this->seoService = new SeoService(
            $slugService,
            $this->categoryRepository,
            $slugHistoryRepository
        );
        $this->validator = new SeoValidator();
    }

    /**
     * Show the SEO edit form for a category.
     *
     * @param int $id
     * @return mixed
     */
    public function edit(int $id)
    {
        $category = $this->categoryRepository->findById($id);
        
        if (!$category) {
            Session::setFlash('error', 'Categoría no encontrada.');
            $this->redirect('/admin/categories');
            return;
        }

        $seoScore = $this->seoService->calculateSeoScore($category);

        return $this->render('admin/categories/seo', [
            'title' => 'Configuración SEO: ' . htmlspecialchars($category['name']),
            'category' => $category,
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
            $this->redirect("/admin/categories/{$id}/seo");
            return;
        }

        $category = $this->categoryRepository->findById($id);
        if (!$category) {
            Session::setFlash('error', 'Categoría no encontrada.');
            $this->redirect('/admin/categories');
            return;
        }

        // Map request to DTO
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
            'priority' => $this->request->post('priority', '0.5'),
            'changefreq' => $this->request->post('changefreq', 'monthly')
        ]);

        // Validate
        $errors = $this->validator->validate($dto);
        if (!empty($errors)) {
            // Usually we'd flash errors to session and redirect back
            Session::setFlash('error', 'Revisa los errores en el formulario.');
            // Let's assume we have a way to pass old input and errors. For simplicity:
            Session::set('errors', $errors);
            $this->redirect("/admin/categories/{$id}/seo");
            return;
        }

        // Delegate to Service Layer
        if ($this->seoService->updateSeo($id, $dto)) {
            Session::setFlash('success', 'Configuración SEO guardada correctamente. Historial de slugs actualizado.');
        } else {
            Session::setFlash('error', 'Hubo un error al guardar la configuración.');
        }

        $this->redirect("/admin/categories/{$id}/seo");
    }
}
