import { themes as prismThemes } from 'prism-react-renderer';
import type { Config } from '@docusaurus/types';
import type * as Preset from '@docusaurus/preset-classic';

const config: Config = {
  title: 'Personal Academic Manager',
  tagline: 'Dokumentasi sistem manajemen tugas & belajar untuk mahasiswa',
  favicon: 'img/favicon.ico',

  future: {
    v4: true,
  },

  url: 'https://docs.personal-academic-manager.dev',
  baseUrl: '/',

  organizationName: 'personal-academic-manager',
  projectName: 'pam-docs',

  onBrokenLinks: 'throw',

  markdown: {
    hooks: {
      onBrokenMarkdownLinks: 'warn',
    },
  },

  i18n: {
    defaultLocale: 'id',
    locales: ['id'],
  },

  presets: [
    [
      'classic',
      {
        docs: {
          routeBasePath: '/',
          sidebarPath: './sidebars.ts',
          editUrl: undefined,
          showLastUpdateTime: true,
          breadcrumbs: true,
        },
        blog: false,
        theme: {
          customCss: './src/css/custom.css',
        },
      } satisfies Preset.Options,
    ],
  ],

  // Temporarily disabled search plugin due to build issues
  // themes: [
  //   [
  //     '@easyops-cn/docusaurus-search-local',
  //     {
  //       hashed: true,
  //       language: ["en"],
  //       indexDocs: true,
  //       indexBlog: false,
  //       docsRouteBasePath: "/",
  //       highlightSearchTermsOnTargetPage: true,
  //       searchResultLimits: 8,
  //       searchBarPosition: "right",
  //     },
  //   ],
  // ],

  themeConfig: {
    image: 'img/pam-social-card.png',
    colorMode: {
      defaultMode: 'light',
      disableSwitch: false,
      respectPrefersColorScheme: true,
    },
    navbar: {
      title: 'PAM Docs',
      logo: {
        alt: 'PAM Logo',
        src: 'img/logo.svg',
      },
      items: [
        {
          type: 'docSidebar',
          sidebarId: 'penggunaSidebar',
          position: 'left',
          label: 'Pengguna',
        },
        {
          type: 'docSidebar',
          sidebarId: 'adminSidebar',
          position: 'left',
          label: 'Admin & Ops',
        },
        {
          to: '/changelog',
          label: 'Changelog',
          position: 'left',
        },
      ],
    },
    footer: {
      style: 'dark',
      links: [
        {
          title: 'Dokumentasi',
          items: [
            {
              label: 'Beranda',
              to: '/',
            },
            {
              label: 'Panduan Pengguna',
              to: '/',
            },
            {
              label: 'Panduan Admin',
              to: '/admin',
            },
          ],
        },
        {
          title: 'Quick Links',
          items: [
            {
              label: 'FAQ',
              to: '/faq',
            },
            {
              label: 'Changelog',
              to: '/changelog',
            },
          ],
        },
        {
          title: 'Fitur Utama',
          items: [
            {
              label: 'Task Management',
              to: '/tugas/membuat-tugas',
            },
            {
              label: 'Study Tracking',
              to: '/belajar/log-sesi',
            },
            {
              label: 'Cloud Storage',
              to: '/materi/upload-cloud',
            },
          ],
        },
      ],
      copyright: `© ${new Date().getFullYear()} Personal Academic Manager. Built with Docusaurus.`,
    },
    prism: {
      theme: prismThemes.github,
      darkTheme: prismThemes.dracula,
      additionalLanguages: ['php', 'bash', 'nginx'],
    },
    tableOfContents: {
      minHeadingLevel: 2,
      maxHeadingLevel: 4,
    },
  } satisfies Preset.ThemeConfig,
};

export default config;
