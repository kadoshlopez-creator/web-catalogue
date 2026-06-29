SET FOREIGN_KEY_CHECKS = 0;

-- Drop old offer_products table
DROP TABLE IF EXISTS `offer_products`;

-- Create new polymorphic offer_targets table
CREATE TABLE IF NOT EXISTS `offer_targets` (
    `id` int NOT NULL AUTO_INCREMENT,
    `offer_id` int NOT NULL,
    `target_type` enum('product', 'category', 'brand', 'global') NOT NULL,
    `target_id` int NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_offer_target` (`offer_id`, `target_type`, `target_id`),
    FOREIGN KEY (`offer_id`) REFERENCES `offers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
