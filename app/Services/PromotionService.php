<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Promotion;

class PromotionService
{
    private Promotion $promotionModel;

    public function __construct()
    {
        $this->promotionModel = new Promotion();
    }

    public function getAllPromotions(): array
    {
        return $this->promotionModel->all();
    }

    public function createPromotion(array $data): string|int|bool
    {
        return $this->promotionModel->create($data);
    }

    public function getPromotion(int $id)
    {
        return $this->promotionModel->find($id);
    }

    public function updatePromotion(int $id, array $data): bool
    {
        return $this->promotionModel->update($id, $data);
    }

    public function deletePromotion(int $id): bool
    {
        return $this->promotionModel->delete($id);
    }
}
