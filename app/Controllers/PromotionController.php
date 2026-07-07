<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\PromotionService;

class PromotionController extends Controller
{
    private PromotionService $promotionService;

    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());
        $this->promotionService = new PromotionService();
    }

    public function index()
    {
        $promotions = $this->promotionService->getAllPromotions();
        return $this->render('admin/marketing/promotions/index', [
            'title' => 'Promociones',
            'promotions' => $promotions
        ]);
    }

    public function create()
    {
        $campaignService = new \App\Services\CampaignService();
        $campaigns = $campaignService->getActiveCampaigns();
        
        return $this->render('admin/marketing/promotions/create', [
            'title' => 'Nueva Promoción',
            'campaigns' => $campaigns
        ]);
    }

    public function store()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'campaign_id' => !empty($_POST['campaign_id']) ? $_POST['campaign_id'] : null,
            'type' => $_POST['type'] ?? 'free_shipping',
            'status' => $_POST['status'] ?? 'active',
            'show_in_menu' => isset($_POST['show_in_menu']) && $_POST['show_in_menu'] == '1' ? 1 : 0
        ];
        
        $this->promotionService->createPromotion($data);
        header('Location: /admin/marketing/promotions');
        exit;
    }

    public function edit(int $id)
    {
        $promotion = $this->promotionService->getPromotion($id);
        if (!$promotion) {
            header('Location: /admin/marketing/promotions');
            exit;
        }

        $campaignService = new \App\Services\CampaignService();
        $campaigns = $campaignService->getActiveCampaigns();
        
        return $this->render('admin/marketing/promotions/edit', [
            'title' => 'Editar Promoción',
            'promotion' => $promotion,
            'campaigns' => $campaigns
        ]);
    }

    public function update(int $id)
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'campaign_id' => !empty($_POST['campaign_id']) ? $_POST['campaign_id'] : null,
            'type' => $_POST['type'] ?? 'free_shipping',
            'status' => $_POST['status'] ?? 'active',
            'show_in_menu' => isset($_POST['show_in_menu']) && $_POST['show_in_menu'] == '1' ? 1 : 0
        ];
        
        $this->promotionService->updatePromotion($id, $data);
        header('Location: /admin/marketing/promotions');
        exit;
    }

    public function delete(int $id)
    {
        $this->promotionService->deletePromotion($id);
        header('Location: /admin/marketing/promotions');
        exit;
    }
}
