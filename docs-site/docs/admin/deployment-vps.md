---
sidebar_position: 1
---

# Deployment VPS

Panduan deploy PAM di VPS Ubuntu 22.04.

## Prasyarat

- VPS Ubuntu 22.04 LTS (min 2GB RAM)
- Domain yang sudah pointing ke IP server
- Akses root/sudo

## Stack

- PHP 8.2
- MySQL 8.0
- nginx
- Redis
- Supervisor (untuk queue worker)

## Installation Steps

### 1. Update System

```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Install PHP 8.2

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt install php8.2-fpm php8.2-cli php8.2-mysql php8.2-redis php8.2-curl php8.2-zip php8.2-mbstring php8.2-xml -y
```

### 3. Install MySQL

```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation
```

Create database:
```bash
sudo mysql
CREATE DATABASE pam_db;
CREATE USER 'pam_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL ON pam_db.* TO 'pam_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Install nginx

```bash
sudo apt install nginx -y
```

Config `/etc/nginx/sites-available/pam`:
```nginx
server {
    listen 80;
    server_name pam.example.com;
    root /var/www/pam/apps/backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable:
```bash
sudo ln -s /etc/nginx/sites-available/pam /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 5. Deploy Application

```bash
cd /var/www
sudo git clone [repo_url] pam
cd pam/apps/backend
sudo composer install --no-dev --optimize-autoloader
sudo cp .env.example .env
# Edit .env with production values
sudo php artisan key:generate
sudo php artisan migrate --force
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 6. Setup Supervisor (Queue)

`/etc/supervisor/conf.d/pam-worker.conf`:
```ini
[program:pam-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/pam/apps/backend/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/pam/apps/backend/storage/logs/worker.log
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start pam-worker:*
```

### 7. Setup Cron (Scheduler)

```bash
sudo crontab -e -u www-data
```

Add:
```cron
* * * * * cd /var/www/pam/apps/backend && php artisan schedule:run >> /dev/null 2>&1
```

## Next Steps

- [SSL Setup](./ssl-setup) - Enable HTTPS
- [Monitoring](./monitoring) - Health checks
