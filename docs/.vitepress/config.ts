import { defineConfig } from 'vitepress'

export default defineConfig({
  title: ':package_name',
  description: ':package_description',
  base: '/:package_slug/',
  themeConfig: {
    nav: [
      { text: 'Overview', link: '/' },
      { text: 'Installation', link: '/installation' },
      { text: 'Usage', link: '/usage' },
      { text: 'Testing', link: '/testing' },
    ],
    sidebar: [
      { text: 'Overview', link: '/' },
      { text: 'Installation', link: '/installation' },
      { text: 'Usage', link: '/usage' },
      { text: 'Testing', link: '/testing' },
    ],
    search: {
      provider: 'local',
    },
    socialLinks: [
      { icon: 'github', link: 'https://github.com/:vendor_slug/:package_slug' },
    ],
    editLink: {
      pattern: 'https://github.com/:vendor_slug/:package_slug/edit/main/docs/:path',
      text: 'Edit this page on GitHub',
    },
  },
})
