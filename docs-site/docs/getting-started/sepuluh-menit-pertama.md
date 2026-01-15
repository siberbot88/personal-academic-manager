---
sidebar_position: 2
---

# 10 Menit Pertama

Panduan setup awal dan tur singkat Personal Academic Manager.

## Tujuan

Dalam 10 menit ini, Anda akan:
- Memahami struktur dashboard
- Membuat tugas pertama
- Log sesi belajar pertama
- Memahami workflow harian

## Step 1: Explore Dashboard (2 menit)

Setelah login, Anda akan melihat **Dashboard** dengan 3 bagian utama:

### A. Fokus Utama (Top 3 Cards)

Dashboard menampilkan **3 tugas prioritas** berdasarkan:

1. **Slot 1** - Deadline terdekat/overdue
2. **Slot 2** - Belum disentuh (progress 0%)
3. **Slot 3** - Risiko tinggi (health status bahaya)

Setiap card menampilkan:
- **Reason** - Alasan masuk slot (mis: "Deadline Terdekat")
- **Next Action** - Checklist item pertama yang belum done
- **Progress Badge** - Persentase completion
- **Health Status** - Aman (hijau) / Rawan (kuning) / Bahaya (merah)

:::tip Dashboard Kosong?
Jika slot kosong (belum ada tugas), klik **"Buat Task"** di header untuk membuat tugas pertama.
:::

### B. Study Progress Widget

Menampilkan:
- **Sesi minggu ini**: X/5 (target mingguan)
- **Daily Streak**: berapa hari berturut-turut log sesi
- **Tombol "Log Sesi"**: untuk quick log tanpa pindah halaman

### C. Quick Actions

Di header, ada 2 tombol:
- **Log Sesi** - Buka modal quick log (25/50/120 menit)
- **Buat Task** - Redirect ke form pembuatan tugas

## Step 2: Buat Tugas Pertama (3 menit)

Klik **"Buat Task"** lalu isi form:

```
Judul: Makalah Metodologi Penelitian
Mata Kuliah: Metodologi Penelitian (pilih dari dropdown)
Deadline: [pilih tanggal 2 minggu dari sekarang]
Template: Makalah (opsional, untuk auto-split phases)
```

Klik **"Buat"**.

:::note Auto-Split Phases
Jika memilih Template, tugas otomatis dibagi menjadi phases:
- Riset (3 hari)
- Draft (5 hari)
- Revisi & Finalisasi (3 hari)
Setiap phase punya checklist items.
:::

## Step 3: Log Sesi Belajar (2 menit)

Klik **"Log Sesi"** di header → isi form:

```
Course: Metodologi Penelitian
Task: Makalah Metodologi Penelitian
Durasi: 50m (Deep Work)
Mode: Belajar
Catatan: Baca jurnal untuk riset (opsional)
```

Klik **"Submit"**. Notifikasi muncul: *"Sesi 50 menit tercatat!"*

:::tip First Touch
Saat pertama kali log sesi untuk suatu task, sistem otomatis:
- Set `first_touched_at` = sekarang
- Hitung `started_lead_days` (berapa hari sebelum deadline)
- Tracking apakah "Start Early H-7" (≥7 hari sebelum deadline)
:::

## Step 4: Cek Health Status (1 menit)

Kembali ke **Dashboard** atau buka **Daftar Tugas**:

- Tugas baru Anda akan muncul dengan badge **Health Status**
- Karena baru dibuat dan masih jauh dari deadline, statusnya **Aman** (hijau)
- Progress masih 0% karena belum ada phase/checklist yang diselesaikan

Klik tugas → lihat detail:
- Tab **Checklist**: untuk menandai item sebagai done
- Tab **Phases**: melihat timeline phases
- **Action**: Mulai (set first touch), Selesai (mark as done)

## Step 5: Pahami Workflow Harian (2 menit)

PAM dirancang untuk workflow harian minimalis:

### Pagi (5 menit)
1. Buka Dashboard → cek **Top 3**
2. Lihat **Next Action** di setiap card
3. Tentukan prioritas hari ini

### Eksekusi (sepanjang hari)
- Kerjakan task
- Toggle checklist items saat selesai
- Progress otomatis update

### Malam (20:00–22:00)
1. **Log Sesi** hari ini (jika belum)
2. Cek progress di Dashboard
3. Opsional: buka **Weekly Review** (setiap Minggu malam)

:::tip Target Mingguan
Usahakan **minimal 5 sesi belajar per minggu** untuk menjaga konsistensi. Weekly Review akan tracking ini.
:::

## Quick Reference

### Navigasi Utama

| Menu | Fungsi |
|------|--------|
| **Dashboard** | Top 3 tasks + Study widget |
| **Daftar Tugas** | Semua tugas (filter: Active/Done/Archived) |
| **Log Sesi** | History semua sesi belajar |
| **Weekly Review** | Evaluasi mingguan (Konsistensi, Anti-Menunda, Eksekusi) |
| **Materi** | Library note/link/file |
| **Inbox** | Quick capture ideas |

### Istilah Penting

- **Health Score**: Skor 0-100 berdasarkan progress vs deadline
- **Attention Flag**: Untuk tugas stagnan ≥3 hari tanpa update
- **Priority Boost**: Untuk tugas yang diprioritaskan manual
- **Start Early H-7**: Tugas dimulai ≥7 hari sebelum deadline

## Next Steps

Anda sudah siap! Lanjutkan dengan:

1. [Workflow Harian](../workflow/pagi-5-menit) - Rutinitas lengkap
2. [Health Status](../tugas/health-status) - Memahami scoring
3. [Weekly Review](../belajar/weekly-review) - Evaluasi mingguan

---

**Butuh bantuan?** Lihat [FAQ](../faq) atau hubungi admin.
