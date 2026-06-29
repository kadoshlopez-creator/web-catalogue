# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

**Database setup / reset (runs all migrations + seeders):**
```bash
php migrate.php
```

**Run the SEO module unit tests (custom runner, no PHPUnit):**
```bash
php tests/SeoModuleTest.php
```

**Run the app:** Serve via Laragon or Apache. The `public/` folder is the DocumentRoot. Locally accessible at `http://web-catalogue.test/`.

**Composer autoloader (no packages to install, only autoloading):**
```bash
php composer.phar dump-autoload
```

## Architecture

This is a **custom PHP 8.1+ MVC framework** built from scratch — no Laravel, Symfony, or any other framework. The entire engine lives in `app/Core/`.

### Request lifecycle

```
public/index.php → Application → Router::resolve()
  → global middlewares (SecurityHeaders, CSRF)
  → controller middlewares (Auth, Guest, RateLimit, Role)
  → Controller method
  → View::render('dot.notation', $data, 'layoutName')
```

- Routes are defined in `routes/web.php` using `$router->get()` / `->post()`.
- Route params like `{id}` are converted to named-capture regex and injected as method arguments.
- `public/index.php` is the sole entry point; `.htaccess` rewrites all traffic there.

### Core layer (`app/Core/`)

| File | Responsibility |
|---|---|
| `Application.php` | Singleton bootstrapper, holds `$app->router/request/response` |
| `Router.php` | Regex-based route matching, middleware execution |
| `Controller.php` | Base class — `render()`, `redirect()`, `registerMiddleware()` |
| `Model.php` | PDO-backed base model — `all()`, `find()`, `where()`, `create()`, `update()`, `delete()` |
| `View.php` | `View::render('dot.path', $data, $layout)` — layouts use `{{content}}` placeholder |
| `Database.php` | PDO singleton, reads `config/database.php` |
| `Session.php` | Session wrapper with flash message support |

### Conventions

- **Controllers** register their middlewares in `__construct()` via `$this->registerMiddleware(new AuthMiddleware())`. All admin controllers require `AuthMiddleware`.
- **Models** extend `App\Core\Model`, declare `protected string $table` and optionally `$primaryKey`.
- **Views** live under `app/Views/` with dot-notation paths: `'admin.products.index'` → `app/Views/admin/products/index.php`. Layouts are in `app/Views/layouts/`.
- **Environment config** is loaded from `.env` via `App\Core\Env`. Copy `.env.example` to `.env` before first run.

### Services & Repositories

Business logic is separated from controllers into `app/Services/`. Data access for SEO-aware entities uses the Repository pattern with interfaces in `app/Repositories/Interfaces/`:

- `SeoService` — calculates SEO scores, delegates slug generation, records slug history for automatic 301 redirects when a slug changes.
- `SlugService` — normalizes text to URL-safe slugs (handles Spanish accents, emojis, special chars).
- `SeoHealthService` / `SystemHealthService` / `DashboardService` — aggregate data for the admin dashboard widgets.
- `PricingService`, `BannerService`, `CampaignService`, etc. — domain services for the marketing module.

### Database

Migrations are plain `.sql` files in `database/migrations/` and seeders in `database/seeders/`, sorted alphabetically and executed in order by `migrate.php`. Config is read from `config/database.php` which reads `$_ENV` values set by `.env`.

### Security

- **CSRF**: `CsrfMiddleware` runs globally. All POST forms must include the hidden CSRF token.
- **Rate limiting**: `RateLimitMiddleware` is applied to the login route against brute-force attacks.
- **Headers**: `SecurityHeadersMiddleware` sets `X-Frame-Options`, `CSP`, etc. on every response.
- **Roles**: `RoleMiddleware` provides role-based access control on top of authentication.

### SEO module

Products and categories each have a dedicated SEO controller (`ProductSeoController`, `CategorySeoController`) backed by `SeoService`. Slug changes are automatically recorded in `*_slug_history` tables so the router can issue 301 redirects. `SeoDTO` carries SEO fields; `SeoValidator` enforces character limits and URL formats.
