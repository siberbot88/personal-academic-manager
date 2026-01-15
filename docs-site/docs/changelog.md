---
sidebar_position: 20
---

# Changelog

Riwayat perubahan dan update sistem PAM.

## v1.0.0 (Week 12) - January 2026

### UI Polish & i18n

- Custom login page dengan Google OAuth prominent
- Dashboard enhancements: Next Action display
- Material inline file upload
- Indonesian locale (APP_LOCALE=id)
- Brand color palette (#003366, #FFCC00, #66CC99)

## v0.3.0 (Week 9-11) - December 2025

### Study System

- Study session logging (Quick Log widget: 25/50/120 min)
- Daily streak & weekly consistency tracking
- Weekly Review page (Konsistensi, Anti-Menunda, Eksekusi)
- Dashboard Study Progress widget

### Material Library & Cloud

- Material types: Note, Link, File
- Cloud storage integration (Cloudflare R2)
- File versioning (v1, v2, v3...)
- Presigned upload (direct to bucket)
- Daily database backup to R2

### Inbox System

- Quick capture for ideas/tasks
- Tag management (Spatie Tags)
- Promote Inbox → Material
- Promote Inbox → Task

## v0.2.0 (Week 6-8) - November 2025

### Dashboard & Prioritization

- **Top 3 Dashboard**: Smart task selection
  - Slot 1: Deadline terdekat
  - Slot 2: Belum disentuh
  - Slot 3: Fokus utama (risiko)
- Health Status & Attention Flags
- Quick actions: Mulai, Buka, Selesai

### Email Reminders

- Daily Digest (morning email)
- Stagnation alerts (task stuck >3 days)
- Bahaya alerts (H-1 deadline)
- Queue system (Redis/Database)

## v0.1.0 (Week 1-4) - October 2025

### Foundation

- Google SSO authentication (whitelist)
- Task CRUD dengan filtering
- Course & Semester management
- Activity logging (Spatie Activitylog)

### Template Engine

- Task templates (Makalah, Laporan Akhir)
- Auto-splitting phases
- Checklist items
- Backward scheduling

### Metrics

- Progress calculation (recursive)
- Health scoring (0-100)
- Priority boost manual
- Start Early H-7 tracking

---

**Build**: Laravel 12, Filament v4.0, PHP 8.2
