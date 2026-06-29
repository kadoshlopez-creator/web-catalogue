<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;
use App\Core\Database;
use App\DTOs\SeoDTO;
use App\Repositories\Interfaces\SeoAwareRepositoryInterface;

/**
 * Class CategoryRepository
 * 
 * Data access layer for Categories, following the Repository Pattern.
 * Isolates the database PDO logic from Services and Controllers.
 * 
 * @package App\Repositories
 */
class CategoryRepository implements SeoAwareRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Find a category by its ID.
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result !== false ? $result : null;
    }

    /**
     * Find a category by its slug.
     *
     * @param string $slug
     * @param int|null $excludeId ID to exclude from search (for unique validation during update)
     * @return array|null
     */
    public function findBySlug(string $slug, ?int $excludeId = null): ?array
    {
        $sql = "SELECT * FROM categories WHERE slug = :slug";
        $params = ['slug' => $slug];
        
        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        
        $sql .= " LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result !== false ? $result : null;
    }

    /**
     * Update the SEO parameters of a category.
     *
     * @param int $id
     * @param SeoDTO $dto
     * @return bool
     */
    public function updateSeo(int $id, SeoDTO $dto): bool
    {
        $sql = "UPDATE categories SET 
            meta_title = :meta_title,
            meta_description = :meta_description,
            meta_keywords = :meta_keywords,
            canonical_url = :canonical_url,
            robots_index = :robots_index,
            robots_follow = :robots_follow,
            schema_json = :schema_json,
            open_graph_title = :open_graph_title,
            open_graph_description = :open_graph_description,
            open_graph_image = :open_graph_image,
            twitter_title = :twitter_title,
            twitter_description = :twitter_description,
            twitter_image = :twitter_image,
            priority = :priority,
            changefreq = :changefreq,
            slug = :slug
            WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $id,
            'meta_title' => $dto->metaTitle,
            'meta_description' => $dto->metaDescription,
            'meta_keywords' => $dto->metaKeywords,
            'canonical_url' => $dto->canonicalUrl,
            'robots_index' => $dto->robotsIndex ? 1 : 0,
            'robots_follow' => $dto->robotsFollow ? 1 : 0,
            'schema_json' => $dto->schemaJson,
            'open_graph_title' => $dto->openGraphTitle,
            'open_graph_description' => $dto->openGraphDescription,
            'open_graph_image' => $dto->openGraphImage,
            'twitter_title' => $dto->twitterTitle,
            'twitter_description' => $dto->twitterDescription,
            'twitter_image' => $dto->twitterImage,
            'priority' => $dto->priority,
            'changefreq' => $dto->changefreq,
            'slug' => $dto->slug
        ]);
    }
}
