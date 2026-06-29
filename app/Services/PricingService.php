<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class PricingService
{
    /**
     * Calcula el precio final de un producto evaluando todas las ofertas activas
     * Prioridad: Producto > Marca > Categoría > Global
     * 
     * @param array $product Array asociativo con los datos del producto (price, id, category_id, brand_id)
     * @return array Array con el precio final, descuento y datos de la oferta aplicada
     */
    public function calculateFinalPrice(array $product): array
    {
        $basePrice = (float) $product['price'];
        $db = Database::getConnection();
        
        // Buscar todas las ofertas activas vigentes
        $sql = "
            SELECT o.*, ot.target_type, ot.target_id 
            FROM offers o
            JOIN offer_targets ot ON o.id = ot.offer_id
            WHERE o.status = 'active' 
              AND (o.start_date IS NULL OR o.start_date <= NOW())
              AND (o.end_date IS NULL OR o.end_date >= NOW())
        ";
        $stmt = $db->query($sql);
        $activeOffers = $stmt->fetchAll();

        if (empty($activeOffers)) {
            return [
                'base_price' => $basePrice,
                'final_price' => $basePrice,
                'has_discount' => false,
                'discount_amount' => 0,
                'offer_details' => null
            ];
        }

        $appliedOffer = null;
        $highestPriority = 0; // 4: Product, 3: Brand, 2: Category, 1: Global

        foreach ($activeOffers as $offer) {
            $isMatch = false;
            $priority = 0;

            if ($offer['target_type'] === 'product' && $offer['target_id'] == $product['id']) {
                $isMatch = true;
                $priority = 4;
            } elseif ($offer['target_type'] === 'brand' && $offer['target_id'] == $product['brand_id']) {
                $isMatch = true;
                $priority = 3;
            } elseif ($offer['target_type'] === 'category' && $offer['target_id'] == $product['category_id']) {
                $isMatch = true;
                $priority = 2;
            } elseif ($offer['target_type'] === 'global') {
                $isMatch = true;
                $priority = 1;
            }

            if ($isMatch && $priority > $highestPriority) {
                $highestPriority = $priority;
                $appliedOffer = $offer;
            }
        }

        if (!$appliedOffer) {
            return [
                'base_price' => $basePrice,
                'final_price' => $basePrice,
                'has_discount' => false,
                'discount_amount' => 0,
                'offer_details' => null
            ];
        }

        // Calcular el nuevo precio
        $discountAmount = 0;
        $finalPrice = $basePrice;

        if ($appliedOffer['discount_type'] === 'percentage') {
            $discountPercent = (float) $appliedOffer['discount_value'];
            $discountAmount = $basePrice * ($discountPercent / 100);
            $finalPrice = $basePrice - $discountAmount;
        } elseif ($appliedOffer['discount_type'] === 'fixed_amount') {
            $discountAmount = (float) $appliedOffer['discount_value'];
            $finalPrice = $basePrice - $discountAmount;
            if ($finalPrice < 0) $finalPrice = 0; // Prevent negative prices
        }

        return [
            'base_price' => $basePrice,
            'final_price' => round($finalPrice, 2),
            'has_discount' => true,
            'discount_amount' => round($discountAmount, 2),
            'offer_details' => [
                'name' => $appliedOffer['name'],
                'type' => $appliedOffer['discount_type'],
                'value' => $appliedOffer['discount_value']
            ]
        ];
    }
}
