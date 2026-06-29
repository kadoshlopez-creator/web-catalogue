<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email AND deleted_at IS NULL");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getRole(int $roleId)
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = :id");
        $stmt->execute(['id' => $roleId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function incrementFailedAttempts(int $userId)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET failed_attempts = failed_attempts + 1 WHERE id = :id");
        $stmt->execute(['id' => $userId]);
    }

    public function lockUser(int $userId)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_locked = 1 WHERE id = :id");
        $stmt->execute(['id' => $userId]);
    }

    public function resetFailedAttempts(int $userId)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET failed_attempts = 0 WHERE id = :id");
        $stmt->execute(['id' => $userId]);
    }
}
