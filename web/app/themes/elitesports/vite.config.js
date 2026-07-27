import { defineConfig } from 'vite'
import fs from 'node:fs'
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin'
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';

// Set APP_URL if it doesn't exist for Laravel Vite plugin
if (! process.env.APP_URL) {
  process.env.APP_URL = 'http://example.test';
}

/**
 * Per-block assets convention: every file in resources/css/blocks/ and
 * resources/js/blocks/ becomes its own Vite entry, so blocks can ship and
 * load their own CSS/JS independently (see App\Blocks\BaseBlock::assets()).
 */
function blockEntries() {
  return ['resources/css/blocks', 'resources/js/blocks'].flatMap((dir) =>
    fs.existsSync(dir)
      ? fs.readdirSync(dir)
          .filter((file) => /\.(css|js)$/.test(file))
          .map((file) => `${dir}/${file}`)
      : [],
  );
}

export default defineConfig({
  base: '/app/themes/elitesports/public/build/',
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/editor.css',
        'resources/js/editor.js',
        ...blockEntries(),
      ],
      refresh: true,
      assets: ['resources/images/**', 'resources/fonts/**'],
    }),

    wordpressPlugin(),

    // Generate the theme.json file in the public/build/assets directory
    // based on the Tailwind config and the theme.json file from base theme folder
    wordpressThemeJson({
      disableTailwindColors: false,
      disableTailwindFonts: false,
      disableTailwindFontSizes: false,
      disableTailwindBorderRadius: false,
    }),
  ],
  resolve: {
    alias: {
      '@scripts': '/resources/js',
      '@styles': '/resources/css',
      '@fonts': '/resources/fonts',
      '@images': '/resources/images',
    },
  },
})
