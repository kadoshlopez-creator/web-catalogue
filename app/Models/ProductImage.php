<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class ProductImage extends Model
{
    protected string $table = 'product_images';

    public function getByProductId(int $productId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = :product_id ORDER BY sort_order ASC, id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
