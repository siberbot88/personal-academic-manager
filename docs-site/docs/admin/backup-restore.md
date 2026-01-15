---
sidebar_position: 3
---

# Backup & Restore

Strategi backup otomatis dan cara restore.

## Backup Otomatis

PAM melakukan backup otomatis **setiap hari** ke Cloudflare R2:

### Yang Di-backup

1. **Database** (MySQL dump)
   - File: `backup-YYYY-MM-DD-HH-mm-ss.sql.gz`
   - Lokasi R2: `s3://pam-storage/backups/`
   - Retention: 30 hari (backup >30 hari otomatis dihapus)

### Scheduler Command

Backup dijalankan via Laravel Scheduler:

```php
$schedule->command('backup:database')->daily()->at('02:00');
```

Pastikan cron sudah setup:

```bash
crontab -e

# Tambahkan line:
* * * * * cd /var/www/pam/apps/backend && php artisan schedule:run >> /dev/null 2>&1
```

## Manual Backup

### Database

```bash
cd /var/www/pam/apps/backend
php artisan backup:database
```

Output: backup tersimpan di R2.

### Download Backup

Gunakan Cloudflare R2 dashboard atau AWS CLI:

```bash
aws s3 cp s3://pam-storage/backups/backup-2026-01-15-02-00-00.sql.gz ./
```

## Restore

### 1. Download Backup

```bash
aws s3 cp s3://pam-storage/backups/backup-2026-01-15-02-00-00.sql.gz ./
```

### 2. Extract

```bash
gunzip backup-2026-01-15-02-00-00.sql.gz
```

### 3. Restore ke MySQL

```bash
mysql -u pam_user -p pam_db < backup-2026-01-15-02-00-00.sql
```

### 4. Verify

Login ke PAM dan cek data sudah restore dengan benar.

:::danger Uji Restore Berkala
**Wajib** uji restore minimal **1x per bulan** untuk memastikan backup bisa di-restore.
:::

## Next Steps

- [Konfigurasi](./konfigurasi) - Setup backup command
- [Monitoring](./monitoring) - Cek backup logs
