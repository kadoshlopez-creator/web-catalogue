<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Setting;

class MenuController extends Controller
{
    private Setting $settingModel;
    
    private array $defaultMenus = [
        'main' => [
            'name' => 'Menú Principal',
            'items' => [
                ['label' => 'Inicio', 'link' => '/'],
                ['label' => 'Catálogo', 'link' => '/catalogo'],
                ['label' => 'Contacto', 'link' => '#contacto']
            ]
        ],
        'footer' => [
            'name' => 'Menú del Pie de Página',
            'items' => [
                ['label' => 'Inicio', 'link' => '/'],
                ['label' => 'Ver Catálogo', 'link' => '/catalogo']
            ]
        ]
    ];

    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new \App\Middleware\AuthMiddleware());
        $this->settingModel = new Setting();
    }

    private function getMenus(): array
    {
        $menus = $this->settingModel->get('navigation_menus');
        if (empty($menus) || !is_array($menus)) {
            $menus = $this->defaultMenus;
            $this->settingModel->set('navigation_menus', $menus);
        }
        return $menus;
    }

    public function index()
    {
        $menus = $this->getMenus();
        return $this->render('admin/menus/index', [
            'title' => 'Menús',
            'menus' => $menus
        ]);
    }

    public function edit(string $id)
    {
        $menus = $this->getMenus();
        
        if (!isset($menus[$id])) {
            $this->redirect('/admin/menus');
        }

        return $this->render('admin/menus/edit', [
            'title' => 'Editar ' . htmlspecialchars($menus[$id]['name']),
            'menu_id' => $id,
            'menu' => $menus[$id]
        ]);
    }

    public function update(string $id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/menus/' . $id . '/edit');
        }

        $menus = $this->getMenus();
        
        if (!isset($menus[$id])) {
            $this->redirect('/admin/menus');
        }

        // Get items from post (usually sent as JSON string due to Alpine JS form structure)
        $itemsRaw = $_POST['items'] ?? '[]';
        $items = json_decode($itemsRaw, true);
        
        if (!is_array($items)) {
            $items = [];
        }

        // Filter and sanitize items
        $cleanItems = [];
        foreach ($items as $item) {
            if (!empty(trim($item['label'] ?? '')) && !empty(trim($item['link'] ?? ''))) {
                $cleanItems[] = [
                    'label' => trim($item['label']),
                    'link' => trim($item['link'])
                ];
            }
        }

        $menus[$id]['items'] = $cleanItems;
        
        if ($this->settingModel->set('navigation_menus', $menus)) {
            \App\Core\Session::set('success', 'Menú actualizado correctamente.');
        } else {
            \App\Core\Session::set('error', 'Error al actualizar el menú.');
        }

        $this->redirect('/admin/menus/' . $id . '/edit');
    }
}
