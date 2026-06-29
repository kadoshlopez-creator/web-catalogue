<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Brand extends Model
{
    protected string $table = 'brands';

    public function allActive(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function paginateWithSearch(string $search, int $limit, int $offset): array
    {
        $params = [];
        $where = "WHERE 1=1";
        
        if (!empty($search)) {
            $where .= " AND (name LIKE :search OR slug LIKE :search)";
            $params[':search'] = "%{$search}%";
        }
        
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$where}";
        $stmtCount = $this->db->prepare($countSql);
        foreach ($params as $key => $val) {
            $stmtCount->bindValue($key, $val);
        }
        $stmtCount->execute();
        $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT * FROM {$this->table} {$where} ORDER BY name ASC LIMIT :limit OFFSET :offset";
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
}
