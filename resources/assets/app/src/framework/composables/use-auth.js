import {useStore} from '@/framework/store/index.js';

export function useAuth() {
    const store = useStore();
    const hasPermission = permission => store.user?.permissions?.includes(permission);
    const hasRole = permission => store.user?.roles?.includes(permission);

    return {
        hasPermission,
        hasRole,
    };
}
