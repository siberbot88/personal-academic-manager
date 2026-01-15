---
sidebar_position: 2
---

# SSL Setup

Enable HTTPS dengan Let's Encrypt.

## Install Certbot

```bash
sudo apt install certbot python3-certbot-nginx -y
```

## Obtain Certificate

```bash
sudo certbot --nginx -d pam.example.com
```

Certbot akan otomatis:
- Generate SSL certificate
- Update nginx config
- Setup auto-renewal

## Verify Auto-Renewal

```bash
sudo certbot renew --dry-run
```

## Manual Renewal

```bash
sudo certbot renew
```

## nginx SSL Config

Certbot akan update `/etc/nginx/sites-available/pam` jadi:

```nginx
server {
    listen 443 ssl http2;
    server_name pam.example.com;
    
    ssl_certificate /etc/letsencrypt/live/pam.example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/pam.example.com/privkey.pem;
    
    # ... rest of config
}

server {
    listen 80;
    server_name pam.example.com;
    return 301 https://$server_name$request_uri;
}
```

## Next Steps

- [Monitoring](./monitoring) - Health checks
- [Backup](./backup-restore) - Daily backup
