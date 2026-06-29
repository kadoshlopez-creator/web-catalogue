<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Category;

class SeoHealthService
{
    private Product $productModel;
    private Category $categoryModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    public function getOverallHealth(): array
    {
        // Simple mock for now, can be expanded to scan all DB records
        return [
            'score' => 85,
            'missing_meta_titles' => 3,
            'missing_meta_descriptions' => 5,
            'missing_alts' => 12
        ];
    }
}
