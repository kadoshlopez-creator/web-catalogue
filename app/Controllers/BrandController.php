<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Models\Brand;
use App\Core\Session;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $model = new Brand();
        $search = strtolower($this->request->get('search') ?? '');
        $page = max(1, (int)($this->request->get('page') ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $result = $model->paginateWithSearch($search, $limit, $offset);
        $brands = $result['data'];
        $total = $result['total'];
        $totalPages = ceil($total / $limit);
        
        return $this->render('admin/brands/index', [
            'title' => 'Marcas',
            'brands' => $brands,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        return $this->render('admin/brands/create', [
            'title' => 'Crear Marca',
            'brand' => null,
            'isEdit' => false
        ]);
    }

    public function store()
    {
        $data = [
            'name' => $this->request->post('name'),
            'slug' => $this->request->post('slug'),
            'is_active' => $this->request->post('is_active', 0) ? 1 : 0
        ];

        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $data['logo'] = $this->uploadImage($_FILES['logo']);
        }

        $model = new Brand();
        if ($model->create($data)) {
            Session::setFlash('success', 'Marca creada exitosamente.');
        } else {
            Session::setFlash('error', 'Error al crear la marca.');
        }

        $this->redirect('/admin/brands');
    }

    public function storeAjax()
    {
        $name = trim($this->request->post('name') ?? '');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        if (empty($name)) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El nombre de la marca es obligatorio']);
            exit;
        }

        $data = [
            'name' => $name,
            'slug' => $slug,
            'is_active' => 1
        ];

        $model = new Brand();
        
        header('Content-Type: application/json');
        
        try {
            $brandId = $model->create($data);
            
            if ($brandId) {
                echo json_encode([
                    'success' => true, 
                    'brand' => [
                        'id' => $brandId,
                        'name' => $name
                    ]
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al crear la marca']);
            }
        } catch (\Throwable $e) {
            http_response_code(400);
            if ($e instanceof \PDOException && (strpos($e->getMessage(), 'Duplicate entry') !== false || strpos($e->getMessage(), 'UNIQUE') !== false)) {
                echo json_encode(['success' => false, 'message' => 'Ya existe una marca con este nombre']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
        }
        exit;
    }

    public function edit($id)
    {
        $model = new Brand();
        $brand = $model->find($id);

        if (!$brand) {
            Session::setFlash('error', 'Marca no encontrada.');
            $this->redirect('/admin/brands');
        }

        return $this->render('admin/brands/edit', [
            'title' => 'Editar Marca',
            'brand' => $brand,
            'isEdit' => true
        ]);
    }

    public function update($id)
    {
        $model = new Brand();
        $brand = $model->find($id);

        if (!$brand) {
            Session::setFlash('error', 'Marca no encontrada.');
            $this->redirect('/admin/brands');
        }

        $data = [
            'name' => $this->request->post('name'),
            'slug' => $this->request->post('slug'),
            'is_active' => $this->request->post('is_active', 0) ? 1 : 0
        ];

        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $data['logo'] = $this->uploadImage($_FILES['logo']);
        }

        if ($model->update($id, $data)) {
            Session::setFlash('success', 'Marca actualizada exitosamente.');
        } else {
            Session::setFlash('error', 'Error al actualizar la marca.');
        }

        $this->redirect('/admin/brands');
    }

    public function delete($id)
    {
        if (!Session::validateCsrf()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/admin/brands');
        }

        $model = new Brand();
        
        if ($model->delete($id)) {
            Session::setFlash('success', 'Marca eliminada exitosamente.');
        } else {
            Session::setFlash('error', 'Error al eliminar la marca.');
        }

        $this->redirect('/admin/brands');
    }

    private function uploadImage($file)
    {
        $uploadDir = __DIR__ . '/../../../public/uploads/brands/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return '/uploads/brands/' . $fileName;
        }

        return null;
    }
}
