# Xpert IT Solution — Server Deployment Guide

This guide explains how to deploy the **Xpert IT Solution** Laravel application on an Ubuntu server using:

- Apache2
- MySQL
- Laravel
- Node.js / NPM
- Meilisearch
- Supervisor

Application directory:

```text
/var/www/html/xpert-it-solution
```

> **Security:** Never commit `.env`, database passwords, Meilisearch keys, API keys, or other secrets to GitHub. The Meilisearch key previously used during local setup should be treated as exposed. Generate a new production key before deployment.

---

# 1. Clone the Repository

```bash
cd /var/www/html

sudo git clone <YOUR_GIT_REPOSITORY_URL> xpert-it-solution

cd /var/www/html/xpert-it-solution
```

Set ownership if required:

```bash
sudo chown -R $USER:$USER /var/www/html/xpert-it-solution
```

---

# 2. Install Laravel Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

Install frontend dependencies and build production assets:

```bash
npm install
npm run build
```

---

# 3. Configure Laravel

Create the environment file if required:

```bash
cp .env.example .env
```

Edit:

```bash
nano .env
```

Set your production configuration:

```env
APP_NAME="Xpert IT Solution"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://xpert-it-solution.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=your_meilisearch_api_key
```

Generate the application key if needed:

```bash
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate --force
```

Create the public storage link:

```bash
php artisan storage:link
```

Clear and rebuild Laravel caches:

```bash
php artisan optimize:clear
php artisan optimize
```

> **Production:** Keep `APP_DEBUG=false`.

---

# 4. Configure Apache

Create the VirtualHost:

```bash
sudo nano /etc/apache2/sites-available/xpert-it-solution.conf
```

Add:

```apache
<VirtualHost *:80>
    ServerName xpert-it-solution.test

    DocumentRoot /var/www/html/xpert-it-solution/public

    <Directory /var/www/html/xpert-it-solution/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/xpert-it-solution-error.log
    CustomLog ${APACHE_LOG_DIR}/xpert-it-solution-access.log combined
</VirtualHost>
```

Enable Apache rewrite and the site:

```bash
sudo a2enmod rewrite
sudo a2ensite xpert-it-solution.conf
```

For a local setup, add the domain to `/etc/hosts`:

```bash
sudo nano /etc/hosts
```

Add:

```text
127.0.0.1 xpert-it-solution.test
```

Test Apache:

```bash
sudo apache2ctl configtest
```

Expected:

```text
Syntax OK
```

Reload Apache:

```bash
sudo systemctl reload apache2
```

Your Laravel application should now be available at:

```text
http://xpert-it-solution.test
```

Because Apache points directly to Laravel's `public` directory, `/public` is removed from the URL.

---

# 5. Set Laravel Permissions

Laravel must be able to write to:

- `storage/`
- `bootstrap/cache/`

Run:

```bash
sudo chown -R $USER:www-data /var/www/html/xpert-it-solution/storage
sudo chown -R $USER:www-data /var/www/html/xpert-it-solution/bootstrap/cache

sudo chmod -R 775 /var/www/html/xpert-it-solution/storage
sudo chmod -R 775 /var/www/html/xpert-it-solution/bootstrap/cache
```

Create the log directory if necessary:

```bash
sudo mkdir -p /var/www/html/xpert-it-solution/storage/logs
```

---

# 6. Configure Meilisearch

Meilisearch should run as a dedicated system service in production instead of being started manually from an SSH terminal.

## 6.1 Install Meilisearch

Install Meilisearch using the official installation method, then verify:

```bash
meilisearch --version
```

Make sure the binary is available at:

```text
/usr/local/bin/meilisearch
```

---

## 6.2 Create a Meilisearch User

```bash
sudo useradd -d /var/lib/meilisearch -s /bin/false -m -r meilisearch
```

Create data directories:

