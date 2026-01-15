---
sidebar_position: 1
---

# Login ke Sistem

Panduan login untuk mengakses Personal Academic Manager.

## Prasyarat

- Akun Google yang sudah didaftarkan (whitelist email)
- Browser modern (Chrome, Firefox, Edge, Safari)

## Langkah Login

### 1. Buka Halaman Login

Akses URL aplikasi PAM yang telah diberikan oleh admin, misalnya:
```
https://pam.example.com/admin/login
```

### 2. Klik "Masuk dengan Google"

Pada halaman login, klik tombol **"Masuk dengan Google"**.

:::tip Recommended
Login dengan Google adalah metode yang direkomendasikan untuk keamanan dan kemudahan.
:::

### 3. Pilih Akun Google

- Jika sudah login Google di browser, pilih akun yang sudah di-whitelist
- Jika belum login, masukkan email dan password Google Anda
- Pastikan email yang dipilih sesuai dengan whitelist yang dikonfigurasi admin

### 4. Berikan Izin Akses

Google akan meminta izin untuk berbagi informasi dasar (nama, email, foto profil) dengan aplikasi PAM. Klik **"Izinkan"** atau **"Allow"**.

### 5. Redirect ke Dashboard

Setelah berhasil, Anda akan otomatis diarahkan ke **Dashboard** PAM dengan tampilan:

- **Fokus Utama** (Top 3 tasks)
- **Study Progress Widget**
- **Quick Actions** (Log Sesi, Buat Task)

## Troubleshooting

### Email Tidak Ter-Whitelist

Jika muncul pesan error *"Email not whitelisted"*:

1. Pastikan email Google Anda sudah didaftarkan oleh admin
2. Hubungi admin untuk menambahkan email ke whitelist
3. Admin perlu menambahkan email ke file `.env`:
   ```bash
   GOOGLE_WHITELIST_EMAILS="email1@gmail.com,email2@gmail.com"
   ```

### Redirect Loop

Jika terjadi redirect loop (terus kembali ke halaman login):

1. Hapus cookies browser untuk domain PAM
2. Logout dari Google di browser
3. Login ulang ke Google
4. Coba akses PAM lagi

### Session Expired

Jika muncul *"Session expired"*, cukup refresh halaman atau login ulang. Session akan otomatis diperpanjang saat aktif menggunakan aplikasi.

## Keamanan

:::warning Jangan Bagikan Session
- **Jangan** logout di komputer publik tanpa menutup semua tab browser
- **Gunakan** mode incognito/private di komputer bersama
- **Aktifkan** 2FA di akun Google Anda untuk keamanan tambahan
:::

Session PAM akan otomatis expired setelah:
- **15 menit** tidak aktif
- Browser ditutup (jika tidak ada "Remember Me")

## Next Steps

Setelah berhasil login:

1. [Setup 10 Menit Pertama](./sepuluh-menit-pertama) - Konfigurasi awal
2. [Membuat Tugas](../tugas/membuat-tugas) - Buat tugas pertama
3. [Log Sesi Belajar](../belajar/log-sesi) - Mulai tracking
