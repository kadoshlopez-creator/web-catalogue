<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Setting extends Model
{
    protected string $table = 'settings';

    /**
     * Get a setting value by key.
     * Parses JSON automatically if applicable.
     */
    public function get(string $key, $default = null)
    {
        $sql = "SELECT setting_value FROM {$this->table} WHERE setting_key = :key";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':key', $key, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetchColumn();
        
        if ($result === false) {
            return $default;
        }

        $decoded = json_decode($result, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $result;
    }

    /**
     * Set a setting value by key.
     * Encodes arrays to JSON automatically.
     */
    public function set(string $key, $value): bool
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        $sql = "INSERT INTO {$this->table} (setting_key, setting_value) 
                VALUES (:key, :value) 
                ON DUPLICATE KEY UPDATE setting_value = :value_update";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':key', $key, PDO::PARAM_STR);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        $stmt->bindValue(':value_update', $value, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
