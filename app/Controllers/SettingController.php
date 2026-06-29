<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Brand;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());
        // Solo usuarios con el permiso 'settings.manage' pueden acceder
        // Asignar este permiso en la tabla de permisos al rol super_admin
        $this->registerMiddleware(new RoleMiddleware('settings.manage'));
    }

    private function safeRedirectBack(string $fallback): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
        if ($referer && $appUrl && str_starts_with($referer, $appUrl . '/')) {
            $this->redirect($referer);
        } else {
            $this->redirect($fallback);
        }
    }

    public function homeBuilder()
    {
        $settingModel = new Setting();
        $categoryModel = new Category();
        $brandModel = new Brand();

        $sections = $settingModel->get('home_sections', []);
        $ribbon = $settingModel->get('home_ribbon', []);
        
        $categories = $categoryModel->all();
        $brands = $brandModel->allActive();

        return $this->render('admin/settings/home_builder', [
            'title' => 'Constructor de Inicio',
            'sections' => $sections,
            'ribbon' => $ribbon,
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    public function brand()
    {
        $settingModel = new Setting();
        $site_logo = $settingModel->get('site_logo', '');
        $site_logo_height = $settingModel->get('site_logo_height', '48');
        $site_favicon = $settingModel->get('site_favicon', '');
        $login_logo = $settingModel->get('login_logo', '');

        return $this->render('admin/settings/brand', [
            'title' => 'Identidad del Sitio',
            'site_logo' => $site_logo,
            'site_logo_height' => $site_logo_height,
            'site_favicon' => $site_favicon,
            'login_logo' => $login_logo
        ]);
    }

    public function saveBrand()
    {
        // CSRF validation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Session::validateCsrfToken($this->request->post('_csrf_token'))) {
                Session::flash('error', 'Token de seguridad inválido.');
                return $this->redirect('/admin/settings/brand');
            }

            $settingModel = new Setting();
            
            // Procesar Logo
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['logo']['tmp_name'];
                $fileName = $_FILES['logo']['name'];
                $fileSize = $_FILES['logo']['size'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($fileExt, $allowedExts) && $fileSize <= 2 * 1024 * 1024) { // 2MB max
                    $uploadDir = __DIR__ . '/../../public/uploads/settings';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $newFileName = 'logo_' . bin2hex(random_bytes(8)) . '.' . $fileExt;
                    $uploadPath = $uploadDir . '/' . $newFileName;
                    
                    if (move_uploaded_file($fileTmp, $uploadPath)) {
                        $settingModel->set('site_logo', '/uploads/settings/' . $newFileName);
                    }
                } else {
                    Session::flash('error', 'El logo debe ser una imagen válida (PNG, JPG, WEBP, SVG) y no superar 2MB.');
                    return $this->redirect('/admin/settings/brand');
                }
            }
            
            // Height
            $logoHeight = $this->request->post('logo_height');
            if ($logoHeight) {
                $settingModel->set('site_logo_height', (int)$logoHeight);
            }
            
            // Procesar Favicon
            if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['favicon']['tmp_name'];
                $fileName = $_FILES['favicon']['name'];
                $fileSize = $_FILES['favicon']['size'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExts = ['ico', 'png', 'webp'];

                if (in_array($fileExt, $allowedExts) && $fileSize <= 500 * 1024) { // 500KB max
                    $uploadDir = __DIR__ . '/../../public/uploads/settings';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $newFileName = 'favicon_' . bin2hex(random_bytes(8)) . '.' . $fileExt;
                    $uploadPath = $uploadDir . '/' . $newFileName;
                    
                    if (move_uploaded_file($fileTmp, $uploadPath)) {
                        $settingModel->set('site_favicon', '/uploads/settings/' . $newFileName);
                    }
                } else {
                    Session::flash('error', 'El favicon debe ser una imagen válida (ICO, PNG, SVG, WEBP) y no superar 500KB.');
                    return $this->redirect('/admin/settings/brand');
                }
            }

            // Procesar Logo Login
            if (isset($_FILES['login_logo']) && $_FILES['login_logo']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['login_logo']['tmp_name'];
                $fileName = $_FILES['login_logo']['name'];
                $fileSize = $_FILES['login_logo']['size'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($fileExt, $allowedExts) && $fileSize <= 2 * 1024 * 1024) { // 2MB max
                    $uploadDir = __DIR__ . '/../../public/uploads/settings';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $newFileName = 'login_logo_' . bin2hex(random_bytes(8)) . '.' . $fileExt;
                    $uploadPath = $uploadDir . '/' . $newFileName;
                    
                    if (move_uploaded_file($fileTmp, $uploadPath)) {
                        $settingModel->set('login_logo', '/uploads/settings/' . $newFileName);
                    }
                } else {
                    Session::flash('error', 'El logo de login debe ser una imagen válida (PNG, JPG, WEBP, SVG) y no superar 2MB.');
                    return $this->redirect('/admin/settings/brand');
                }
            }

            Session::flash('success', 'Configuración de marca guardada correctamente.');
            return $this->redirect('/admin/settings/brand');
        }
    }

    public function saveHomeBuilder()
    {
        // CSRF validation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Session::validateCsrfToken($this->request->post('_csrf_token'))) {
                Session::flash('error', 'Token de seguridad inválido.');
                return $this->redirect('/admin/settings/home');
            }

            $settingModel = new Setting();

            $sectionsData = $this->request->post('sections');
            $ribbonData = $this->request->post('ribbon');
            
            // Procesar Sections
            $sections = [];
            if (!empty($sectionsData) && is_array($sectionsData)) {
                foreach ($sectionsData as $section) {
                    if (!empty($section['type']) && !empty($section['title'])) {
                        $sections[] = [
                            'type' => $section['type'],
                            'title' => $section['title'],
                            'subtitle' => $section['subtitle'] ?? '',
                            'limit' => (int)($section['limit'] ?? 4),
                            'category_id' => !empty($section['category_id']) ? (int)$section['category_id'] : null,
                            'brand_id' => !empty($section['brand_id']) ? (int)$section['brand_id'] : null
                        ];
                    }
                }
            }
            
            // Procesar Ribbon
            $ribbon = [
                'is_active' => !empty($ribbonData['is_active']) ? true : false,
                'text' => $ribbonData['text'] ?? '',
                'gradient' => $ribbonData['gradient'] ?? 'red',
                'bg_css' => $ribbonData['bg_css'] ?? '',
                'is_marquee' => !empty($ribbonData['is_marquee']) ? true : false
            ];
            
            // Procesar Hero Settings
            $heroSettingsData = $this->request->post('hero_settings');
            $heroSettings = [
                'is_active' => !empty($heroSettingsData['is_active']) ? true : false,
            ];
            
            // Procesar Hero Slides y las imágenes
            $heroSlidesData = $this->request->post('hero_slides');
            $heroSlides = [];
            
            if (!empty($heroSlidesData) && is_array($heroSlidesData)) {
                $uploadDir = __DIR__ . '/../../public/uploads/hero';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                foreach ($heroSlidesData as $index => $slide) {
                    if (empty($slide['title'])) continue;
                    
                    $imagePath = $slide['existing_image'] ?? '';
                    
                    // Comprobar si hay una nueva imagen para este slide
                    if (isset($_FILES['hero_images']['name'][$index]) && !empty($_FILES['hero_images']['name'][$index])) {
                        if ($_FILES['hero_images']['error'][$index] === UPLOAD_ERR_OK) {
                            $fileTmp = $_FILES['hero_images']['tmp_name'][$index];
                            $fileName = $_FILES['hero_images']['name'][$index];
                            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                            
                            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
                            if (in_array($fileExt, $allowedExts)) {
                                $newFileName = 'hero_' . bin2hex(random_bytes(8)) . '_' . $index . '.' . $fileExt;
                                $uploadPath = $uploadDir . '/' . $newFileName;
                                
                                if (move_uploaded_file($fileTmp, $uploadPath)) {
                                    $imagePath = '/uploads/hero/' . $newFileName;
                                }
                            }
                        }
                    }
                    
                    $heroSlides[] = [
                        'title' => $slide['title'],
                        'subtitle' => $slide['subtitle'] ?? '',
                        'btn_text' => $slide['btn_text'] ?? '',
                        'btn_link' => $slide['btn_link'] ?? '',
                        'layout' => $slide['layout'] ?? 'text_left',
                        'image_path' => $imagePath
                    ];
                }
            }

            $settingModel->set('home_sections', $sections);
            $settingModel->set('home_ribbon', $ribbon);
            $settingModel->set('home_hero_settings', $heroSettings);
            $settingModel->set('home_hero_slides', $heroSlides);

            Session::flash('success', 'Portada guardada correctamente.');
            return $this->redirect('/admin/settings/home');
        }
    }

    public function deleteAsset()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Session::validateCsrfToken($this->request->post('_csrf_token'))) {
                Session::flash('error', 'Token de seguridad inválido.');
                return $this->redirect('/admin/settings/home');
            }
            
            $type = $this->request->post('type');
            $settingModel = new Setting();
            
            if ($type === 'logo') {
                $current = $settingModel->get('site_logo');
                if ($current) {
                    $filePath = __DIR__ . '/../../public' . $current;
                    if (file_exists($filePath)) unlink($filePath);
                    $settingModel->set('site_logo', '');
                }
                Session::flash('success', 'Logo eliminado correctamente.');
            } elseif ($type === 'favicon') {
                $current = $settingModel->get('site_favicon');
                if ($current) {
                    $filePath = __DIR__ . '/../../public' . $current;
                    if (file_exists($filePath)) unlink($filePath);
                    $settingModel->set('site_favicon', '');
                }
                Session::flash('success', 'Favicon eliminado correctamente.');
            } elseif ($type === 'login_logo') {
                $current = $settingModel->get('login_logo');
                if ($current) {
                    $filePath = __DIR__ . '/../../public' . $current;
                    if (file_exists($filePath)) unlink($filePath);
                    $settingModel->set('login_logo', '');
                }
                Session::flash('success', 'Logo de login eliminado correctamente.');
            }
            
            $this->safeRedirectBack('/admin/settings/brand');
            return;
        }
    }
}
