<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Models\Product;
use App\Core\Session;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $model = new Product();
        $search = $this->request->get('search') ?? '';
        $page = max(1, (int)($this->request->get('page') ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $result = $model->paginateWithSearch($search, $limit, $offset);
        $products = $result['data'];
        $total = $result['total'];
        $totalPages = ceil($total / $limit);
        
        return $this->render('admin/products/index', [
            'title' => 'Productos',
            'products' => $products,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        $categoryModel = new \App\Models\Category();
        $brandModel = new \App\Models\Brand();
        return $this->render('admin/products/create', [
            'title' => 'Nuevo Producto',
            'categories' => $categoryModel->all(),
            'brands' => $brandModel->allActive()
        ]);
    }

    public function store()
    {
        $name = trim($this->request->post('name', ''));
        if (empty($name) || mb_strlen($name) > 255) {
            Session::flash('error', 'El nombre del producto es obligatorio y no puede superar 255 caracteres.');
            return $this->redirect('/admin/products/create');
        }

        $price = filter_var($this->request->post('price', ''), FILTER_VALIDATE_FLOAT);
        if ($price === false || $price < 0) {
            Session::flash('error', 'El precio debe ser un número positivo.');
            return $this->redirect('/admin/products/create');
        }

        $sku = trim($this->request->post('sku', ''));
        if (mb_strlen($sku) > 100) {
            Session::flash('error', 'El SKU no puede superar los 100 caracteres.');
            return $this->redirect('/admin/products/create');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $category_id = (int)$this->request->post('category_id', 0) ?: null;
        $brand_id = (int)$this->request->post('brand_id', 0) ?: null;
        $short_description = mb_substr(trim($this->request->post('short_description', '')), 0, 500);
        $full_description = mb_substr(trim($this->request->post('full_description', '')), 0, 10000);
        $is_active = $this->request->post('is_active') ? 1 : 0;
        $has_tax = $this->request->post('has_tax') ? 1 : 0;

        $model = new Product();

        $productId = $model->create([
            'category_id' => $category_id,
            'brand_id' => $brand_id,
            'name' => $name,
            'slug' => $slug,
            'sku' => $sku,
            'price' => $price,
            'short_description' => $short_description,
            'full_description' => $full_description,
            'is_active' => $is_active,
            'has_tax' => $has_tax
        ]);

        if ($productId) {
            $this->handleImageUploads($productId);
        }

        Session::flash('success', 'Producto creado correctamente.');
        $this->redirect('/admin/products');
    }

    public function edit(string $id)
    {
        $model = new Product();
        $product = $model->find($id);

        if (!$product) {
            Session::flash('error', 'Producto no encontrado.');
            $this->redirect('/admin/products');
            return;
        }

        $categoryModel = new \App\Models\Category();
        $imageModel = new \App\Models\ProductImage();
        $brandModel = new \App\Models\Brand();
        
        return $this->render('admin/products/create', [
            'title' => 'Editar Producto',
            'product' => $product,
            'categories' => $categoryModel->all(),
            'brands' => $brandModel->allActive(),
            'images' => $imageModel->getByProductId($id)
        ]);
    }

    public function update(string $id)
    {
        $name = trim($this->request->post('name', ''));
        if (empty($name) || mb_strlen($name) > 255) {
            Session::flash('error', 'El nombre del producto es obligatorio y no puede superar 255 caracteres.');
            return $this->redirect('/admin/products/' . $id . '/edit');
        }

        $price = filter_var($this->request->post('price', ''), FILTER_VALIDATE_FLOAT);
        if ($price === false || $price < 0) {
            Session::flash('error', 'El precio debe ser un número positivo.');
            return $this->redirect('/admin/products/' . $id . '/edit');
        }

        $sku = trim($this->request->post('sku', ''));
        if (mb_strlen($sku) > 100) {
            Session::flash('error', 'El SKU no puede superar los 100 caracteres.');
            return $this->redirect('/admin/products/' . $id . '/edit');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $category_id = (int)$this->request->post('category_id', 0) ?: null;
        $brand_id = (int)$this->request->post('brand_id', 0) ?: null;
        $short_description = mb_substr(trim($this->request->post('short_description', '')), 0, 500);
        $full_description = mb_substr(trim($this->request->post('full_description', '')), 0, 10000);
        $is_active = $this->request->post('is_active') ? 1 : 0;
        $has_tax = $this->request->post('has_tax') ? 1 : 0;

        $model = new Product();
        $model->update($id, [
            'category_id' => $category_id,
            'brand_id' => $brand_id,
            'name' => $name,
            'slug' => $slug,
            'sku' => $sku,
            'price' => $price,
            'short_description' => $short_description,
            'full_description' => $full_description,
            'is_active' => $is_active,
            'has_tax' => $has_tax
        ]);

        $this->handleImageUploads($id);

        Session::flash('success', 'Producto actualizado correctamente.');
        $this->redirect('/admin/products');
    }

    public function delete(string $id)
    {
        $model = new Product();
        $model->delete($id);

        Session::flash('success', 'Producto eliminado.');
        $this->redirect('/admin/products');
    }

    public function deleteImage(string $imageId)
    {
        $imageModel = new \App\Models\ProductImage();
        $image = $imageModel->find($imageId);
        
        if ($image) {
            $filePath = __DIR__ . '/../../public' . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $imageModel->delete($imageId);
            Session::flash('success', 'Imagen eliminada correctamente.');
        }

        $this->safeRedirectBack('/admin/products');
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

    private function handleImageUploads($productId)
    {
        $imageModel = new \App\Models\ProductImage();
        $existingImages = $imageModel->getByProductId($productId);
        $currentCount = count($existingImages);

        $uploadDir = __DIR__ . '/../../public/uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (isset($_FILES['images']) && is_array($_FILES['images']['tmp_name'])) {
            $files = $_FILES['images'];
            $maxSize = 2 * 1024 * 1024; // 2MB

            for ($i = 0; $i < count($files['tmp_name']); $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                    continue;
                }

                if ($currentCount >= 5) {
                    Session::flash('error', 'Se ha alcanzado el límite de 5 imágenes.');
                    break;
                }

                if ($files['size'][$i] > $maxSize) {
                    continue;
                }

                // Validar con magic bytes reales, no con el Content-Type del cliente
                if (!$this->isValidImageFile($files['tmp_name'][$i], $files['name'][$i])) {
                    continue;
                }

                // Extensión derivada del MIME real detectado por finfo
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $realMime = $finfo->file($files['tmp_name'][$i]);
                $safeExtMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                $extension = $safeExtMap[$realMime];

                $originalName = pathinfo($files['name'][$i], PATHINFO_FILENAME);
                $safeName = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $originalName), '-'));
                if (empty($safeName)) {
                    $safeName = 'producto';
                }

                // Nombre con entropía criptográfica para evitar colisiones y enumeración
                $filename = $safeName . '-' . bin2hex(random_bytes(5)) . '.' . $extension;
                $targetPath = $uploadDir . $filename;

                if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                    $imageModel->create([
                        'product_id' => $productId,
                        'image_path' => '/uploads/products/' . $filename,
                        'sort_order' => 0,
                        'is_main' => ($currentCount === 0) ? 1 : 0
                    ]);
                    $currentCount++;
                }
            }
        }
    }

    private function isValidImageFile(string $tmpPath, string $originalName): bool
    {
        // 1. Whitelist de MIME reales mediante magic bytes (no cabecera HTTP)
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $realMime = $finfo->file($tmpPath);
        if (!in_array($realMime, $allowedMimes, true)) {
            return false;
        }

        // 2. Whitelist de extensión (contra doble extensión: shell.php.jpg)
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExts, true)) {
            return false;
        }

        // 3. Verificar que los bytes forman una imagen parseable
        if (getimagesize($tmpPath) === false) {
            return false;
        }

        return true;
    }
}
