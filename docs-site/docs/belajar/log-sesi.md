---
sidebar_position: 1
---

# Log Sesi Belajar

Cara mencatat sesi belajar dan tracking konsistensi.

## Akses Quick Log

### Dari Dashboard

Klik **"Log Sesi"** di header → modal akan muncul.

### Dari Menu

Navigasi: **Log Sesi** → klik **"Log Sesi Baru"**

## Form Log Sesi

| Field | Required | Opsi |
|-------|----------|------|
| Course | ❌ | Pilih mata kuliah |
| Task | ❌ | Pilih tugas terkait |
| Durasi | ✅ | 25m (Pomodoro) / 50m (Deep Work) / 120m (Marathon) |
| Mode | ✅ | Belajar / Review / Nulis |
| Catatan | ❌ | Ringkasan aktivitas |

:::tip Durasi Template
- **25m**: Quick review, latihan soal
- **50m**: Deep work, fokus penuh
- **120m**: Project besar, marathon
:::

## Manfaat Tracking

### 1. Daily Streak

Sistem tracking berapa hari **berturut-turut** log sesi. Streak reset jika skip 1 hari.

### 2. Weekly Consistency

Target: **5 sesi per minggu**. Progress ditampilkan di Dashboard dan Weekly Review.

### 3. Auto First Touch

Ketika pertama kali log sesi untuk suatu task:
- `first_touched_at` otomatis di-set
- Sistem hitung **started_lead_days** (berapa hari sebelum deadline)

## Best Practices

1. **Log segera** setelah sesi selesai (jangan ditunda)
2. **Jujur** dalam durasi (jangan inflate angka)
3. **Konsisten** minimal 5x/minggu
4. **Catat** di Notes untuk referensi

## Next Steps

- [Target Mingguan](./target-mingguan) - Mencapai 5 sesi/minggu
- [Weekly Review](./weekly-review) - Evaluasi konsistensi
