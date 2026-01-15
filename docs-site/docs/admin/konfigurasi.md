---
sidebar_position: 1
---

# Konfigurasi

Environment variables dan konfigurasi sistem PAM.

## File Konfigurasi

Lokasi: `apps/backend/.env`

## Core Settings

### App

```bash
APP_NAME="Personal Academic Manager"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pam.example.com
APP_LOCALE=id
```

### Database

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pam_db
DB_USERNAME=pam_user
DB_PASSWORD=[strong_password]
```

### Mail (SMTP)

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

:::warning Gmail App Password
Untuk Gmail, gunakan **App Password** (bukan password akun biasa). Generate di: https://myaccount.google.com/apppasswords
:::

### Google OAuth

```bash
GOOGLE_CLIENT_ID=[your_client_id]
GOOGLE_CLIENT_SECRET=[your_secret]
GOOGLE_REDIRECT_URI=https://pam.example.com/auth/google/callback

# Whitelist emails (comma-separated)
GOOGLE_WHITELIST_EMAILS="student1@gmail.com,student2@gmail.com"
```

### Cloudflare R2

```bash
R2_ACCOUNT_ID=[cloudflare_account_id]
R2_ACCESS_KEY_ID=[r2_access_key]
R2_SECRET_ACCESS_KEY=[r2_secret]
R2_BUCKET=pam-storage
R2_ENDPOINT=https://[account_id].r2.cloudflarestorage.com
R2_PUBLIC_URL=https://storage.pam.example.com
```

### Queue & Cache

```bash
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=database

REDIS_HOST=127.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## File Upload Limits

Konfigurasi di `php.ini`:

```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
```

Dan nginx `/etc/nginx/sites-available/pam`:

```nginx
client_max_body_size 50M;
```

## Next Steps

- [ENV Reference](./env-reference) - Detail setiap variable
- [Deployment VPS](./deployment-vps) - Setup server
