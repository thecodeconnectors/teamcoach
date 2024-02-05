import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite';
import vue from '@vitejs/plugin-vue';
import {resolve} from 'path';
import {defineConfig, loadEnv} from 'vite';
import makeCert from 'vite-plugin-mkcert';

// https://vitejs.dev/config/
export default defineConfig(({mode}) => {
    const env = loadEnv(mode, process.cwd()); // Loads variables from .env.* file per environment defined by `mode` which can be one of development (default), staging or production
    const isRemoteBackend = env.VITE_IS_REMOTE_BACKEND === 'true';
    const proxyTarget = `${isRemoteBackend ? 'https' : 'http'}://${env.VITE_API_BASE_URL}`;

    return {
        plugins: [
            vue(),
            VueI18nPlugin({
                runtimeOnly: false,
                fullInstall: false,
            }),
            makeCert({
                autoUpgrade: true,
                hosts: [
                    env.VITE_APP_HOST,
                ],
            }),
        ],
        resolve: {
            alias: {
                '@': resolve(__dirname, 'src'),
            },
        },
        build: {
            sourcemap: true,
            target: 'esnext',
            rollupOptions: {
                output: {
                    entryFileNames: 'assets/[name].js',
                    chunkFileNames: 'assets/[name].js',
                    assetFileNames: 'assets/[name].[ext]',
                },
            },
        },
        server: {
            host: env.VITE_APP_HOST,
            port: env.VITE_DEV_PORT,
            strictPort: true,
            https: true,
            proxy: {
                '/api': {
                    target: proxyTarget,
                    changeOrigin: isRemoteBackend,
                    // rewrite: path => isRemoteBackend ? path.replace(/^\/api/, '/aos/api') : path,
                },
            },
        },
        preview: {
            host: env.VITE_APP_HOST,
            port: env.VITE_PREVIEW_PORT,
            strictPort: true,
            https: true,
            proxy: {
                '/api': {
                    target: proxyTarget,
                    changeOrigin: isRemoteBackend,
                    // rewrite: path => isRemoteBackend ? path.replace(/^\/api/, '/aos/api') : path,
                },
            },
        },
        test: {
            globals: true,
            include: ['src/**/*.test.js'],
            environment: 'happy-dom',
            setupFiles: './test-helpers/setup.js',
            coverage: {
                reporter: ['text'],
            },
        },
    };
});