```bash
sudo mkdir -p /var/lib/meilisearch/data
sudo mkdir -p /var/lib/meilisearch/dumps
sudo mkdir -p /var/lib/meilisearch/snapshots
```

Set ownership:

```bash
sudo chown -R meilisearch:meilisearch /var/lib/meilisearch
```

---

## 6.3 Create the Meilisearch Environment File

Create:

```bash
sudo nano /etc/meilisearch.env
```

Add:

```env
MEILI_ENV=production
MEILI_MASTER_KEY=YOUR_NEW_MEILISEARCH_MASTER_KEY
MEILI_HTTP_ADDR=127.0.0.1:7700
MEILI_DB_PATH=/var/lib/meilisearch/data
MEILI_DUMP_DIR=/var/lib/meilisearch/dumps
MEILI_SNAPSHOT_DIR=/var/lib/meilisearch/snapshots
MEILI_NO_ANALYTICS=true
```

Protect the file:

```bash
sudo chown root:root /etc/meilisearch.env
sudo chmod 600 /etc/meilisearch.env
```

Generate a new master key with:

```bash
openssl rand -hex 32
```

Do not commit this key to GitHub.

---

## 6.4 Create a systemd Service

Create:

```bash
sudo nano /etc/systemd/system/meilisearch.service
```

Add:

```ini
[Unit]
Description=Meilisearch Search Engine
After=network.target

[Service]
Type=simple
User=meilisearch
Group=meilisearch

EnvironmentFile=/etc/meilisearch.env

ExecStart=/usr/local/bin/meilisearch

Restart=on-failure
RestartSec=5

LimitNOFILE=65536

[Install]
WantedBy=multi-user.target
```

Enable and start Meilisearch:

```bash
sudo systemctl daemon-reload
sudo systemctl enable meilisearch
sudo systemctl start meilisearch
```

Check status:

```bash
sudo systemctl status meilisearch
```

Check logs:

```bash
sudo journalctl -u meilisearch -f
```

Test the service:

```bash
curl http://127.0.0.1:7700/health
```

Expected response:

```json
{"status":"available"}
```

Since Meilisearch listens on `127.0.0.1`, it is only accessible from the server itself. Laravel can connect to it without exposing the search service directly to the internet.

---

# 7. Connect Laravel to Meilisearch

Update the Laravel `.env`:

```env
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=YOUR_MEILISEARCH_API_KEY
```

For the initial setup, your Laravel application can use the Meilisearch master key. For better security, create a dedicated API key for the application and use that instead.

Clear Laravel configuration cache:

```bash
php artisan optimize:clear
php artisan optimize
```

---

# 8. Configure Laravel Scout

After Meilisearch is running and Laravel is connected, synchronize the index settings:

```bash
php artisan scout:sync-index-settings
```

Import existing products:

```bash
php artisan scout:import "App\Models\Product"
```

You do not need to copy your local Meilisearch data to production. If your production database contains the correct products, rebuild the production search index from the database.

---

# 9. Configure Supervisor for Laravel Queues

Laravel queue workers are long-running processes. Supervisor keeps them running automatically.

Install Supervisor:

```bash
sudo apt update
sudo apt install supervisor -y
```

Enable it at boot:

```bash
sudo systemctl enable --now supervisor
```

Check:

```bash
sudo systemctl status supervisor
```

Create the worker configuration:

```bash
sudo nano /etc/supervisor/conf.d/xpert-it-solution-worker.conf
```

Add:

```ini
[program:xpert-it-solution-worker]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php /var/www/html/xpert-it-solution/artisan queue:work --sleep=3 --tries=3 --timeout=90
directory=/var/www/html/xpert-it-solution
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/xpert-it-solution/storage/logs/worker.log
stopwaitsecs=3600
```

Make sure the log directory exists:

```bash
sudo mkdir -p /var/www/html/xpert-it-solution/storage/logs
```

Ensure Apache/Supervisor can write to Laravel storage:

