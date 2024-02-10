import {useStore} from '@/framework/store';

export default (to, from, next) => {
    const store = useStore();
    store.isAuthenticated ? next() : next({name: 'auth.login'});
}
