import type { SidebarsConfig } from '@docusaurus/plugin-content-docs';

const sidebars: SidebarsConfig = {
  penggunaSidebar: [
    {
      type: 'doc',
      id: 'index',
      label: 'Beranda',
    },
    {
      type: 'category',
      label: 'Getting Started',
      collapsed: false,
      items: [
        'getting-started/login',
        'getting-started/sepuluh-menit-pertama',
      ],
    },
    {
      type: 'category',
      label: 'Workflow Harian',
      items: [
        'workflow/pagi-5-menit',
        'workflow/malam-review',
      ],
    },
    {
      type: 'category',
      label: 'Mengelola Tugas',
      items: [
        'tugas/membuat-tugas',
        'tugas/template-fase',
        'tugas/health-status',
        'tugas/start-early-h7',
      ],
    },
    {
      type: 'category',
      label: 'Sistem Belajar',
      items: [
        'belajar/log-sesi',
        'belajar/target-mingguan',
        'belajar/weekly-review',
      ],
    },
    {
      type: 'category',
      label: 'Inbox & Materi',
      items: [
        'materi/inbox-capture',
        'materi/promote-material',
        'materi/upload-cloud',
        'materi/versioning',
      ],
    },
    {
      type: 'doc',
      id: 'faq',
      label: 'FAQ',
    },
  ],

  adminSidebar: [
    {
      type: 'doc',
      id: 'admin/index',
      label: 'Admin & Operasional',
    },
    {
      type: 'category',
      label: 'Konfigurasi',
      items: [
        'admin/konfigurasi',
        'admin/env-reference',
      ],
    },
    {
      type: 'category',
      label: 'Deployment',
      items: [
        'admin/deployment-vps',
        'admin/ssl-setup',
      ],
    },
    {
      type: 'category',
      label: 'Maintenance',
      items: [
        'admin/backup-restore',
        'admin/monitoring',
      ],
    },
    {
      type: 'doc',
      id: 'admin/troubleshooting',
      label: 'Troubleshooting',
    },
  ],
};

export default sidebars;
