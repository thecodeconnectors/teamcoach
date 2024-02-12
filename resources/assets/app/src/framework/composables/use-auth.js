import {useStore} from '@/framework/store/index.js';

export function useAuth() {
    const store = useStore();
    const hasPermission = permission => store.user?.permissions?.includes(permission);

    return {
        hasPermission,
    };
}
