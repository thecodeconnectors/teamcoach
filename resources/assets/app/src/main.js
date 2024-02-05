import {createApp} from 'vue';
import {createPinia} from 'pinia';
import App from '@/App.vue';
import {router} from '@/framework/routes/router';
import '@/framework/assets/tailwind.css';
import '@/framework/assets/icons/fontawesome';

const pinia = createPinia();

createApp(App)
    .use(router)
    .use(pinia)
    .mount('#app');
