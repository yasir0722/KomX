# KomX Platform

A modular monolith platform built with **Laravel 12**, **Vue 3**, and **MySQL** — designed for community/organisation management.

## Features

- 🔐 Session-based SPA authentication (Laravel Sanctum)
- 👥 Member management with profiles
- 🎭 Role-based access (Admin, Committee, Member)
- 📊 Dashboard with stats (planned)
- 📅 Event & attendance tracking (planned)
- ⭐ Points / activity tracking (planned)
- 📈 Basic reporting (planned)

## Tech Stack

| Layer     | Technology                    |
| --------- | ----------------------------- |
| Backend   | Laravel 12 (PHP 8.3)         |
| Frontend  | Vue 3 + Vite + Tailwind CSS  |
| Database  | MySQL 8.0                     |
| Auth      | Laravel Sanctum (SPA cookies) |
| Queue     | Database driver               |
| Dev Env   | Docker                        |

---

## Quick Start (Docker)

### Prerequisites

- Docker Desktop installed
- Git

### 1. Clone & configure

```bash
git clone https://github.com/yasir0722/KomX.git
cd KomX
cp .env.example .env
```

### 2. Start containers

```bash
docker-compose up -d
```

This starts:
- **komx-app** → PHP-FPM + Nginx on port `8000`
- **komx-mysql** → MySQL 8 on port `3306`
- **komx-node** → Vite dev server on port `5173`

### 3. Install dependencies & setup

```bash
# Enter the app container
docker exec -it komx-app bash

# Inside the container:
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 4. Access the app

- **App:** http://localhost:8000
- **Vite HMR:** http://localhost:5173

### Default login credentials

| Role      | Email               | Password |
| --------- | ------------------- | -------- |
| Admin     | admin@komx.app      | password |
| Committee | committee@komx.app  | password |
| Member    | member@komx.app     | password |

---

## Quick Start (Without Docker)

### Prerequisites

- PHP 8.3+
- Composer
- Node.js 20+
- MySQL 8

### Setup

```bash
git clone https://github.com/yasir0722/KomX.git
cd KomX
cp .env.example .env

# Edit .env with your local MySQL credentials

composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### Run

```bash
# Uses Composer's "dev" script to run all services concurrently:
composer dev

# Or run separately:
php artisan serve          # Backend on :8000
npm run dev                # Vite on :5173
php artisan queue:listen   # Queue worker
```

---

## Project Structure

```
app/
├── Actions/            # Single-purpose action classes
│   ├── Auth/
│   └── Member/
├── Domain/             # Domain enums, value objects
│   ├── Auth/
│   ├── Member/
│   ├── Event/          # (planned)
│   ├── Attendance/     # (planned)
│   └── Points/         # (planned)
├── Http/
│   ├── Controllers/
│   │   └── Api/V1/     # Versioned API controllers
│   ├── Middleware/      # Role middleware, etc.
│   ├── Requests/       # Form request validation
│   └── Resources/      # API resources (JSON transforms)
├── Models/             # Eloquent models
├── Repositories/       # Data-access abstraction
│   ├── Auth/
│   └── Member/
├── Services/           # Business logic orchestration
│   ├── Auth/
│   └── Member/
└── Providers/

resources/js/
├── App.vue             # Root Vue component
├── app.js              # Entry point
├── router/             # Vue Router
├── stores/             # Pinia state management
├── services/           # API service layer (axios)
├── views/              # Page-level components
│   ├── Auth/
│   ├── Dashboard/
│   ├── Members/
│   ├── Events/         # (planned)
│   └── Reports/        # (planned)
├── components/
│   ├── layout/
│   └── shared/
├── composables/        # Reusable composition functions
└── utils/
```

### Architecture Rules

1. **Controllers are thin** — delegate to services, return responses.
2. **Services handle business logic** — validate rules, orchestrate repos.
3. **Repositories abstract data access** — all Eloquent queries live here.
4. **Actions are single-purpose** — one `execute()` per class.
5. **API routes are versioned** — all under `/api/v1`.

