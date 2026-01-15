---
sidebar_position: 2
---

# Health Status

Sistem scoring otomatis untuk memprioritaskan tugas.

## Apa itu Health Score?

Health Score adalah **nilai 0-100** yang dihitung otomatis berdasarkan:

1. **Progress** (%): seberapa banyak checklist sudah done
2. **Deadline Proximity**: seberapa dekat dengan deadline
3. **Stagnation**: berapa lama tidak ada update

## Kategori Health

| Status | Score | Warna | Artinya |
|--------|-------|-------|---------|
| **Aman** | 70-100 | 🟢 Green | On track, tidak perlu cemas |
| **Rawan** | 40-69 | 🟡 Yellow | Perlu perhatian lebih |
| **Bahaya** | 0-39 | 🔴 Red | Urgent, prioritaskan segera |

## Attention Flag

Tugas mendapat **Attention Flag** jika:
- Progress 0% dan sudah lewat H-3
- Tidak ada update **≥3 hari berturut-turut**
- Overdue (deadline sudah lewat)

## Priority Boost

Manual boost untuk menandai tugas sebagai extra prioritas. Tugas dengan boost akan:
- Muncul di **Slot 3** (Fokus Utama)
- Ditampilkan dengan badge boost

## Next Steps

- [Start Early H-7](./start-early-h7) - Manfaat mulai lebih awal
- [Workflow Pagi](../workflow/pagi-5-menit) - Cek health tiap pagi
