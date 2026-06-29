<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Role extends Model
{
    protected string $table = 'roles';

    public function getPermissions(int $roleId): array
    {
        $role = $this->find($roleId);
        
        if (!$role || empty($role['permissions'])) {
            return [];
        }
        
        return json_decode($role['permissions'], true) ?? [];
    }
}
