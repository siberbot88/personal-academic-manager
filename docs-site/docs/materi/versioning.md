---
sidebar_position: 4
---

# Versioning

File version control di PAM Cloud Storage.

## Apa itu Versioning?

Setiap file yang di-upload ke PAM bisa punya **multiple versions**:
- v1, v2, v3, ...
- Semua version tersimpan
- Bisa set version tertentu sebagai **Final**

## Upload New Version

### 1. Dari Material Page

Navigasi: **Materi** → pilih material → tab **Attachments**

### 2. Klik "New Ver"

Pada file yang ingin di-update, klik **"New Ver"**.

### 3. Upload Revision

Upload file baru → sistem otomatis:
- Increment version number (v2, v3, ...)
- Set `is_current = true` pada version baru
- Version lama tetap tersimpan dengan `is_current = false`

## Set Final Version

Klik **"Set Final"** pada version tertentu untuk menandai sebagai **official/final**.

Fungsi:
- **Unset** semua version lain di group yang sama
- **Set** version ini sebagai `is_final = true`

Berguna untuk:
- Marking submission version (yang di-submit ke dosen)
- Official documentation version

## Version History

List versions menampilkan:
- **Version Number** (v1, v2, v3)
- **Upload Date**
- **Size**
- **Current** badge (version terbaru)
- **Final** badge (jika di-set final)

## Example

```
Makalah_Algoritma.docx
├── v1 (uploaded 2026-01-01) - Draft
├── v2 (uploaded 2026-01-10) - Revision 1 [Current]
└── v3 (uploaded 2026-01-15) - Final Submission [Final] [Current]
```

## Storage

Semua version tersimpan di **Cloudflare R2**, tidak ada auto-delete.

Admin bisa manual cleanup old versions jika storage penuh.

## Next Steps

- [Upload Cloud](./upload-cloud) - Cara upload file
- [Backup](../admin/backup-restore) - Database backup
