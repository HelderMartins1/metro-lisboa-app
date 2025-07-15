import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import svgLoader from 'vite-svg-loader'

export default defineConfig(({mode}) => {
    const env = loadEnv(mode, process.cwd())
    return {
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
                ],
                refresh: true,
            }),
            vue(),
            svgLoader()
        ],
        server: {
          proxy: {
            '/api': {
              target: 'http://localhost:8000',
              changeOrigin: true,
              rewrite: path => path.replace(/^\/api/, '/api'),
            }
          }
        }
    }
});
