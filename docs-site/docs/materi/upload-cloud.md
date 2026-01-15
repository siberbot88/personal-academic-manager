---
sidebar_position: 3
---

# Upload File ke Cloud

Panduan upload file ke R2 Cloud Storage dengan versioning.

## Jenis Material

PAM mendukung 3 tipe material:

1. **Note/Catatan** - Teks biasa
2. **Link/URL** - Bookmark referensi
3. **File** - Upload ke cloud storage

## Upload File

### 1. Buat Material

Navigasi: **Materi** → **Buat Material**

Form:
- **Tipe**: pilih "File"
- **Judul**: nama file yang deskriptif
- **Course**: mata kuliah terkait
- **Tags**: untuk kategorisasi

### 2. Upload Inline

Setelah pilih Tipe "File", field **"Upload File"** muncul.

- **Drag & Drop** file atau klik untuk browse
- **Maks 50MB** per file
- Format: PDF, DOCX, PPTX, ZIP, gambar

:::warning Ukuran File
Untuk file >50MB, gunakan **"Unggah ke Cloud"** di tab Attachments (presigned upload langsung ke R2).
:::

### 3. Simpan

Klik **"Buat"** → file otomatis:
- Upload ke R2 Cloud
- Buat record Attachment (version 1)
- Link ke Material

## Lihat & Download

1. Navigasi: **Materi** → klik material
2. Tab **Attachments** → lihat list file
3. Klik **"Download"** → file download dari R2

Download URL adalah **presigned URL** (temporary, 1 jam valid).

## Versioning

### Upload Version Baru

1. Buka Material → tab **Attachments**
2. Klik **"New Ver"** pada file yang ingin di-update
3. Upload file revision → sistem otomatis:
   - Create new version (v2, v3, ...)
   - Set `is_current = true` pada versi terbaru
   - Version lama tetap tersimpan

### Set Final

Klik **"Set Final"** untuk menandai version tertentu sebagai final/official.

## Next Steps

- [Versioning](./versioning) - Detail version control
- [Inbox Capture](./inbox-capture) - Quick capture ideas
