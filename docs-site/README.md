# PAM Documentation Site

Professional documentation for Personal Academic Manager built with Docusaurus v3.

## Features

- ✅ Docs-as-homepage (no blog)
- ✅ Local search (@easyops-cn/docusaurus-search-local)
- ✅ Indonesian language (id locale)
- ✅ Brand colors (#003366, #FFCC00, #66CC99)
- ✅ Dark mode toggle
- ✅ Responsive sidebar & TOC
- ✅ Breadcrumbs & next/prev navigation

## Structure

### User Guides
- Getting Started (Login, 10 menit pertama)
- Mengelola Tugas (Membuat tugas, Health status)
- Sistem Belajar (Log sesi, Weekly review)
- Inbox & Materi (Upload cloud)

### Admin Guides
- Konfigurasi (ENV, mail, R2)
- Backup & Restore
- Deployment

### Other
- FAQ
- Changelog

## Development

Install dependencies:
```bash
npm install
```

Start dev server:
```bash
npm run start
```

Open http://localhost:3000

## Build

Production build:
```bash
npm run build
```

Output: `build/` directory

Serve locally:
```bash
npm run serve
```

## Deploy to Cloudflare Pages

### Via Cloudflare Dashboard

1. Go to **Workers & Pages** → **Create Application** → **Pages**
2. Connect your Git repository
3. Build settings:
   - **Build command**: `npm run build`
   - **Build output directory**: `build`
   - **Root directory**: `docs-site` (if in subfolder)
4. Deploy!

### Via Wrangler CLI

```bash
npm install -g wrangler
wrangler pages deploy build --project-name=pam-docs
```

### Custom Domain

In Cloudflare Pages:
1. Go to project → **Custom domains**
2. Add `docs.example.com`
3. Configure DNS CNAME record

## Content Updates

All documentation is in `docs/` folder as Markdown (`.md`) files.

### Add New Page

1. Create `docs/category/new-page.md`
2. Add to `sidebars.ts` if needed (or use auto-generated)
3. Include frontmatter:
   ```markdown
   ---
   sidebar_position: 1
   title: Page Title
   ---
   ```

### Update Sidebar

Edit `sidebars.ts` to reorganize categories and items.

### Styling

Brand colors in src/ css/custom.css`:
- Primary: `#003366`
- Accent: `#FFCC00`
- Success: `#66CC99`

## Troubleshooting

### Build Fails

Check Node version:
```bash
node --version  # Should be v18+ or v20+
```

Clear cache:
```bash
npm run clear
npm run build
```

### Search Not Working

Rebuild search index:
```bash
npm run build
```

Search index is generated during build.

## License

Same as main PAM application.
