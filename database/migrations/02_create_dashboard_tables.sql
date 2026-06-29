SET FOREIGN_KEY_CHECKS = 0;

-- Configuración de preferencias por usuario
CREATE TABLE IF NOT EXISTS `dashboard_preferences` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `dark_mode` tinyint(1) NOT NULL DEFAULT 0,
    `layout_json` json NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tareas Inteligentes detectadas por el sistema
CREATE TABLE IF NOT EXISTS `dashboard_tasks` (
    `id` int NOT NULL AUTO_INCREMENT,
    `type` varchar(50) NOT NULL,
    `entity_type` varchar(50) NOT NULL,
    `entity_id` int NOT NULL,
    `priority` enum('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    `status` enum('pending', 'resolved', 'ignored') DEFAULT 'pending',
    `message` text NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Registro de Actividad del Sistema (Auditoría) para Dashboard
CREATE TABLE IF NOT EXISTS `dashboard_activity` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NULL,
    `action` varchar(255) NOT NULL,
    `module` varchar(50) NOT NULL,
    `ip_address` varchar(45) NULL,
    `user_agent` text NULL,
    `result` enum('success', 'failure', 'warning') DEFAULT 'success',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Almacenamiento de Métricas y KPIs cacheados
CREATE TABLE IF NOT EXISTS `dashboard_metrics` (
    `id` int NOT NULL AUTO_INCREMENT,
    `metric_key` varchar(50) NOT NULL UNIQUE,
    `metric_value` json NOT NULL,
    `calculated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
