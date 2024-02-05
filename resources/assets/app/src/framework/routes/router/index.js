import {createRouter, createWebHistory} from 'vue-router';
import {nextTick} from 'vue';
import {useStore} from '@/framework/store/index.js';
import {getUser, startSession} from '@/framework/components/auth/auth.api.js';

let routes = [];

const frameworkRoutes = import.meta.glob('@/framework/routes/*.js');
const appRoutes = import.meta.glob('@/routes/*.js');

async function loadAndCombineRoutes() {
    const combinedRoutes = {...frameworkRoutes, ...appRoutes};
    const importPromises = Object.values(combinedRoutes).map(importFunction => importFunction());
    const modules = await Promise.all(importPromises);
    const allRoutes = modules.flatMap(module => module.default);

    return allRoutes;
}

routes = await loadAndCombineRoutes();

const router = createRouter({
    history: createWebHistory(),
    //base: window.location.origin,
    routes,
});

router.beforeEach(async () => {
    const store = useStore();

    store.errors = null;
    store.flashMessage = null;

    if (!store.sessionStarted) {
        await startSession().then(async () => {
            store.setSessionStarted();
            await getUser().then(response => {
                if (response.data) {
                    store.setUser(response.data);
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

export {
    router,
};
