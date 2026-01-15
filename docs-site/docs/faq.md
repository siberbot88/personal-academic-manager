---
sidebar_position: 10
---

# FAQ

Pertanyaan yang sering diajukan.

## Umum

### Apa itu PAM?

**Personal Academic Manager** adalah sistem manajemen tugas dan belajar untuk mahasiswa, dengan fitur:
- Task management dengan health monitoring
- Study session logging & weekly review
- Cloud storage untuk file/materi
- Email reminders

### Apakah PAM gratis?

Ya, PAM adalah aplikasi **open-source** dan bisa di-deploy sendiri.

## Login & Akses

### Kenapa harus pakai email Google?

PAM menggunakan **Google OAuth** untuk keamanan dan kemudahan. Email perlu di-whitelist oleh admin.

### Lupa password?

PAM tidak menggunakan password. Login via **Google OAuth** saja.

## Tugas

### Kenapa tugas saya tidak muncul di Top 3?

Top 3 menampilkan tugas berdasarkan **algoritma prioritas**:
- Slot 1: Deadline terdekat
- Slot 2: Belum disentuh (progress 0%)
- Slot 3: Risiko tinggi (health bahaya)

Jika tugas tidak masuk kriteria, tidak akan muncul.

### Apa itu Health Score?

Skor 0-100 berdasarkan **progress vs deadline**. Semakin rendah, semakin bahaya.

## Belajar & Sesi

### Harus log sesi belajar?

**Tidak wajib**, tapi sangat direkomendasikan untuk:
- Tracking konsistensi (5 sesi/minggu)
- Building daily streak
- Weekly review yang akurat

### Bagaimana jika lupa log sesi?

Bisa log **retroaktif** (backdate) dengan manual adjust `started_at` di database (perlu akses admin).

## Cloud Storage

### Berapa batas upload file?

**50MB** per file. Untuk file lebih besar, gunakan presigned upload langsung ke R2.

### Apakah file saya aman?

Ya. File tersimpan di **Cloudflare R2** (private bucket) dan hanya bisa diakses via presigned URL (valid 1 jam).

## Technical

### Apakah PAM support mobile?

Saat ini PAM adalah **web app** yang responsive. Bisa diakses via browser mobile.

### Bagaimana cara backup data?

Admin melakukan **backup otomatis setiap hari** ke R2. Lihat [Backup & Restore](./admin/backup-restore).

## Next Steps

Masih ada pertanyaan? Hubungi admin sistem atau baca:

- [Troubleshooting](./admin/troubleshooting)
- [Panduan Admin](./admin/index)
