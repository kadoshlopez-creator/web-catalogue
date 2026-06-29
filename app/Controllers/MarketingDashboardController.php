<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\CampaignService;
use App\Services\PromotionService;
use App\Services\OfferService;
use App\Services\BannerService;

class MarketingDashboardController extends Controller
{
    private CampaignService $campaignService;
    private PromotionService $promotionService;
    private OfferService $offerService;
    private BannerService $bannerService;

    public function __construct()
    {
        $this->layout = 'admin';
        $this->registerMiddleware(new AuthMiddleware());
        
        $this->campaignService = new CampaignService();
        $this->promotionService = new PromotionService();
        $this->offerService = new OfferService();
        $this->bannerService = new BannerService();
    }

    public function index()
    {
        // Gather stats
        $activeCampaigns = count($this->campaignService->getActiveCampaigns());
        // For simplicity, we assume we count all for now as mock data
        $activePromotions = count($this->promotionService->getAllPromotions());
        $activeOffers = count($this->offerService->getAllOffers());
        $activeBanners = count($this->bannerService->getAllBanners());

        return $this->render('admin/marketing/dashboard', [
            'title' => 'Marketing Dashboard',
            'activeCampaigns' => $activeCampaigns,
            'activePromotions' => $activePromotions,
            'activeOffers' => $activeOffers,
            'activeBanners' => $activeBanners
        ]);
    }
}
