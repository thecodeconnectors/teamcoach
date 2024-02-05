import {createApp} from 'vue';
import {createPinia} from 'pinia';
import App from '@/App.vue';
import configureRouter from '@/framework/routes/router';
import '@/framework/assets/tailwind.css';
import '@/framework/assets/icons/fontawesome';

const pinia = createPinia();

configureRouter().then((router) => {
    createApp(App)
        .use(pinia)
        .use(router)
        .mount('#app');
}).catch(console.error);
