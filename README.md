# Ecom Admin

A Laravel 13 e-commerce admin panel built with a modular (HMVC-style) architecture using [nwidart/laravel-modules](https://github.com/nWidart/laravel-modules). Each feature area (products, orders, customers, users, ACL, API) lives in its own self-contained module under `Modules/`, so it can be reused or dropped into another Laravel project.

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 13 (PHP ^8.3) |
| Modules | nwidart/laravel-modules (HMVC) |
| Database | MySQL (required — see note below) |
| Auth (admin panel) | Session-based, role-gated |
| Auth (REST API) | Laravel Sanctum (Bearer tokens) |
| Roles & Permissions | spatie/laravel-permission |
| PDF generation | barryvdh/laravel-dompdf |
| Frontend build | Vite + Tailwind CSS |
| Admin theme | Bootstrap 5 (Mamix admin template) |

> **MySQL is required, not optional.** One migration (`Modules/User/database/migrations/..._add_employee_and_crm_fields_to_users_table.php`) widens the `role` column via raw `ALTER TABLE ... MODIFY COLUMN ... ENUM(...)` SQL, which is MySQL syntax. SQLite will not run this migration.

## System Architecture

```
ecom_admin/
├── app/                      Core app: base User model, AuthController, Providers
├── Modules/
│   ├── Category/             Category CRUD (name, slug)
│   ├── Product/              Product CRUD (name, slug, sku, price, stock, image, category)
│   ├── User/                 User CRUD, roles (admin/employee/customer), AdminSeeder
│   ├── Order/                Orders + order items, OrderService (stock sync, invoicing)
│   ├── Customer/             CRM: lost-customer detection, employee assignment, re-engagement
│   ├── Acl/                  Roles & permissions admin UI (Spatie)
│   └── Api/                  Public REST API: auth, product catalog, order placement
├── resources/views/admin/    Shared admin layout (sidebar, header, footer) + login/dashboard
├── routes/web.php            Core routes (login, dashboard, profile) — everything else is
│                             registered per-module via each module's RouteServiceProvider
└── database/migrations/      Core tables only (users, cache, jobs) — module tables live
                              inside each module's own database/migrations/
```

**How a module is wired up:** every module has its own `routes/web.php`, `routes/api.php`, `app/Http/Controllers`, `app/Models`, `database/migrations`, and `resources/views`. Laravel-modules auto-registers each module's `RouteServiceProvider`, which loads its routes under the shared `web`/`api` middleware groups. Module dependency order (which one must be enabled before another) is declared in each `module.json`'s `requires` key — e.g. `Product` requires `Category`, `Order` requires `Product`, `Customer` requires `User` + `Order`, `Api` requires `Product` + `Order`.

**Two separate authentication systems, by design:**
- **Admin panel** (`admin@yourdomain / password`, session cookies): only `role = admin` or `role = employee` accounts can log in at `/login`. Access within the panel is then further restricted per-permission (see below).
- **Public REST API** (`Modules/Api`, Bearer tokens via Sanctum): `role = customer` accounts register/login here to browse products and place orders. Order placement also supports **guest checkout** (no token — just `name` + `email` in the request body).

**Roles & permissions (ACL):** the `role` enum column on `users` (`admin` / `employee` / `customer`) drives business logic (login gate, customer detection, KPI). It's automatically kept in sync with a matching [Spatie](https://spatie.be/docs/laravel-permission) role via a model event, which drives fine-grained route permissions (`products.view`, `orders.create`, `customers.assign`, etc.). **Admin always has every permission** and that role is locked from editing in the UI; **Employee/Customer roles start with a limited permission set** that an admin can adjust from `/roles`.

## Completed Features

**Catalog**
- Category CRUD (auto-generated unique slugs)
- Product CRUD (SKU, price, stock quantity, image upload, category assignment, auto-generated unique slugs)

**Users & Access Control**
- User CRUD with photo upload, phone, address, status (active/inactive)
- Three roles: `admin`, `employee`, `customer` — only admin/employee can log into the panel
- Role-based + permission-based access control (Spatie laravel-permission), with an admin UI at `/roles` to adjust what the Employee/Customer roles can do
- Admin seeder for bootstrapping the first account

**Orders & Purchase History**
- Multi-item orders (order + order line items), created from the admin panel or the public API
- Automatic stock decrement on order placement, with an out-of-stock guard (rejects the order instead of silently under-selling)
- Per-customer purchase history: purchase frequency (`purchase_count`) and last purchase date, kept in sync automatically (including on order deletion)
- Automatic PDF invoice generation + email delivery on every successful order (admin-created or API-created)

**Customer Relationship Management**
- Lost-customer detection: customers with no purchase inside a configurable window (default 90 days) are flagged
- Simulated re-engagement messaging (email/SMS) with a logged contact history per customer
- Employee assignment: admins assign a lost customer to an employee for follow-up
- KPI tracking: when an assigned customer makes a new purchase, the assigned employee's KPI score increments automatically and the assignment closes out

**Public REST API** (`/api/v1`)
- `POST /auth/register`, `POST /auth/login`, `POST /auth/logout` — customer accounts only
- `GET /products`, `GET /products/{slug}` — public product catalog, no auth required
- `POST /orders` — place an order as an authenticated customer **or** as a guest (`name` + `email` in the body)
- `GET /orders` — the authenticated customer's own order history

## Database Schema

### Core

**`users`**
| Column | Type | Notes |
|---|---|---|
| id, name, email, password | — | standard Laravel auth fields |
| avatar | string, nullable | profile/product-list photo |
| role | enum(admin, customer, employee) | default `customer` |
| status | enum(active, inactive) | default `active` |
| phone, address | string / text, nullable | |
| assigned_employee_id | FK → users.id, nullable | who's following up on this (lost) customer |
| assigned_at | timestamp, nullable | |
| kpi_score | unsigned int | employee follow-up performance counter |
| last_purchase_at | timestamp, nullable | |
| purchase_count | unsigned int | |

### Catalog

**`categories`**: id, name, slug (unique)
**`products`**: id, category_id (FK), name, slug (unique), sku (unique), price, stock_quantity, image, timestamps

### Orders

**`orders`**: id, user_id (FK), order_number (unique), status(enum: pending/processing/completed/cancelled), total_amount, notes
**`order_items`**: id, order_id (FK), product_id (FK), quantity, unit_price, subtotal

### CRM

**`customer_contacts`**: id, user_id (FK, the customer), sent_by (FK, the admin/employee who sent it), channel(enum: email/sms), message

### ACL (Spatie)

`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions` — standard Spatie laravel-permission tables.

### API

`personal_access_tokens` — standard Laravel Sanctum table.

---

## Getting Started (Local Setup)

### 1. Prerequisites

Install these first if you don't already have them:

- **PHP 8.3+** with extensions: `mbstring`, `dom`, `gd`, `mysqli`/`pdo_mysql`, `openssl`, `curl`, `zip`
- **Composer** ([getcomposer.org](https://getcomposer.org))
- **MySQL 8+**
- **Node.js 18+** and npm (for building the admin UI's CSS/JS)
- **Git**

Quick check:
```bash
php -v
composer -V
mysql --version
node -v && npm -v
```

### 2. Clone & install dependencies

```bash
git clone <your-repo-url> ecom_admin
cd ecom_admin

composer install
npm install
```

### 3. Environment configuration

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set at minimum:

```ini
APP_NAME="Ecom Admin"
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecom_admin
DB_USERNAME=root
DB_PASSWORD=your_mysql_password

# Safe for local dev — writes emails to storage/logs/laravel.log instead of sending them
MAIL_MAILER=log
```

Create the database (if it doesn't already exist):
```bash
mysql -u root -p -e "CREATE DATABASE ecom_admin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 4. Run migrations

```bash
php artisan migrate
```

This runs every core migration **and** every module's migrations (nwidart/laravel-modules registers each module's `database/migrations` path automatically).

### 5. Run seeders

```bash
php artisan db:seed
```

This seeds, in order:
1. **Acl** — creates the `admin`/`employee`/`customer` Spatie roles and the full permission set (admin gets everything; employee/customer get a limited default set)
2. **User** — creates the first admin account via `AdminSeeder`, plus 10 demo employee accounts via `EmployeeSeeder`
3. **Category** — 5 demo categories (Electronics, Home & Kitchen, Apparel, Sports & Outdoors, Books)
4. **Product** — 10 demo products spread across those categories
5. **Customer** — 10 demo customer accounts via `CustomerDatabaseSeeder`
6. **Order** — 10 demo orders ("sales") with 1–3 line items each ("transactions"), placed through `OrderService` (so stock, purchase stats, and invoicing behave exactly like a real order) with timestamps randomized over the last 45 days

Check `Modules/User/database/seeders/AdminSeeder.php` for the seeded admin email/password (edit it before seeding if you want different credentials).

### 6. Link the public storage disk

Needed for product images / user avatars to be reachable over HTTP:
```bash
php artisan storage:link
```

### 7. Build frontend assets

```bash
npm run build
# or, while developing:
npm run dev
```

---

## Running the Project Locally

**All-in-one (recommended while developing)** — runs the app server, queue listener, log viewer, and Vite dev server together:
```bash
composer run dev
```

**Or individually**, if you'd rather run things in separate terminals:
```bash
php artisan serve      # app server → http://127.0.0.1:8000
npm run dev            # Vite dev server (CSS/JS hot reload)
php artisan queue:listen --tries=1
```

Visit `http://127.0.0.1:8000/login` and sign in with the seeded admin account.

> Note: nothing is actually queued yet (`QUEUE_CONNECTION=database` but invoice emails currently send synchronously), so the queue listener above is a no-op today — it's there so queued jobs work immediately if you add any later.

---

## Nginx Setup

For a closer-to-production local setup (or an actual server), serve the app through Nginx + PHP-FPM instead of `php artisan serve`.

### 1. Install Nginx and PHP-FPM

```bash
sudo apt update
sudo apt install nginx php8.3-fpm
```

Confirm PHP-FPM's socket path (used in the config below):
```bash
sudo systemctl status php8.3-fpm
ls /run/php/
# e.g. /run/php/php8.3-fpm.sock
```

### 2. Create the site config

`/etc/nginx/sites-available/ecom_admin`:
```nginx
server {
    listen 80;
    server_name ecom_admin.local;
    root /var/www/backend/my/ecom_admin/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 20M;
}
```

Adjust `root` to your actual project path and `fastcgi_pass` to match the PHP-FPM socket/version you confirmed above.

### 3. Enable the site

```bash
sudo ln -s /etc/nginx/sites-available/ecom_admin /etc/nginx/sites-enabled/
sudo nginx -t          # validate config syntax before reloading
sudo systemctl reload nginx
```

### 4. Point the domain at it locally

Add to `/etc/hosts`:
```
127.0.0.1  ecom_admin.local
```

Then set `APP_URL=http://ecom_admin.local` in `.env` and visit `http://ecom_admin.local/login`.

### 5. File permissions

Nginx/PHP-FPM needs write access to these directories:
```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 6. HTTPS (optional, recommended beyond local dev)

Use [Certbot](https://certbot.eff.org/) for a free Let's Encrypt certificate on a real domain:
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-real-domain.com
```

---

## Quick Reference

| Task | Command |
|---|---|
| Install PHP deps | `composer install` |
| Install JS deps | `npm install` |
| Run migrations | `php artisan migrate` |
| Rollback last migration | `php artisan migrate:rollback` |
| Fresh DB + reseed | `php artisan migrate:fresh --seed` |
| Run seeders only | `php artisan db:seed` |
| Serve locally (dev server) | `php artisan serve` |
| Build frontend assets | `npm run build` |
| Watch frontend assets | `npm run dev` |
| List all modules | `php artisan module:list` |
| List all routes | `php artisan route:list` |
| Clear all caches | `php artisan optimize:clear` |
