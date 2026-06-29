<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Models\Category;
use App\Core\Session;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $model = new Category();
        $allCategories = $model->getFlatTree();
        
        $search = strtolower($this->request->get('search') ?? '');
        $page = max(1, (int)($this->request->get('page') ?? 1));
        $limit = 20;
        
        if (!empty($search)) {
            $allCategories = array_filter($allCategories, function($cat) use ($search) {
                return strpos(strtolower($cat['name']), $search) !== false || 
                       strpos(strtolower($cat['slug']), $search) !== false;
            });
            // Re-index array after filter
            $allCategories = array_values($allCategories);
        }
        
        $total = count($allCategories);
        $totalPages = ceil($total / $limit);
        $offset = ($page - 1) * $limit;
        
        $categories = array_slice($allCategories, $offset, $limit);
        
        return $this->render('admin/categories/index', [
            'title' => 'Categorías',
            'categories' => $categories,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        $model = new Category();
        $parents = $model->getFlatTree($model->getEligibleParents());

        return $this->render('admin/categories/create', [
            'title' => 'Nueva Categoría',
            'parents' => $parents
        ]);
    }

    public function store()
    {
        $name = trim($this->request->post('name', ''));
        if (empty($name) || mb_strlen($name) > 255) {
            Session::flash('error', 'El nombre de la categoría es obligatorio y no puede superar 255 caracteres.');
            return $this->redirect('/admin/categories/create');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $description = mb_substr(trim($this->request->post('description', '')), 0, 1000);
        $is_active = $this->request->post('is_active') ? 1 : 0;

        $parent_id = $this->request->post('parent_id') ?: null;
        $level = 1;
        
        $model = new Category();
        
        if ($parent_id) {
            $parent = $model->find($parent_id);
            if ($parent) {
                $level = $parent['level'] + 1;
                if ($level > 3) {
                    Session::flash('error', 'No se pueden crear categorías de más de 3 niveles de profundidad.');
                    return $this->redirect('/admin/categories/create');
                }
            }
        }

        $model->create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_active' => $is_active,
            'parent_id' => $parent_id,
            'level' => $level
        ]);

        Session::flash('success', 'Categoría creada correctamente.');
        return $this->redirect('/admin/categories');
    }

    public function edit(string $id)
    {
        $model = new Category();
        $category = $model->find($id);

        if (!$category) {
            Session::flash('error', 'Categoría no encontrada.');
            return $this->redirect('/admin/categories');
        }

        $parents = $model->getFlatTree($model->getEligibleParents($category['id']));

        return $this->render('admin/categories/edit', [
            'title' => 'Editar Categoría',
            'category' => $category,
            'parents' => $parents
        ]);
    }

    public function update(string $id)
    {
        $name = trim($this->request->post('name', ''));
        if (empty($name) || mb_strlen($name) > 255) {
            Session::flash('error', 'El nombre de la categoría es obligatorio y no puede superar 255 caracteres.');
            return $this->redirect('/admin/categories/' . $id . '/edit');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $description = mb_substr(trim($this->request->post('description', '')), 0, 1000);
        $is_active = $this->request->post('is_active') ? 1 : 0;

        $parent_id = $this->request->post('parent_id') ?: null;
        $level = 1;

        $model = new Category();
        
        if ($parent_id) {
            $parent = $model->find($parent_id);
            if ($parent) {
                $level = $parent['level'] + 1;
                if ($level > 3) {
                    Session::flash('error', 'No se pueden crear categorías de más de 3 niveles de profundidad.');
                    return $this->redirect('/admin/categories/edit/' . $id);
                }
            }
        }

        $model->update($id, [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_active' => $is_active,
            'parent_id' => $parent_id,
            'level' => $level
        ]);

        Session::flash('success', 'Categoría actualizada correctamente.');
        return $this->redirect('/admin/categories');
    }

    public function delete(string $id)
    {
        $model = new Category();
        $model->delete($id);

        Session::flash('success', 'Categoría eliminada.');
        $this->redirect('/admin/categories');
    }
}
