<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces;

/**
 * Interface SlugHistoryRepositoryInterface
 * 
 * Defines the contract for tracking slug changes to handle 301 redirects automatically.
 * 
 * @package App\Repositories\Interfaces
 */
interface SlugHistoryRepositoryInterface
{
    /**
     * Store a new slug history record.
     *
     * @param int $entityId
     * @param string $oldSlug
     * @param string $newSlug
     * @param int $redirectType
     * @return bool
     */
    public function recordHistory(int $entityId, string $oldSlug, string $newSlug, int $redirectType = 301): bool;

    /**
     * Find the new slug for an old slug, if it exists.
     *
     * @param string $oldSlug
     * @return string|null
     */
    public function findNewSlug(string $oldSlug): ?string;
}
