<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\CampaignService;

class CampaignController extends Controller
{
    private CampaignService $campaignService;

    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());
        $this->campaignService = new CampaignService();
    }

    public function index()
    {
        $campaigns = $this->campaignService->getAllCampaigns();
        return $this->render('admin/marketing/campaigns/index', [
            'title' => 'Campañas',
            'campaigns' => $campaigns
        ]);
    }

    public function create()
    {
        return $this->render('admin/marketing/campaigns/create', [
            'title' => 'Nueva Campaña'
        ]);
    }

    public function store()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'status' => $_POST['status'] ?? 'draft'
        ];
        
        $this->campaignService->createCampaign($data);
        header('Location: /admin/marketing/campaigns');
        exit;
    }

    public function edit(int $id)
    {
        $campaign = $this->campaignService->getCampaign($id);
        if (!$campaign) {
            header('Location: /admin/marketing/campaigns');
            exit;
        }

        return $this->render('admin/marketing/campaigns/edit', [
            'title' => 'Editar Campaña',
            'campaign' => $campaign
        ]);
    }

    public function update(int $id)
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'status' => $_POST['status'] ?? 'draft'
        ];
        
        $this->campaignService->updateCampaign($id, $data);
        header('Location: /admin/marketing/campaigns');
        exit;
    }
}
