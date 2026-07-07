<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\OfferService;

class OfferController extends Controller
{
    private OfferService $offerService;

    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());
        $this->offerService = new OfferService();
    }

    public function index()
    {
        $offers = $this->offerService->getAllOffers();
        return $this->render('admin/marketing/offers/index', [
            'title' => 'Ofertas',
            'offers' => $offers
        ]);
    }

    public function create()
    {
        $campaignService = new \App\Services\CampaignService();
        $campaigns = $campaignService->getActiveCampaigns();

        $brandModel = new \App\Models\Brand();
        $brands = $brandModel->where('is_active', 1);

        $categoryModel = new \App\Models\Category();
        $categories = $categoryModel->where('is_active', 1);

        $productModel = new \App\Models\Product();
        $products = $productModel->where('is_active', 1);
        
        return $this->render('admin/marketing/offers/create', [
            'title' => 'Nueva Oferta',
            'campaigns' => $campaigns,
            'brands' => $brands,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function store()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'campaign_id' => !empty($_POST['campaign_id']) ? $_POST['campaign_id'] : null,
            'discount_type' => $_POST['discount_type'] ?? 'percentage',
            'discount_value' => $_POST['discount_value'] ?? 0,
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'status' => $_POST['status'] ?? 'active',
            'target_type' => $_POST['target_type'] ?? 'global',
            'target_ids' => !empty($_POST['target_ids']) ? $_POST['target_ids'] : []
        ];
        
        $this->offerService->createOffer($data);
        header('Location: /admin/marketing/offers');
        exit;
    }

    public function edit(int $id)
    {
        $offer = $this->offerService->getOffer($id);
        if (!$offer) {
            header('Location: /admin/marketing/offers');
            exit;
        }

        $campaignService = new \App\Services\CampaignService();
        $campaigns = $campaignService->getActiveCampaigns();

        $brandModel = new \App\Models\Brand();
        $brands = $brandModel->where('is_active', 1);

        $categoryModel = new \App\Models\Category();
        $categories = $categoryModel->where('is_active', 1);

        $productModel = new \App\Models\Product();
        $products = $productModel->where('is_active', 1);

        return $this->render('admin/marketing/offers/edit', [
            'title' => 'Editar Oferta',
            'offer' => $offer,
            'campaigns' => $campaigns,
            'brands' => $brands,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function update(int $id)
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'campaign_id' => !empty($_POST['campaign_id']) ? $_POST['campaign_id'] : null,
            'discount_type' => $_POST['discount_type'] ?? 'percentage',
            'discount_value' => $_POST['discount_value'] ?? 0,
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'status' => $_POST['status'] ?? 'active',
            'target_type' => $_POST['target_type'] ?? 'global',
            'target_ids' => !empty($_POST['target_ids']) ? $_POST['target_ids'] : []
        ];
        
        $this->offerService->updateOffer($id, $data);
        header('Location: /admin/marketing/offers');
        exit;
    }
}
