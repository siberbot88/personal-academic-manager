---
sidebar_position: 1
---

# Admin & Operasional

Panduan untuk admin sistem PAM.

## Tanggung Jawab Admin

Admin bertanggung jawab atas:

1. **Konfigurasi** - Environment variables, mail, R2
2. **Deployment** - VPS setup, nginx, SSL
3. **Backup** - Daily backup database ke R2
4. **Monitoring** - Queue, scheduler, disk usage
5. **Troubleshooting** - Resolve issues user

## Quick Links

### Konfigurasi
- [Konfigurasi Lengkap](./konfigurasi) - ENV reference
- [ENV Reference](./env-reference) - Semua environment variables

### Deployment
- [Deployment VPS](./deployment-vps) - Setup lengkap di Ubuntu
- [SSL Setup](./ssl-setup) - Let's Encrypt dengan Certbot

### Maintenance
- [Backup & Restore](./backup-restore) - Strategi backup
- [Monitoring](./monitoring) - Health check & logs

### Problem Solving
- [Troubleshooting](./troubleshooting) - Solusi masalah umum

## Tools yang Dibutuhkan

- **Server**: Ubuntu 22.04 LTS (minimal 2GB RAM)
- **Stack**: PHP 8.2, MySQL 8, nginx, Redis, supervisor
- **Cloud**: Cloudflare R2 (S3-compatible)
- **Email**: SMTP (mis: Gmail, Mailtrap)

## Next Steps

Untuk setup awal, ikuti urutan:

1. ✅ [Deployment VPS](./deployment-vps)
2. ✅ [Konfigurasi](./konfigurasi)
3. ✅ [Backup & Restore](./backup-restore)
4. 📊 [Monitoring](./monitoring)
