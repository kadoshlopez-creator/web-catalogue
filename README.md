# NEXUS Catálogo Web

Un moderno catálogo de productos administrable (CMS) construido desde cero utilizando el patrón arquitectónico MVC (Modelo-Vista-Controlador) en PHP puro, sin el uso de frameworks pesados.

## 🚀 Características Principales

*   **Arquitectura MVC Propia:** Sistema de enrutamiento, controladores, modelos y vistas construidos desde cero.
*   **Diseño Premium (Tailwind CSS):** Interfaz pública enfocada en la conversión con un diseño elegante estilo *Glassmorphism*.
*   **Panel Administrativo (Dashboard):** Gestión completa de Productos y Categorías con autenticación y roles.
*   **Alta Seguridad:** 
    *   Protección contra ataques CSRF mediante tokens dinámicos.
    *   Middlewares de seguridad global (`X-Frame-Options`, `CSP`).
    *   Limitación de tasa (Rate Limiting) contra ataques de fuerza bruta en el inicio de sesión.
*   **Optimizado para SEO y Rendimiento:**
    *   Meta etiquetas dinámicas (Open Graph) para compartir en redes sociales.
    *   Soporte integrado para compresión GZIP/Deflate.
    *   Caché estática de navegador pre-configurada vía `.htaccess`.
*   **Integración con WhatsApp:** Llamados a la acción directos para ventas por mensajería.

## 🛠️ Tecnologías Utilizadas

*   **Backend:** PHP 8.1+ (Patrón MVC personalizado, PDO para Base de Datos).
*   **Frontend:** HTML5, CSS3, Tailwind CSS (CDN para vistas públicas y panel administrativo).
*   **Base de Datos:** MySQL / MariaDB.
*   **Servidor:** Apache (con soporte para `mod_rewrite`).

## 📁 Estructura del Proyecto

```text
/
├── app/
│   ├── Controllers/   # Lógica de las páginas y administración (Auth, Catalog, Product, etc.)
│   ├── Core/          # Motor de la aplicación (Router, Request, Response, View, Session)
│   ├── Middleware/    # Filtros de peticiones (Auth, Guest, Csrf, RateLimit, SecurityHeaders)
│   ├── Models/        # Abstracción de la base de datos (Model.php padre)
│   └── Views/         # Plantillas HTML/PHP (layouts, admin, public)
├── config/            # Configuraciones globales (app, database)
├── database/          # Scripts SQL (migraciones y seeders iniciales)
├── public/            # DocumentRoot (Punto de entrada: index.php y .htaccess)
├── routes/            # Definición de rutas del sistema (web.php)
├── .env.example       # Plantilla de variables de entorno
└── migrate.php        # Script CLI para ejecutar migraciones y reiniciar la BD
```

## 💻 Requisitos Previos

*   Servidor web Apache con `mod_rewrite` habilitado.
*   PHP >= 8.1
*   Base de datos MySQL o MariaDB.
*   Composer (opcional, actualmente usado solo para autoloading básico PSR-4).

## 🚀 Instalación Local

1.  **Clonar el repositorio:**
    Coloca el proyecto en tu directorio de servidor local (ej. `c:\laragon\www\web-catalogue`).
2.  **Configurar Variables de Entorno:**
    Duplica el archivo `.env.example` y renómbralo a `.env`. Ajusta las credenciales de tu base de datos:
    ```env
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=web_catalogue
    DB_USERNAME=root
    DB_PASSWORD=
    ```
3.  **Configurar Base de Datos:**
    Asegúrate de crear una base de datos vacía llamada `web_catalogue` en tu gestor (phpMyAdmin, HeidiSQL, etc.).
    Luego ejecuta las migraciones iniciales y el seeder corriendo este comando en la raíz del proyecto:
    ```bash
    php migrate.php
    ```
    *Este script creará las tablas e insertará al usuario administrador por defecto.*
4.  **Iniciar:**
    Accede al proyecto desde tu entorno de desarrollo local (ej. `http://web-catalogue.test/`).

## 🔐 Acceso al Administrador

- **URL:** `http://tu-dominio/login`
- **Email:** `admin@example.com`
- **Contraseña:** `password`

> **Nota:** Cambia estas credenciales inmediatamente después del primer inicio de sesión en un entorno de producción.
