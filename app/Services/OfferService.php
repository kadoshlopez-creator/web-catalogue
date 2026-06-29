<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Offer;

class OfferService
{
    private Offer $offerModel;

    public function __construct()
    {
        $this->offerModel = new Offer();
    }

    public function getAllOffers(): array
    {
        return $this->offerModel->all();
    }

    public function createOffer(array $data): string|int|bool
    {
        $targetType = $data['target_type'] ?? 'global';
        $targetId = $data['target_id'] ?? null;
        
        unset($data['target_type'], $data['target_id']);

        $offerId = $this->offerModel->create($data);
        
        if ($offerId) {
            $db = \App\Core\Database::getConnection();
            $stmt = $db->prepare("INSERT INTO offer_targets (offer_id, target_type, target_id) VALUES (?, ?, ?)");
            $stmt->execute([$offerId, $targetType, $targetId]);
        }
        
        return $offerId;
    }

    public function getOffer(int $id)
    {
        $offer = $this->offerModel->find($id);
        if ($offer) {
            $db = \App\Core\Database::getConnection();
            $stmt = $db->prepare("SELECT target_type, target_id FROM offer_targets WHERE offer_id = ?");
            $stmt->execute([$id]);
            $target = $stmt->fetch();
            if ($target) {
                $offer['target_type'] = $target['target_type'];
                $offer['target_id'] = $target['target_id'];
            } else {
                $offer['target_type'] = 'global';
                $offer['target_id'] = null;
            }
        }
        return $offer;
    }

    public function updateOffer(int $id, array $data): bool
    {
        $targetType = $data['target_type'] ?? 'global';
        $targetId = $data['target_id'] ?? null;
        
        unset($data['target_type'], $data['target_id']);

        $success = $this->offerModel->update($id, $data);

        if ($success) {
            $db = \App\Core\Database::getConnection();
            // Delete old target
            $stmt = $db->prepare("DELETE FROM offer_targets WHERE offer_id = ?");
            $stmt->execute([$id]);
            
            // Insert new target
            $stmt = $db->prepare("INSERT INTO offer_targets (offer_id, target_type, target_id) VALUES (?, ?, ?)");
            $stmt->execute([$id, $targetType, $targetId]);
        }

        return $success;
    }
}
