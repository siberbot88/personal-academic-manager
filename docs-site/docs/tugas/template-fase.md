---
sidebar_position: 2
---

# Template & Fase

Kustomisasi template untuk auto-split phases.

## Built-in Templates

PAM memiliki 2 template default:

### 1. Makalah

**Phases (11 hari total):**
1. Riset & Referensi (3 hari)
2. Penulisan Draft (5 hari)
3. Revisi & Finalisasi (3 hari)

### 2. Laporan Akhir

**Phases (14 hari total):**
1. Survey & Data Collection (4 hari)
2. Analysis & Writing (7 hari)
3. Review & Formatting (3 hari)

## Cara Gunakan

Saat membuat tugas:
1. Pilih **Template** dari dropdown
2. Set **Deadline**
3. Sistem otomatis hitung mundur dan buat phases

## Kustomisasi Template

Template disimpan di database (`task_type_templates` table).

Admin bisa tambah template baru via:
```sql
INSERT INTO task_type_templates (name, total_days) VALUES ('Presentasi', 7);
```

Lalu tambah phases di `phase_templates` table.

## Next Steps

- [Membuat Tugas](./membuat-tugas) - Cara buat task
- [Health Status](./health-status) - Scoring system
