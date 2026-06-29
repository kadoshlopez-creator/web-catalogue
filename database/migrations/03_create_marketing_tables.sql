SET FOREIGN_KEY_CHECKS = 0;

-- Campañas (Contenedor principal)
CREATE TABLE IF NOT EXISTS `campaigns` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `description` text NULL,
    `start_date` datetime NULL,
    `end_date` datetime NULL,
    `status` enum('draft', 'scheduled', 'active', 'paused', 'ended') DEFAULT 'draft',
    `priority` int DEFAULT 0,
    `color_theme` varchar(50) NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Promociones (Acciones comerciales sin modificar precio base)
CREATE TABLE IF NOT EXISTS `promotions` (
    `id` int NOT NULL AUTO_INCREMENT,
    `campaign_id` int NULL,
    `name` varchar(255) NOT NULL,
    `type` enum('bogo', 'free_shipping', 'gift', 'discount_code') NOT NULL,
    `status` enum('active', 'inactive') DEFAULT 'active',
    `conditions` json NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`campaign_id`) REFERENCES `campaigns`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Relaciones polimórficas (simuladas) para promociones
CREATE TABLE IF NOT EXISTS `promotion_targets` (
    `id` int NOT NULL AUTO_INCREMENT,
    `promotion_id` int NOT NULL,
    `target_type` enum('product', 'category', 'brand', 'global') NOT NULL,
    `target_id` int NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_target` (`promotion_id`, `target_type`, `target_id`),
    FOREIGN KEY (`promotion_id`) REFERENCES `promotions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ofertas (Modifican el precio directamente)
CREATE TABLE IF NOT EXISTS `offers` (
    `id` int NOT NULL AUTO_INCREMENT,
    `campaign_id` int NULL,
    `name` varchar(255) NOT NULL,
    `discount_type` enum('percentage', 'fixed_amount') NOT NULL,
    `discount_value` decimal(10,2) NOT NULL,
    `start_date` datetime NULL,
    `end_date` datetime NULL,
    `status` enum('active', 'inactive') DEFAULT 'active',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`campaign_id`) REFERENCES `campaigns`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Relación de ofertas con productos
CREATE TABLE IF NOT EXISTS `offer_products` (
    `offer_id` int NOT NULL,
    `product_id` int NOT NULL,
    PRIMARY KEY (`offer_id`, `product_id`),
    FOREIGN KEY (`offer_id`) REFERENCES `offers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Banners
CREATE TABLE IF NOT EXISTS `banners_marketing` (
    `id` int NOT NULL AUTO_INCREMENT,
    `campaign_id` int NULL,
    `name` varchar(255) NOT NULL,
    `type` enum('hero', 'popup', 'slider', 'sidebar', 'mini') NOT NULL,
    `image_desktop` varchar(255) NOT NULL,
    `image_mobile` varchar(255) NULL,
    `link_url` varchar(255) NULL,
    `status` enum('active', 'inactive') DEFAULT 'active',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`campaign_id`) REFERENCES `campaigns`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Landing Pages
CREATE TABLE IF NOT EXISTS `landing_pages` (
    `id` int NOT NULL AUTO_INCREMENT,
    `campaign_id` int NULL,
    `title` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `seo_metadata` json NULL,
    `content_blocks` json NOT NULL,
    `status` enum('published', 'draft') DEFAULT 'draft',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`campaign_id`) REFERENCES `campaigns`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
