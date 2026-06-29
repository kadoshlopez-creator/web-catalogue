<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;
use App\Core\Database;
use App\Repositories\Interfaces\SlugHistoryRepositoryInterface;

/**
 * Class ProductSlugHistoryRepository
 * 
 * Manages the persistence of slug history to handle 301 redirects for products.
 * 
 * @package App\Repositories
 */
class ProductSlugHistoryRepository implements SlugHistoryRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Store a new slug history record.
     *
     * @param int $entityId
     * @param string $oldSlug
     * @param string $newSlug
     * @param int $redirectType
     * @return bool
     */
    public function recordHistory(int $entityId, string $oldSlug, string $newSlug, int $redirectType = 301): bool
    {
        $updateSql = "UPDATE product_slug_history SET new_slug = :newSlug WHERE new_slug = :oldSlug AND product_id = :entityId";
        $updateStmt = $this->db->prepare($updateSql);
        $updateStmt->execute([
            'newSlug' => $newSlug,
            'oldSlug' => $oldSlug,
            'entityId' => $entityId
        ]);

        $sql = "INSERT INTO product_slug_history (product_id, old_slug, new_slug, redirect_type) 
                VALUES (:entityId, :oldSlug, :newSlug, :redirectType)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'entityId' => $entityId,
            'oldSlug' => $oldSlug,
            'newSlug' => $newSlug,
            'redirectType' => $redirectType
        ]);
    }

    /**
     * Find the new slug for an old slug, if it exists.
     *
     * @param string $oldSlug
     * @return string|null
     */
    public function findNewSlug(string $oldSlug): ?string
    {
        $sql = "SELECT new_slug FROM product_slug_history WHERE old_slug = :oldSlug ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['oldSlug' => $oldSlug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result['new_slug'] : null;
    }
}