```bash
sudo chown -R www-data:www-data /var/www/html/xpert-it-solution/storage
sudo chmod -R 775 /var/www/html/xpert-it-solution/storage
```

Load the Supervisor configuration:

```bash
sudo supervisorctl reread
sudo supervisorctl update
```

Start the worker:

```bash
sudo supervisorctl start xpert-it-solution-worker:*
```

Check status:

```bash
sudo supervisorctl status
```

Expected:

```text
RUNNING
```

After this setup, you do **not** need to manually run:

```bash
php artisan queue:work
```

Supervisor keeps the queue worker running and restarts it when necessary.

---

# 10. Restart Queue Workers After Deployment

Queue workers are long-running processes and may still have the old application code loaded.

After deploying new code, run:

```bash
php artisan queue:restart
```

Or restart the Supervisor worker:

```bash
sudo supervisorctl restart xpert-it-solution-worker:*
```

---

# 11. Useful Commands

### Apache

```bash
sudo systemctl status apache2
sudo systemctl reload apache2
sudo apache2ctl configtest
```

### Laravel

```bash
php artisan optimize:clear
php artisan optimize
php artisan migrate --force
```

### Meilisearch

```bash
sudo systemctl status meilisearch
sudo systemctl restart meilisearch
sudo journalctl -u meilisearch -f
curl http://127.0.0.1:7700/health
```

### Supervisor

```bash
sudo supervisorctl status
sudo supervisorctl restart xpert-it-solution-worker:*
sudo supervisorctl stop xpert-it-solution-worker:*
sudo supervisorctl start xpert-it-solution-worker:*
```

### Laravel Logs

```bash
tail -f /var/www/html/xpert-it-solution/storage/logs/laravel.log
```

### Queue Worker Logs

```bash
tail -f /var/www/html/xpert-it-solution/storage/logs/worker.log
```

---

# 12. Deployment Checklist

## Laravel

- [ ] Clone repository
- [ ] Configure `.env`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database
- [ ] Configure Meilisearch
- [ ] Run Composer install
- [ ] Build frontend assets
- [ ] Run database migrations
- [ ] Run `php artisan storage:link`
- [ ] Run `php artisan optimize`

## Apache

- [ ] Create VirtualHost
- [ ] Set `DocumentRoot` to `/public`
- [ ] Enable `rewrite`
- [ ] Enable site
- [ ] Test Apache configuration
- [ ] Reload Apache

## Meilisearch

- [ ] Install Meilisearch
- [ ] Create dedicated `meilisearch` user
- [ ] Generate a secure master key
- [ ] Configure `/etc/meilisearch.env`
- [ ] Create systemd service
- [ ] Enable Meilisearch at boot
- [ ] Verify `/health`
- [ ] Configure Laravel Scout
- [ ] Sync index settings
- [ ] Import products

## Queue

- [ ] Install Supervisor
- [ ] Create queue worker configuration
- [ ] Start the worker
- [ ] Verify worker status is `RUNNING`

---

# 13. Production Architecture

```text
                         Internet
                            |
                            v
                         Apache2
                            |
                            v
                  Laravel Application
                            |
            +---------------+---------------+
            |               |               |
            v               v               v
          MySQL         Meilisearch      Laravel Queue
                        127.0.0.1:7700       |
                              |              v
                         systemd         Supervisor
                                             |
                                             v
                                      queue:work worker
```

### Service Responsibilities

| Service | Purpose | Process Manager |
|---|---|---|
| Apache2 | Serves Laravel | systemd |
| Laravel | Web application | Apache/PHP |
| MySQL | Application database | systemd |
| Meilisearch | Product search | systemd |
| Laravel Queue | Background jobs | Supervisor |

This setup keeps each service independent and ensures that:

- Apache serves Laravel from the `public` directory.
- Meilisearch starts automatically after a server reboot.
- Laravel queue workers run automatically.
- Search indexes can be rebuilt from the production database.
- Queue jobs do not require manually running `php artisan queue:work`.