<?php
declare(strict_types=1);

namespace App\Validators;

use App\DTOs\SeoDTO;
use App\Core\Session;

/**
 * Class SeoValidator
 * 
 * Validates the inputs for the SEO form.
 * 
 * @package App\Validators
 */
class SeoValidator
{
    /**
     * Validate the SEO inputs and return an array of errors if any.
     *
     * @param SeoDTO $dto
     * @return array
     */
    public function validate(SeoDTO $dto): array
    {
        $errors = [];

        // Meta Title (Optional, but if present max 60 chars)
        if ($dto->metaTitle !== null && mb_strlen($dto->metaTitle) > 60) {
            $errors['meta_title'] = 'El título SEO no debe superar los 60 caracteres.';
        }

        // Meta Description (Optional, but if present max 160 chars)
        if ($dto->metaDescription !== null && mb_strlen($dto->metaDescription) > 160) {
            $errors['meta_description'] = 'La descripción SEO no debe superar los 160 caracteres.';
        }

        // Slug (Max 120)
        if (!empty($dto->slug) && mb_strlen($dto->slug) > 120) {
            $errors['slug'] = 'El slug no debe superar los 120 caracteres.';
        }

        // Canonical URL (Must be a valid URL if present)
        if (!empty($dto->canonicalUrl) && !filter_var($dto->canonicalUrl, FILTER_VALIDATE_URL)) {
            $errors['canonical_url'] = 'La URL canónica debe ser una URL válida (ej. https://...).';
        }

        // JSON Schema (Must be valid JSON if present)
        if (!empty($dto->schemaJson)) {
            json_decode($dto->schemaJson);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors['schema_json'] = 'El Schema estructurado debe ser un formato JSON válido.';
            }
        }
        
        // Open Graph Image (Must be a valid URL or path)
        if (!empty($dto->openGraphImage) && mb_strlen($dto->openGraphImage) > 255) {
            $errors['open_graph_image'] = 'La imagen de Open Graph no debe superar los 255 caracteres.';
        }

        return $errors;
    }
}
