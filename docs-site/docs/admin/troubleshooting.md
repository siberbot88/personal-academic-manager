---
sidebar_position: 5
---

# Troubleshooting

Solusi masalah umum di PAM.

## Upload File Gagal

### Symptoms
- Error "413 Request Entity Too Large"
- Upload timeout

### Solution

1. Check nginx config:
```nginx
client_max_body_size 50M;
```

2. Check PHP config (`/etc/php/8.2/fpm/php.ini`):
```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
```

3. Restart services:
```bash
sudo systemctl restart php8.2-fpm
sudo systemctl reload nginx
```

## Queue Worker Not Processing

### Symptoms
- Jobs stuck in queue
- Email tidak terkirim

### Solution

1. Check supervisor status:
```bash
sudo supervisorctl status pam-worker:*
```

2. Restart workers:
```bash
sudo supervisorctl restart pam-worker:*
```

3. Check logs:
```bash
tail -f /var/www/pam/apps/backend/storage/logs/worker.log
```

## Scheduler Not Running

### Symptoms
- Daily backups tidak jalan
- Email reminders tidak kirim

### Solution

1. Verify cron:
```bash
sudo crontab -l -u www-data
```

2. Test manual:
```bash
cd /var/www/pam/apps/backend
sudo -u www-data php artisan schedule:run
```

3. Check logs:
```bash
tail -f storage/logs/laravel.log
```

## R2 Upload Error

### Symptoms
- "CORS policy" error
- "Access Denied" error

### Solution

1. Verify R2 credentials in `.env`
2. Check bucket CORS settings di Cloudflare dashboard
3. Verify presigned URL expiry (default 1 hour)

## Database Connection Failed

### Symptoms
- "SQLSTATE[HY000] [2002] Connection refused"

### Solution

1. Check MySQL running:
```bash
sudo systemctl status mysql
```

2. Verify credentials in `.env`
3. Test connection:
```bash
mysql -u pam_user -p pam_db
```

## Session Expired Terus

### Symptoms
- User logout sendiri
- "Session expired" setelah refresh

### Solution

1. Check `SESSION_DRIVER=database` di `.env`
2. Verify `sessions` table exists
3. Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
```

## Next Steps

- [Monitoring](./monitoring) - Health checks
- [Backup](./backup-restore) - Restore procedures
