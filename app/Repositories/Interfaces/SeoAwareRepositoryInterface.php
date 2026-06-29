<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTOs\SeoDTO;

/**
 * Interface SeoAwareRepositoryInterface
 * 
 * Defines the contract for any entity repository that supports SEO data handling.
 * 
 * @package App\Repositories\Interfaces
 */
interface SeoAwareRepositoryInterface
{
    /**
     * Find an entity by its ID.
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array;

    /**
     * Find an entity by its slug, optionally excluding a specific ID.
     *
     * @param string $slug
     * @param int|null $excludeId
     * @return array|null
     */
    public function findBySlug(string $slug, ?int $excludeId = null): ?array;

    /**
     * Update the SEO parameters of an entity.
     *
     * @param int $id
     * @param SeoDTO $dto
     * @return bool
     */
    public function updateSeo(int $id, SeoDTO $dto): bool;
}
