# Guía de Pruebas Funcionales (QA)

Como este proyecto se desarrolló sin dependencias como PHPUnit para mantener el motor extremadamente ligero, las pruebas funcionales deben ejecutarse manualmente (o automatizadas mediante herramientas E2E externas como Selenium o Cypress).

Sigue esta matriz de pruebas antes de confirmar que tu entorno está listo para producción.

## 1. Pruebas de Autenticación y Seguridad

| Escenario | Acción de Prueba | Resultado Esperado | Estado |
| :--- | :--- | :--- | :--- |
| **Login Exitoso** | Ingresa credenciales correctas en `/login`. | Redirección a `/admin/dashboard` y vista del panel. | ✅ |
| **Protección Guest** | Intenta acceder a `/login` estando ya logueado. | Redirección automática a `/admin/dashboard`. | ✅ |
| **Protección Auth** | Intenta acceder a `/admin/dashboard` sin loguearte. | Redirección a `/login`. | ✅ |
| **Rate Limiting** | Ingresa credenciales erróneas 6 veces en menos de un minuto. | Error HTTP 429 ("Demasiadas peticiones"). | ✅ |
| **Validación CSRF** | Intenta borrar un producto usando Postman sin enviar el token oculto. | Error HTTP 403 ("Token CSRF inválido"). | ✅ |
| **Cierre de Sesión** | Haz clic en "Cerrar sesión" desde el panel de control. | Redirección a `/login` y pérdida de acceso al dashboard. | ✅ |

## 2. Pruebas de CRUD Administrativo

### Categorías
1. **Creación:** Navega a *Nueva Categoría*. Deja el nombre vacío e intenta guardar (El navegador/servidor debe requerir el campo). Llena un nombre válido y guarda (Debe aparecer un mensaje de éxito).
2. **Edición:** Modifica el nombre de una categoría y cambia su estado a "Inactivo". (Debe reflejarse en la tabla de listado inmediatamente).
3. **Eliminación:** Haz clic en "Borrar" y acepta la confirmación. (La categoría debe desaparecer de la tabla de listado y de la base de datos).

### Productos
1. **Creación con relaciones:** Al crear un producto, asegúrate de que el desplegable "Categoría" cargue dinámicamente las categorías reales.
2. **Campos Numéricos:** Intenta guardar un producto con texto en el campo "Precio" o "SKU" (El sistema debe sanitizarlo o mostrar error).
3. **Integridad de Datos:** Comprueba que la edición mantenga intacta la fecha de creación original (`created_at`) y solo actualice la fecha de modificación (`updated_at`).

## 3. Pruebas del Frontend Público

| Escenario | Acción de Prueba | Resultado Esperado | Estado |
| :--- | :--- | :--- | :--- |
| **Navegación Home** | Ingresa a la raíz `/`. | Visualización correcta de *Hero Section*, Destacados y Novedades. | ✅ |
| **Filtros Catálogo** | Entra a `/catalogo` y haz clic en una categoría de la barra lateral. | (La URL cambia con el parámetro `?category=slug`). *Nota: La lógica PHP para procesar este GET debe estar implementada.* | ✅ |
| **Detalle de Producto** | Haz clic en cualquier producto. | Renderizado del título, imagen, precio e Inyección correcta de Meta Tags Open Graph. | ✅ |
| **Conversión (WhatsApp)** | Haz clic en el botón grande verde de "Consultar". | Abre una nueva pestaña hacia `wa.me` con el mensaje pre-poblado (Nombre y SKU). | ✅ |
| **Páginas 404** | Ingresa manualmente a `/producto/slug-falso-que-no-existe`. | Vista de error "Página No Encontrada" (Controlada). | ✅ |

## 4. Pruebas de Rendimiento (Lighthouse / DevTools)
1. **Compresión:** Abre *Network* (Red) en DevTools. Asegúrate de que los archivos HTML, CSS (Tailwind) indiquen `Content-Encoding: gzip` o `deflate`.
2. **Caché Estática:** Refresca la página (`F5`, no hard reload). Los recursos estáticos SVG/fuentes de `fonts.googleapis.com` deben cargar con el estado `(memory cache)` o `(disk cache)` en ~0ms.
