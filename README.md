# Server License - Software License & Customer Management

A self-hosted admin panel for managing software licenses, customers, products and release versions, with a simple API that client software uses to validate licenses and check for updates. License durations can be set flexibly, from seconds to years.

- [Video demo / user guide](https://www.youtube.com/watch?v=Q7xXVTATZ6A)

```
🚫 Commercial use of this free source code, in any form, is prohibited.
```

   - [Requirements](#requirements)
   - [Installation](#installation)
   - [Upgrading an existing installation](#upgrading-an-existing-installation)
   - [Production deployment](#production-deployment)
   - [Development](#development)
   - [API Documentation](#api-documentation)
   - [FAQ](#faq)
   - [Contributing](#contributing)

## Requirements

- PHP 8.3+ with `pdo_mysql` (or `pdo_sqlite`), `mbstring`, `gd`, `zip`, `bcmath`
- Composer 2
- MySQL 8 (or SQLite)

No local PHP? The `serverlicense-php` Docker image (see `Dockerfile`) is used by the `bin/composer` and `bin/artisan` wrappers:

```sh
docker build -t serverlicense-php .
bin/composer install
bin/artisan migrate
```

## Installation

```sh
git clone https://github.com/thanhdpm/serverlicense.git
cd serverlicense
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set the database connection (`DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). Then:

```sh
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Open [Server License](http://127.0.0.1:8000/) and log in with:

-  **Email address:** admin@jzontech.asia
-  **Password:** admin

**Change this password immediately** (avatar menu → *Change password*).

### Configuration

| Variable | Default | Description |
| --- | --- | --- |
| `APP_DEMO` | `false` | Read-only demo: every write request in the admin panel returns 403. |
| `FORCE_HTTPS` | `false` | Generate `https://` URLs (use behind a TLS-terminating proxy). |
| `PAGINATION` | `10` | Default rows per page (`?row=` can raise it up to 100). |
| `API_RATE_LIMIT` | `300` | Public API requests allowed per minute per client IP. |
| `VERSION_UPLOAD_MAX_KB` | `512000` | Maximum size of an update file. PHP's `upload_max_filesize` and `post_max_size` must allow it too. |
| `VERSION_UPLOAD_EXTENSIONS` | `zip,rar,7z,tar,gz,exe,msi,dmg,pkg,apk,jar,bin` | Allowed update file extensions. |

## Upgrading an existing installation

```sh
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
```

The 2026-09-17 migrations are additive and keep the public API output unchanged:

- `licenses.key` becomes a unique, indexed `VARCHAR(191)` (fast license lookups). **If two licenses share a key, or a key is longer than 191 characters, the migration stops before changing anything and lists them.** Fix those rows, then run `migrate` again.
- `licenses.customer_id` is added and filled from each license's customer snapshot.
- `versions.product_id` gets an index and a foreign key. Version rows whose product was already deleted (unreachable from the panel and the API) are removed.

Other changes worth knowing:

- The web database setup page was removed. Configure the database in `.env`.
- Admin URLs now follow resource conventions, e.g. `/customers/{id}/edit`, `/products/{id}/versions`. API URLs are unchanged.
- Rename `CACHE_DRIVER` to `CACHE_STORE` in `.env` (the old name still works).

## Production deployment

```sh
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
```

- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Enable OPcache.
- Run `php artisan optimize` again after every deploy or `.env` change (it caches config, routes, events and views).

## Development

```sh
composer test     # PHPUnit
composer lint     # Pint (code style); `composer format` fixes issues
composer analyse  # Larastan static analysis
```

The same checks run in GitHub Actions (`.github/workflows/ci.yml`). The public API responses are locked by byte-for-byte contract tests in `tests/Feature/Api`; if one of them fails, a change would break shipped clients.

## API Documentation

All endpoints return HTTP 200 with an `ok` flag; check `message` for the outcome.

- **Check a license and get its details:**
  - **GET** {{base_url}}/api/license?key=*{{license_key}}*
  - `message`: `VALID_LICENSE`, `INVALID_LICENSE`, `EXPIRED_LICENSE`, `VALIDATION_FAILED`
  - The first valid call activates the license and records the caller's IP address and User-Agent.
- **Get product details (including the version log):**
  - **GET** {{base_url}}/api/product?id=*{{product_id}}*
  - `message`: `SUCCESS`, `PRODUCT_NOT_FOUND`, `VALIDATION_FAILED`

Requests are limited to `API_RATE_LIMIT` per minute per IP (HTTP 429 when exceeded).

## FAQ

**1. How does my software know which version is the latest?**
- Call the product details API. The response includes the full version log, newest version first, plus a dedicated `lastest_version` key holding the latest version.

**2. How do I update my software to a specific version?**
- Call the product details API. `versions` lists every version together with the download link for its update file.

## Contributing

Contributions are welcome!

- **Found a bug or have an idea?** [Open an issue](https://github.com/thanhdpm/serverlicense/issues). Include steps to reproduce, what you expected, and what happened instead.
- **Want to fix or improve something?** Fork the repository, create a branch, and [open a pull request](https://github.com/thanhdpm/serverlicense/pulls).

Before opening a pull request, make sure these pass:

```sh
composer lint
composer analyse
composer test
```

Please keep the public API responses backward compatible: client software already in use depends on them.
