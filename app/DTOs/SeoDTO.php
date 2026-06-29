<?php
declare(strict_types=1);

namespace App\DTOs;

/**
 * Class SeoDTO
 * 
 * Data Transfer Object for encapsulating SEO data for any Entity (Category, Product).
 * 
 * @package App\DTOs
 */
class SeoDTO
{
    public string $slug;
    public ?string $metaTitle;
    public ?string $metaDescription;
    public ?string $metaKeywords;
    public ?string $canonicalUrl;
    public bool $robotsIndex;
    public bool $robotsFollow;
    public ?string $schemaJson;
    public ?string $openGraphTitle;
    public ?string $openGraphDescription;
    public ?string $openGraphImage;
    public ?string $twitterTitle;
    public ?string $twitterDescription;
    public ?string $twitterImage;
    public float $priority;
    public string $changefreq;

    public function __construct(array $data)
    {
        $this->slug = $data['slug'] ?? '';
        $this->metaTitle = $data['meta_title'] ?? null;
        $this->metaDescription = $data['meta_description'] ?? null;
        $this->metaKeywords = $data['meta_keywords'] ?? null;
        $this->canonicalUrl = $data['canonical_url'] ?? null;
        
        $this->robotsIndex = isset($data['robots_index']) ? (bool) $data['robots_index'] : true;
        $this->robotsFollow = isset($data['robots_follow']) ? (bool) $data['robots_follow'] : true;
        
        $this->schemaJson = $data['schema_json'] ?? null;
        $this->openGraphTitle = $data['open_graph_title'] ?? null;
        $this->openGraphDescription = $data['open_graph_description'] ?? null;
        $this->openGraphImage = $data['open_graph_image'] ?? null;
        $this->twitterTitle = $data['twitter_title'] ?? null;
        $this->twitterDescription = $data['twitter_description'] ?? null;
        $this->twitterImage = $data['twitter_image'] ?? null;
        
        $this->priority = isset($data['priority']) ? (float) $data['priority'] : 0.5;
        $this->changefreq = $data['changefreq'] ?? 'monthly';
    }
}
