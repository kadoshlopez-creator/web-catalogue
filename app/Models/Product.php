<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Product extends Model
{
    protected string $table = 'products';

    public function allWithCategory(): array
    {
        $sql = "SELECT p.*, c.name as category_name, pi.image_path as main_image 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                WHERE p.deleted_at IS NULL
                ORDER BY p.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginateWithSearch(string $search, int $limit, int $offset): array
    {
        $params = [];
        $where = "WHERE p.deleted_at IS NULL";
        
        if (!empty($search)) {
            $where .= " AND (p.name LIKE :search OR p.slug LIKE :search OR c.name LIKE :search)";
            $params[':search'] = "%{$search}%";
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} p LEFT JOIN categories c ON p.category_id = c.id {$where}";
        $stmtCount = $this->db->prepare($countSql);
        foreach ($params as $key => $val) {
            $stmtCount->bindValue($key, $val);
        }
        $stmtCount->execute();
        $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Get records
        $sql = "SELECT p.*, c.name as category_name, pi.image_path as main_image 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                {$where}
                ORDER BY p.id DESC LIMIT :limit OFFSET :offset";
                
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'total' => $total,
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
    }

    public function getFeatured(int $limit = 4): array
    {
        $sql = "SELECT p.*, c.name as category_name, pi.image_path as main_image 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                WHERE p.deleted_at IS NULL AND p.is_active = 1 AND p.is_featured = 1
                ORDER BY p.id DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatest(int $limit = 8): array
    {
        $sql = "SELECT p.*, c.name as category_name, pi.image_path as main_image 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                WHERE p.deleted_at IS NULL AND p.is_active = 1
                ORDER BY p.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findBySlug(string $slug)
    {
        $sql = "SELECT p.*, c.name as category_name, pi.image_path as main_image 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                WHERE p.deleted_at IS NULL AND p.is_active = 1 AND p.slug = :slug";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByCategorySlug(string $slug, int $limit = 20): array
    {
        $sql = "SELECT p.*, c.name as category_name, pi.image_path as main_image 
                FROM {$this->table} p 
                INNER JOIN categories c ON p.category_id = c.id 
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                WHERE p.deleted_at IS NULL AND p.is_active = 1 AND c.slug = :slug AND c.is_active = 1
                ORDER BY p.id DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchCatalog(string $query = '', array $filters = [], int $limit = 50): array
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name, pi.image_path as main_image 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                WHERE p.deleted_at IS NULL AND p.is_active = 1";
        
        $params = [];

        if (!empty($query)) {
            $sql .= " AND (p.name LIKE :query OR p.short_description LIKE :query OR p.sku LIKE :query)";
            $params[':query'] = '%' . $query . '%';
        }

        if (!empty($filters['category'])) {
            $sql .= " AND c.slug = :category AND c.is_active = 1";
            $params[':category'] = $filters['category'];
        }

        if (!empty($filters['brand'])) {
            $sql .= " AND b.slug = :brand AND b.is_active = 1";
            $params[':brand'] = $filters['brand'];
        }

        if (!empty($filters['trend'])) {
            if ($filters['trend'] === 'oferta') {
                $sql .= " AND (
                    p.label = 'oferta' 
                    OR EXISTS (
                        SELECT 1 FROM offers o 
                        JOIN offer_targets ot ON o.id = ot.offer_id 
                        WHERE o.status = 'active' 
                          AND (o.start_date IS NULL OR o.start_date <= NOW())
                          AND (o.end_date IS NULL OR o.end_date >= NOW())
                          AND (
                              (ot.target_type = 'global') OR 
                              (ot.target_type = 'product' AND ot.target_id = p.id) OR
                              (ot.target_type = 'category' AND ot.target_id = p.category_id) OR
                              (ot.target_type = 'brand' AND ot.target_id = p.brand_id)
                          )
                    )
                )";
            } elseif ($filters['trend'] === 'destacado') {
                $sql .= " AND (p.is_featured = 1 OR p.label = 'destacado')";
            } elseif ($filters['trend'] === 'nuevo') {
                $sql .= " AND p.label = 'nuevo'";
            } else {
                $sql .= " AND p.label = :trend_label";
                $params[':trend_label'] = $filters['trend'];
            }
        }

        $sql .= " ORDER BY p.id DESC LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
