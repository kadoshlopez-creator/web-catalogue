<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\BannerService;

class BannerController extends Controller
{
    private BannerService $bannerService;

    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());
        $this->bannerService = new BannerService();
    }

    public function index()
    {
        $banners = $this->bannerService->getAllBanners();
        return $this->render('admin/marketing/banners/index', [
            'title' => 'Gestión de Banners',
            'banners' => $banners
        ]);
    }

    public function create()
    {
        return $this->render('admin/marketing/banners/create', [
            'title' => 'Nuevo Banner'
        ]);
    }

    public function store()
    {
        try {
            $data = [
                'title' => $_POST['title'] ?? null,
                'subtitle' => $_POST['subtitle'] ?? null,
                'link' => $_POST['link'] ?? null,
                'sort_order' => $_POST['sort_order'] ?? 0,
                'is_active' => $_POST['is_active'] ?? 0
            ];
            
            $file = $_FILES['image'] ?? [];
            
            $this->bannerService->createBanner($data, $file);
            $_SESSION['flash_success'] = 'Banner creado exitosamente.';
            header('Location: /admin/marketing/banners');
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            header('Location: /admin/marketing/banners/create');
            exit;
        }
    }

    public function edit(int $id)
    {
        $banner = $this->bannerService->getBanner($id);
        if (!$banner) {
            header('Location: /admin/marketing/banners');
            exit;
        }

        return $this->render('admin/marketing/banners/edit', [
            'title' => 'Editar Banner',
            'banner' => $banner
        ]);
    }

    public function update(int $id)
    {
        try {
            $data = [
                'title' => $_POST['title'] ?? null,
                'subtitle' => $_POST['subtitle'] ?? null,
                'link' => $_POST['link'] ?? null,
                'sort_order' => $_POST['sort_order'] ?? 0,
                'is_active' => $_POST['is_active'] ?? 0
            ];
            
            $file = $_FILES['image'] ?? [];
            
            $this->bannerService->updateBanner($id, $data, $file);
            $_SESSION['flash_success'] = 'Banner actualizado exitosamente.';
            header('Location: /admin/marketing/banners');
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            header("Location: /admin/marketing/banners/{$id}/edit");
            exit;
        }
    }

    public function delete(int $id)
    {
        $this->bannerService->deleteBanner($id);
        $_SESSION['flash_success'] = 'Banner eliminado exitosamente.';
        header('Location: /admin/marketing/banners');
        exit;
    }
}
