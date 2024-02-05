import {useStore} from '@/framework/store';

export default (to, from, next) => {
    const store = useStore();
    console.log('guest middleware');
    store.isAuthenticated ? next({name: 'home'}) : next();
}
