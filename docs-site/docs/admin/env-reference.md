---
sidebar_position: 2
---

# ENV Reference

Daftar lengkap environment variables untuk PAM.

## Core Application

```bash
APP_NAME="Personal Academic Manager"
APP_ENV=production
APP_DEBUG=false
APP_KEY=[generate via: php artisan key:generate]
APP_URL=https://pam.example.com
APP_LOCALE=id
APP_TIMEZONE=Asia/Jakarta
```

## Database

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pam_db
DB_USERNAME=pam_user
DB_PASSWORD=[strong_password]
```

## Mail

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=[app_password]
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@pam.example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Google OAuth

```bash
GOOGLE_CLIENT_ID=[from Google Cloud Console]
GOOGLE_CLIENT_SECRET=[from Google Cloud Console]
GOOGLE_REDIRECT_URI=https://pam.example.com/auth/google/callback
GOOGLE_WHITELIST_EMAILS="email1@gmail.com,email2@gmail.com"
```

## Cloudflare R2

```bash
R2_ACCOUNT_ID=[cloudflare_account_id]
R2_ACCESS_KEY_ID=[r2_access_key]
R2_SECRET_ACCESS_KEY=[r2_secret_key]
R2_BUCKET=pam-storage
R2_ENDPOINT=https://[account_id].r2.cloudflarestorage.com
R2_PUBLIC_URL=https://storage.pam.example.com
```

## Queue & Cache

```bash
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=database
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## See Also

- [Konfigurasi](./konfigurasi) - Setup guide
- [Deployment](./deployment-vps) - Server setup
