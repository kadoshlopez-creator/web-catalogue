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
        $targetIds = $data['target_ids'] ?? [];
        
        // Mantener compatibilidad si envían un solo ID (no array)
        if (!is_array($targetIds) && $targetIds !== null && $targetIds !== '') {
            $targetIds = [$targetIds];
        }
        
        unset($data['target_type'], $data['target_ids'], $data['target_id']);

        $offerId = $this->offerModel->create($data);
        
        if ($offerId) {
            $db = \App\Core\Database::getConnection();
            $stmt = $db->prepare("INSERT INTO offer_targets (offer_id, target_type, target_id) VALUES (?, ?, ?)");
            
            if ($targetType === 'global' || empty($targetIds)) {
                $stmt->execute([$offerId, $targetType, null]);
            } else {
                foreach ($targetIds as $tid) {
                    if (!empty($tid)) {
                        $stmt->execute([$offerId, $targetType, $tid]);
                    }
                }
            }
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
            $targets = $stmt->fetchAll();
            
            if (!empty($targets)) {
                $offer['target_type'] = $targets[0]['target_type'];
                $offer['target_ids'] = array_filter(array_column($targets, 'target_id'));
                // Mantener compatibility
                $offer['target_id'] = !empty($offer['target_ids']) ? $offer['target_ids'][0] : null;
            } else {
                $offer['target_type'] = 'global';
                $offer['target_ids'] = [];
                $offer['target_id'] = null;
            }
        }
        return $offer;
    }

    public function updateOffer(int $id, array $data): bool
    {
        $targetType = $data['target_type'] ?? 'global';
        $targetIds = $data['target_ids'] ?? [];
        
        if (!is_array($targetIds) && $targetIds !== null && $targetIds !== '') {
            $targetIds = [$targetIds];
        }
        
        unset($data['target_type'], $data['target_ids'], $data['target_id']);

        $success = $this->offerModel->update($id, $data);

        if ($success) {
            $db = \App\Core\Database::getConnection();
            // Delete old targets
            $stmt = $db->prepare("DELETE FROM offer_targets WHERE offer_id = ?");
            $stmt->execute([$id]);
            
            // Insert new targets
            $stmt = $db->prepare("INSERT INTO offer_targets (offer_id, target_type, target_id) VALUES (?, ?, ?)");
            if ($targetType === 'global' || empty($targetIds)) {
                $stmt->execute([$id, $targetType, null]);
            } else {
                foreach ($targetIds as $tid) {
                    if (!empty($tid)) {
                        $stmt->execute([$id, $targetType, $tid]);
                    }
                }
            }
        }

        return $success;
    }
}
