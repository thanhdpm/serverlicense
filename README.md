
# Server License - Quản lý giấy phép phần mềm, khách hàng

Có đầy đủ tài liệu API tích hợp, quản lý giấy phép, khách hàng linh hoạt. Có cấu hình thời hạn giấy phép theo thời gian.
- [VIDEO DEMO / HƯỚNG DẪN SỬ DỤNG](https://www.youtube.com/watch?v=Q7xXVTATZ6A)

```
🚫 Cấm thương mại mã nguồn miễn phí dưới mọi hình thức!
```

   - [Requirements](#requirements)
   - [Installation](#installation)
   - [Upgrading an existing installation](#upgrading-an-existing-installation)
   - [Production deployment](#production-deployment)
   - [Development](#development)
   - [Api Documentation](#api-documentation)
   - [FAQ](#faq)
   - [Bug report & Contribute](#bug-report--contribute)
   - [Credits](#credits)

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
git clone https://github.com/ducthanh-jtech/serverlicense.git
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

-  **Email Address:** admin@jzontech.asia
-  **Password:** admin

**Change this password immediately** (avatar menu → *Đổi mật khẩu*).

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

- **Kiểm tra, thông tin giấy phép:**
  - **GET** {{base_url}}/api/license?key=*{{license_key}}*
  - `message`: `VALID_LICENSE`, `INVALID_LICENSE`, `EXPIRED_LICENSE`, `VALIDATION_FAILED`
  - Lần gọi hợp lệ đầu tiên sẽ kích hoạt giấy phép (lưu IP + User-Agent).
- **Thông tin sản phẩm (bao gồm version log):**
  - **GET** {{base_url}}/api/product?id=*{{product_id}}*
  - `message`: `SUCCESS`, `PRODUCT_NOT_FOUND`, `VALIDATION_FAILED`

Requests are limited to `API_RATE_LIMIT` per minute per IP (HTTP 429 when exceeded).

## FAQ

**1. Làm sao để phần mềm của tôi biết phiên bản hiện tại là gì?**
- Request api `Thông tin sản phẩm`, nó sẽ trả về kết quả bao gồm cả nhật kí phiên bản. Phiên bản mới nhất sẽ được đẩy lên đầu tiên, ngoài ra còn có key (`lastest_version`) riêng để nhận biết phiên bản mới nhất.

**2. Làm sao để cập nhật phần mềm theo phiên bản?**
- Request api `Thông tin sản phẩm`, `versions` sẽ hiển thị tất cả phiên bản và đường dẫn tải bản cập nhật.

## Bug report & Contribute

- Facebook *(Online 24/24)*: **https://www.facebook.com/jzondev**

- Telegram *(Online 24/24)*: **https://t.me/cuteboiz999**

- Zalo: **0966142061**  *(Không khuyến khích)*

## Credits

-  *Fully coded by **Jzon Dev / Pham Duc Thanh.***

-  *Product of Jzon Tech.*

**CẢM ƠN BẠN ĐÃ SỬ DỤNG SẢN PHẨM CỦA JZON TECH 😍**
