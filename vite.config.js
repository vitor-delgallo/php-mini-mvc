import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue';

function normalizeBasePath(value = '') {
  const normalized = String(value)
    .replace(/\\/g, '/')
    .replace(/^\/+|\/+$/g, '');

  return normalized ? `/${normalized}` : '';
}

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '');
  const basePath = normalizeBasePath(env.BASE_PATH ?? process.env.BASE_PATH ?? '');

  return {
    base: `${basePath}/public/build/`,
    publicDir: false,
    plugins: [vue()],
    build: {
      outDir: 'public/build',
      emptyOutDir: true,
      manifest: true,
      rollupOptions: {
        input: 'resources/vue/main.js',
      },
    },
  };
});
