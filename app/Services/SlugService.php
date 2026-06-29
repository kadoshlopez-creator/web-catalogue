<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\Interfaces\SeoAwareRepositoryInterface;

/**
 * Class SlugService
 * 
 * Handles generation and sanitization of slugs, ensuring uniqueness.
 * 
 * @package App\Services
 */
class SlugService
{
    private SeoAwareRepositoryInterface $repository;

    public function __construct(SeoAwareRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Generate a unique slug for a category.
     *
     * @param string $text
     * @param int|null $excludeId ID to exclude when checking for uniqueness (for updates)
     * @return string
     */
    public function generateUniqueSlug(string $text, ?int $excludeId = null): string
    {
        $baseSlug = $this->sanitize($text);
        $slug = $baseSlug;
        $counter = 2;

        while ($this->repository->findBySlug($slug, $excludeId) !== null) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Sanitize a string into a clean, URL-friendly slug.
     * Removes accents, emojis, special characters and double spaces.
     *
     * @param string $text
     * @return string
     */
    public function sanitize(string $text): string
    {
        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');

        // Replace accents and special characters
        $unwanted_array = [
            'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u',
            'à'=>'a', 'è'=>'e', 'ì'=>'i', 'ò'=>'o', 'ù'=>'u',
            'ä'=>'a', 'ë'=>'e', 'ï'=>'i', 'ö'=>'o', 'ü'=>'u',
            'â'=>'a', 'ê'=>'e', 'î'=>'i', 'ô'=>'o', 'û'=>'u',
            'ñ'=>'n', 'ç'=>'c'
        ];
        $text = strtr($text, $unwanted_array);

        // Remove emojis and symbols
        $text = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $text);
        $text = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $text);
        $text = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $text);
        $text = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $text);
        $text = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $text);

        // Replace anything that is not a letter or number with a dash
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);

        // Remove duplicate dashes
        $text = preg_replace('/-+/', '-', $text);

        // Trim dashes from start and end
        $text = trim($text, '-');

        // Ensure max length (120 chars)
        return substr($text, 0, 120);
    }
}