---

## API

All API routes are prefixed with `/api/v1`.

| Method   | Endpoint            | Auth | Role   | Description        |
| -------- | ------------------- | ---- | ------ | ------------------ |
| `POST`   | `/login`            | No   | —      | Login              |
| `GET`    | `/user`             | Yes  | Any    | Current user       |
| `POST`   | `/logout`           | Yes  | Any    | Logout             |
| `GET`    | `/members`          | Yes  | Any    | List members       |
| `GET`    | `/members/{id}`     | Yes  | Any    | Show member        |
| `POST`   | `/members`          | Yes  | Admin  | Create member      |
| `PUT`    | `/members/{id}`     | Yes  | Admin  | Update member      |
| `DELETE` | `/members/{id}`     | Yes  | Admin  | Delete member      |

---

## Production Deployment (DigitalOcean)

### Server Setup (one-time)

```bash
# 1. Provision Ubuntu 22.04+ droplet (min 1GB RAM)

# 2. Install dependencies
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx php8.3-fpm php8.3-mysql php8.3-mbstring \
    php8.3-xml php8.3-bcmath php8.3-gd php8.3-curl \
    mysql-server composer nodejs npm supervisor certbot \
    python3-certbot-nginx git unzip

# 3. Create database
sudo mysql -e "CREATE DATABASE komx;"
sudo mysql -e "CREATE USER 'komx'@'localhost' IDENTIFIED BY 'YOUR_SECURE_PASSWORD';"
sudo mysql -e "GRANT ALL PRIVILEGES ON komx.* TO 'komx'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# 4. Clone repo
cd /var/www
sudo git clone https://github.com/yasir0722/KomX.git komx
cd komx
sudo chown -R www-data:www-data .

# 5. Configure environment
cp .env.example .env
# Edit .env with production values:
#   APP_ENV=production
#   APP_DEBUG=false
#   APP_URL=https://yourdomain.com
#   DB_PASSWORD=YOUR_SECURE_PASSWORD
#   SESSION_DOMAIN=yourdomain.com
#   SANCTUM_STATEFUL_DOMAINS=yourdomain.com

# 6. Install & build
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan key:generate
php artisan migrate --force
php artisan db:seed   # first time only
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Configure Nginx (see docker/nginx/default.conf as reference)
# Update server_name, root path, SSL, etc.

# 8. SSL
sudo certbot --nginx -d yourdomain.com

# 9. Queue worker via Supervisor
sudo nano /etc/supervisor/conf.d/komx-worker.conf
# [program:komx-worker]
# process_name=%(program_name)s_%(process_num)02d
# command=php /var/www/komx/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
# autostart=true
# autorestart=true
# numprocs=1
# user=www-data

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start komx-worker:*
```

### Deploying Updates

```bash
cd /var/www/komx
bash deploy.sh
```

### Backups (cron)

```bash
# Add to crontab (crontab -e):
0 2 * * * mysqldump -u komx -pYOUR_PASSWORD komx | gzip > /var/backups/komx-$(date +\%F).sql.gz
0 3 * * 0 find /var/backups -name "komx-*.sql.gz" -mtime +30 -delete
```

---

## Environment Variables

See `.env.example` for all available configuration. Key variables:

| Variable                  | Description                          |
| ------------------------- | ------------------------------------ |
| `APP_ENV`                 | `local` or `production`              |
| `APP_DEBUG`               | `true` for dev, `false` for prod     |
| `DB_*`                    | MySQL connection details             |
| `SANCTUM_STATEFUL_DOMAINS`| Domains allowed for SPA auth         |
| `SESSION_DOMAIN`          | Cookie domain                        |
| `QUEUE_CONNECTION`        | `database` (default)                 |
| `LOG_STACK`               | `daily,stderr` (production-ready)    |

---

## Contributing

Solo project — see `CONTEXT.md` for full architecture decisions and conventions.

## License

Proprietary — all rights reserved.
