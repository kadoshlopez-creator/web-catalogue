-- Roles
INSERT IGNORE INTO `roles` (`id`, `name`, `permissions`) VALUES
(1, 'SuperAdmin', '{"all": true}'),
(2, 'Editor', '{"products": true, "categories": true, "brands": true}');

-- Usuario Administrador (Password es 'password')
INSERT IGNORE INTO `users` (`id`, `role_id`, `name`, `email`, `password`) VALUES
(1, 1, 'Administrador General', 'admin@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Categorías Básicas
INSERT IGNORE INTO `categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Electrónica', 'electronica', 'Dispositivos y gadgets tecnológicos'),
(2, 'Hogar', 'hogar', 'Artículos para el hogar y muebles'),
(3, 'Ropa', 'ropa', 'Moda y accesorios');

-- Marcas Básicas
INSERT IGNORE INTO `brands` (`id`, `name`, `slug`) VALUES
(1, 'Apple', 'apple'),
(2, 'Samsung', 'samsung'),
(3, 'Sony', 'sony');

-- Configuración Básica
INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'Catálogo Web Premium'),
('company_email', 'contacto@ejemplo.com'),
('company_phone', '+123456789'),
('company_whatsapp', '123456789'),
('company_address', '123 Calle Principal, Ciudad'),
('primary_color', '#0f172a'),
('secondary_color', '#3b82f6'),
('seo_meta_title', 'Catálogo Web - Los mejores productos'),
('seo_meta_description', 'Encuentra los mejores productos con nosotros.');
