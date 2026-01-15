---
sidebar_position: 4
---

# Monitoring

Health checks dan monitoring untuk PAM.

## Queue Worker

Check status:
```bash
sudo supervisorctl status pam-worker:*
```

Restart jika perlu:
```bash
sudo supervisorctl restart pam-worker:*
```

## Scheduler

Verify cron running:
```bash
sudo tail -f /var/www/pam/apps/backend/storage/logs/laravel.log
```

## Logs

### Application Logs

```bash
tail -f /var/www/pam/apps/backend/storage/logs/laravel.log
```

### nginx Logs

```bash
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
```

### PHP-FPM Logs

```bash
sudo tail -f /var/log/php8.2-fpm.log
```

## Disk Usage

```bash
df -h
du -sh /var/www/pam/apps/backend/storage
```

## Database Size

```bash
sudo mysql -e "SELECT table_schema AS 'Database', 
ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)' 
FROM information_schema.TABLES 
WHERE table_schema = 'pam_db';"
```

## Next Steps

- [Troubleshooting](./troubleshooting) - Common issues
- [Backup](./backup-restore) - Restore procedures
