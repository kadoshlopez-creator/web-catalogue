-- Agrega el flag show_in_menu a la tabla promotions
-- Permite controlar de forma explícita si la sección "Promociones" aparece en el menú público

ALTER TABLE `promotions`
    ADD COLUMN `show_in_menu` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`;
