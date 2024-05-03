import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [svelte()],
  build: {
    minify: false,
    outDir: '../users/table/',
    lib: {
      entry: 'src/main.ts',
      formats: ['iife'],
      name: 'table',
    }
  },
});