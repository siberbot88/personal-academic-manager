---
sidebar_position: 3
---

# Start Early H-7

Tracking tugas yang dimulai ≥7 hari sebelum deadline.

## Apa itu Start Early H-7?

**H-7** = 7 hari sebelum deadline.

Tugas yang **first_touched_at** (pertama kali log sesi) terjadi **≥7 hari sebelum deadline** dianggap "Start Early".

## Kenapa Penting?

Indikator **anti-procrastination**:
- Semakin banyak task Start Early → semakin baik perencanaan
- Target: ≥60% tasks dimulai H-7 atau lebih awal

## Cara Tracking

### 1. Auto-Track saat Log Sesi

Ketika pertama kali log sesi untuk suatu task:
- `first_touched_at` = sekarang
- Sistem hitung `started_lead_days` = selisih hari vs deadline
- Jika `started_lead_days >= 7` → flag "Start Early"

### 2. Lihat di Weekly Review

**Anti-Menunda** metric menampilkan:
```
Tasks Start Early H-7: 4/6 (67%)
```

Target: **≥60%**

## Strategi

1. **Buat task segera** saat diberikan dosen
2. **Log sesi pertama** minimal seminggu sebelum deadline
3. **Breakdown** task besar jadi phases (gunakan template)

## Next Steps

- [Health Status](./health-status) - Scoring system
- [Weekly Review](../belajar/weekly-review) - Metrik Anti-Menunda
