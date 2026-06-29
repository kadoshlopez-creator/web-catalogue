<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Campaign;

class CampaignService
{
    private Campaign $campaignModel;

    public function __construct()
    {
        $this->campaignModel = new Campaign();
    }

    public function getAllCampaigns(): array
    {
        return $this->campaignModel->all();
    }

    public function getActiveCampaigns(): array
    {
        return $this->campaignModel->where('status', 'active');
    }

    public function createCampaign(array $data): string|int|bool
    {
        // Simple validation or DTO mapping would go here
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
        
        // Ensure slug is unique
        $existing = $this->campaignModel->where('slug', $slug);
        if (!empty($existing)) {
            $slug .= '-' . time();
        }
        
        $data['slug'] = $slug;
        return $this->campaignModel->create($data);
    }

    public function getCampaign(int $id)
    {
        return $this->campaignModel->find($id);
    }

    public function updateCampaign(int $id, array $data): bool
    {
        // Simple validation or DTO mapping would go here
        if (isset($data['name']) && empty($data['slug'])) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
            $existing = $this->campaignModel->where('slug', $slug);
            if (!empty($existing) && $existing[0]['id'] != $id) {
                $slug .= '-' . time();
            }
            $data['slug'] = $slug;
        }
        
        return $this->campaignModel->update($id, $data);
    }
}
