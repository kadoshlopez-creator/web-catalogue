<?php
declare(strict_types=1);

namespace App\Services;

use App\DTOs\SeoDTO;
use App\Repositories\Interfaces\SeoAwareRepositoryInterface;
use App\Repositories\Interfaces\SlugHistoryRepositoryInterface;

/**
 * Class SeoService
 * 
 * Handles business logic for SEO optimizations, scores and routing logic.
 * 
 * @package App\Services
 */
class SeoService
{
    private SlugService $slugService;
    private SeoAwareRepositoryInterface $repository;
    private SlugHistoryRepositoryInterface $slugHistoryRepository;

    public function __construct(
        SlugService $slugService,
        SeoAwareRepositoryInterface $repository,
        SlugHistoryRepositoryInterface $slugHistoryRepository
    ) {
        $this->slugService = $slugService;
        $this->repository = $repository;
        $this->slugHistoryRepository = $slugHistoryRepository;
    }

    /**
     * Process and save an entity's SEO data.
     * Generates 301 redirects automatically if the slug changes.
     *
     * @param int $entityId
     * @param SeoDTO $dto
     * @return bool
     */
    public function updateSeo(int $entityId, SeoDTO $dto): bool
    {
        $currentEntity = $this->repository->findById($entityId);
        if (!$currentEntity) {
            return false;
        }

        // Validate and generate unique slug if not provided
        if (empty($dto->slug)) {
            $baseText = !empty($dto->metaTitle) ? $dto->metaTitle : $currentEntity['name'];
            $dto->slug = $this->slugService->generateUniqueSlug($baseText, $entityId);
        } else {
            $dto->slug = $this->slugService->generateUniqueSlug($dto->slug, $entityId);
        }

        // Track slug history for 301 redirects
        if ($currentEntity['slug'] !== $dto->slug) {
            $this->slugHistoryRepository->recordHistory($entityId, $currentEntity['slug'], $dto->slug);
        }

        return $this->repository->updateSeo($entityId, $dto);
    }

    /**
     * Calculate an SEO score from 0 to 100 based on standard best practices.
     * 
     * @param array $categoryData The current category data array
     * @return int
     */
    public function calculateSeoScore(array $categoryData): int
    {
        $score = 0;

        // Meta Title (Ideal length 50-60)
        $titleLength = mb_strlen((string)($categoryData['meta_title'] ?? ''));
        if ($titleLength >= 30 && $titleLength <= 60) {
            $score += 20;
        } elseif ($titleLength > 0) {
            $score += 10;
        }

        // Meta Description (Ideal length 140-160)
        $descLength = mb_strlen((string)($categoryData['meta_description'] ?? ''));
        if ($descLength >= 120 && $descLength <= 160) {
            $score += 20;
        } elseif ($descLength > 0) {
            $score += 10;
        }

        // Keywords
        if (!empty($categoryData['meta_keywords'])) {
            $score += 10;
        }

        // Canonical URL
        if (!empty($categoryData['canonical_url'])) {
            $score += 10;
        }

        // Open Graph
        if (!empty($categoryData['open_graph_title']) && !empty($categoryData['open_graph_description'])) {
            $score += 15;
        }

        // Twitter
        if (!empty($categoryData['twitter_title']) && !empty($categoryData['twitter_description'])) {
            $score += 10;
        }

        // Schema JSON
        if (!empty($categoryData['schema_json'])) {
            $score += 15;
        }

        return min($score, 100);
    }
}
