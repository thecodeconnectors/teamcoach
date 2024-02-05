import {useStore} from '@/framework/store';

export default (to, from, next) => {
    const store = useStore();
    console.log('user middleware');
    store.isAuthenticated ? next() : next({name: 'auth.login'});
}
