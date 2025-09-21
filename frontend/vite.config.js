import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react(), tailwindcss()],
  root: '.',
  server: {
    port: 5173,
    proxy: {
      '/api': 'http://localhost:8000'
    }
  },
  build: {
    outDir: '../public/assets',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: 'index.html', // your entry point
    },
  },
})
