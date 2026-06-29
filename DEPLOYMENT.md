# Guía de Despliegue a Producción (CPanel / Servidor Compartido)

Dado que este proyecto está desarrollado en PHP puro sin dependencias complejas (solo usa Composer para el Autoloader base), el despliegue a un servidor de producción estándar (Hosting compartido con cPanel, VPS, etc.) es extremadamente sencillo.

Sigue estos pasos para subir tu proyecto a producción:

## 1. Preparación de Archivos Localmente

Antes de subir los archivos:
1. Elimina (o no subas) la carpeta `database/`.
2. Asegúrate de tener los archivos `.htaccess` (en `public/` y opcionalmente en la raíz si deseas enrutar peticiones) intactos, ya que controlan el enrutamiento y la seguridad de Apache.
3. Si utilizaste Composer para generar el autoloader, asegúrate de subir la carpeta `vendor/`.

## 2. Subida al Servidor (cPanel / FileZilla)

Este proyecto está diseñado con la carpeta `public` como *DocumentRoot* por cuestiones de seguridad, evitando que usuarios externos accedan a los archivos del núcleo en `app/` o `config/`.

### Opción A: Hosting cPanel Estándar (Donde `public_html` es la raíz)
Si no tienes la libertad de cambiar el DocumentRoot de tu hosting, la estructura de carpetas debe ser la siguiente:

1. **Sube el núcleo (Fuera del acceso web):** 
   Crea una carpeta llamada `nexus-core` (o el nombre que gustes) **UN NIVEL ANTES** de `public_html`.
   Sube todo el contenido del proyecto allí (`app/`, `config/`, `routes/`, `vendor/`, `.env`), **EXCEPTO** la carpeta `public/`.
2. **Sube los archivos públicos:**
   Sube el contenido de la carpeta `public/` (el archivo `index.php` y el `.htaccess`) **DENTRO** de la carpeta `public_html` de tu cPanel.
3. **Modifica los paths en `index.php`:**
   Abre el archivo `index.php` ubicado ahora en `public_html` y modifica las rutas para que apunten a tu nueva carpeta `nexus-core`.
   *Cambia:*
   ```php
   require_once __DIR__ . '/../vendor/autoload.php';
   Env::load(__DIR__ . '/../.env');
   $app = new Application(dirname(__DIR__));
   require_once __DIR__ . '/../routes/web.php';
   ```
   *Por:*
   ```php
   require_once __DIR__ . '/../nexus-core/vendor/autoload.php';
   Env::load(__DIR__ . '/../nexus-core/.env');
   $app = new Application(__DIR__ . '/../nexus-core');
   require_once __DIR__ . '/../nexus-core/routes/web.php';
   ```

### Opción B: VPS / Servidor Apache con control Total
Si usas un VPS o puedes editar la configuración de Apache (VirtualHost):
1. Sube la carpeta completa del proyecto a `/var/www/web-catalogue`.
2. Apunta el `DocumentRoot` de tu dominio explícitamente a la carpeta `public`:
   ```apache
   <VirtualHost *:80>
       ServerName tudominio.com
       DocumentRoot /var/www/web-catalogue/public

       <Directory /var/www/web-catalogue/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

## 3. Configuración de Base de Datos en Producción

1. Ingresa a phpMyAdmin o el gestor de bases de datos de tu hosting.
2. Crea una base de datos nueva y asigna un usuario con todos los privilegios.
3. Importa el archivo `database/migrations/01_create_tables.sql`.
4. (Opcional) Importa el archivo `database/seeders/01_initial_data.sql` si deseas tener el usuario admin por defecto, o crea uno manualmente.
5. Edita el archivo `.env` en producción (ubicado en `nexus-core/.env` o en la raíz) con las credenciales reales:
   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=tu_usuario_basededatos
   DB_USERNAME=tu_usuario_usuariobd
   DB_PASSWORD=tu_contraseña_fuerte
   ```

## 4. Comprobaciones Finales de Seguridad

- Asegúrate de tener instalado un certificado SSL (HTTPS). El sistema enviará la cookie de sesión como `Secure` si detecta una conexión segura.
- Revisa que tu servidor soporte `mod_rewrite`, `mod_deflate`, y `mod_expires` de Apache (generalmente habilitados por defecto en cPanel). Esto es vital para las reglas de `.htaccess` del Módulo 8 (SEO y Rendimiento).
- Verifica que los archivos como `.env` devuelvan un error 403 o 404 si alguien intenta acceder a ellos mediante el navegador (esto ya está resuelto si subiste el núcleo fuera de `public_html`).

## 5. Listo para operar
Navega a `https://tudominio.com/`. ¡La vitrina y el panel están listos para recibir a los primeros visitantes!
