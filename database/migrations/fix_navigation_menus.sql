UPDATE settings
SET setting_value = JSON_OBJECT(
    'main', JSON_OBJECT(
        'items', JSON_ARRAY(
            JSON_OBJECT('label', 'Inicio', 'link', '/'),
            JSON_OBJECT('label', 'Cat\u00e1logo', 'link', '/catalogo')
        )
    ),
    'footer', JSON_OBJECT(
        'items', JSON_ARRAY(
            JSON_OBJECT('label', 'Inicio', 'link', '/'),
            JSON_OBJECT('label', 'Cat\u00e1logo', 'link', '/catalogo'),
            JSON_OBJECT('label', 'Sobre Nosotros', 'link', '/p/sobre-nosotros')
        )
    )
)
WHERE setting_key = 'navigation_menus';
