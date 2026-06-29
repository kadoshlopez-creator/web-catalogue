<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Banner extends Model
{
    protected string $table = 'banners';
    
    /**
     * Obtiene los banners activos ordenados
     * @return array
     */
    public function getActiveBanners(): array
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY sort_order ASC, id DESC")->fetchAll();
    }

    public function getAllBanners(): array
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY sort_order ASC, id DESC")->fetchAll();
    }
}
