import {createRouter, createWebHistory} from 'vue-router';
import {nextTick} from 'vue';
import {useStore} from '@/framework/store/index.js';
import {getUser, startSession} from '@/framework/components/auth/auth.api.js';
import {getSettings} from '@/app/settings/settings.api.js';

export default async function configureRouter() {

    const frameworkRoutes = import.meta.glob('@/framework/routes/*.js');
    const appRoutes = import.meta.glob('@/routes/*.js');

    const combinedRoutes = {...frameworkRoutes, ...appRoutes};
    const importPromises = Object.values(combinedRoutes).map(importFunction => importFunction());
    const modules = await Promise.all(importPromises);
    const routes = modules.flatMap(module => module.default);

    const router = createRouter({
        history: createWebHistory(import.meta.env.BASE_URL),
        base: window.location.origin,
        routes,
    });

    router.beforeEach(async () => {
        const store = useStore();

        store.errors = null;
        store.flashMessage = null;

        if (!store.sessionStarted) {
            await startSession().then(async response => {
                store.setLogo(response.logo);
                store.setSessionStarted();
                await getUser().then(async response => {
                    if (response.data) {
                        store.setUser(response.data);
                        const {data: settings} = await getSettings();
                        store.setSettings(settings);
                    }
                });
            });
        }
    });

    router.afterEach((to) => {
        nextTick(() => {
            document.title = to.meta?.title || 'Teamcoa.ch';
        });
    });
    return router;
}
