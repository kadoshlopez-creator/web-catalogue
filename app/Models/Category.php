<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    protected string $table = 'categories';

    /**
     * Get all categories formatted as a flat tree with depth indication
     */
    public function getTree()
    {
        // First get all categories ordered by name
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY name ASC");
        $stmt->execute();
        $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Build a hierarchical tree
        return $this->buildTree($categories);
    }

    /**
     * Get categories that can act as parents (level 1 and 2)
     */
    public function getEligibleParents(?int $excludeId = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE level IN (1, 2)";
        $params = [];
        
        if ($excludeId) {
            // Un padre no puede ser él mismo ni sus hijos, 
            // pero para simplificar, al menos excluimos a sí mismo
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        $sql .= " ORDER BY name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $parents = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $this->buildTree($parents);
    }

    /**
     * Helper to build a flat list with children nested underneath their parents
     */
    private function buildTree(array $elements, $parentId = null)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                } else {
                    $element['children'] = [];
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
    
    /**
     * Get a flattened tree for display in selects or tables
     */
    public function getFlatTree($tree = null, $prefix = '')
    {
        if ($tree === null) {
            $tree = $this->getTree();
        }
        
        $flat = [];
        foreach ($tree as $node) {
            $node['display_name'] = $prefix . $node['name'];
            $children = $node['children'] ?? [];
            unset($node['children']);
            $flat[] = $node;
            
            if (!empty($children)) {
                $flat = array_merge($flat, $this->getFlatTree($children, $prefix . '— '));
            }
        }
        
        return $flat;
    }
}
