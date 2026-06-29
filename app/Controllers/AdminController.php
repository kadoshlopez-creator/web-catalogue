<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Services\DashboardService;
use App\Services\SystemHealthService;
use App\Services\SeoHealthService;
use App\Services\TaskService;
use App\Models\Product;
use App\Models\Category;

class AdminController extends Controller
{
    private DashboardService $dashboardService;
    private SystemHealthService $systemHealthService;
    private SeoHealthService $seoHealthService;
    private TaskService $taskService;

    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());
        
        $this->dashboardService = new DashboardService();
        $this->systemHealthService = new SystemHealthService();
        $this->seoHealthService = new SeoHealthService();
        $this->taskService = new TaskService();
    }

    public function index()
    {
        $userId = Session::get('user_id');
        $preferences = $this->dashboardService->getUserPreferences($userId ?? 0);

        return $this->render('admin/dashboard', [
            'title' => 'Dashboard',
            'preferences' => $preferences
        ]);
    }

    public function getMetrics()
    {
        header('Content-Type: application/json');
        $productModel = new Product();
        $categoryModel = new Category();
        
        echo json_encode([
            'totalProducts' => count($productModel->all()),
            'totalCategories' => count($categoryModel->all()),
            'visits' => rand(100, 500), // Mock data for now
            'seoScore' => $this->seoHealthService->getOverallHealth()['score']
        ]);
        exit;
    }

    public function getSystemHealth()
    {
        header('Content-Type: application/json');
        echo json_encode($this->systemHealthService->getSystemHealth());
        exit;
    }

    public function getTasks()
    {
        header('Content-Type: application/json');
        echo json_encode($this->taskService->getPendingTasks());
        exit;
    }
    
    public function savePreferences()
    {
        header('Content-Type: application/json');
        $userId = Session::get('user_id');
        
        // Limitar tamaño del payload para prevenir DoS por memoria
        $rawInput = file_get_contents('php://input', false, null, 0, 65536); // 64KB máximo
        if (strlen($rawInput) >= 65536) {
            http_response_code(413);
            echo json_encode(['success' => false, 'message' => 'Payload demasiado grande']);
            exit;
        }

        $input = json_decode($rawInput, true, 8); // depth máximo de 8 niveles
        if ($userId && is_array($input)) {
            $this->dashboardService->saveUserPreferences($userId, $input);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
        }
        exit;
    }
}
