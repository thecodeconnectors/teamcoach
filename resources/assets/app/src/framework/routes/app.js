import middleware from '@/framework/routes/middleware';

export default [
    {
        beforeEnter: middleware.user,
        path: '',
        component: () => import('@/framework/components/layouts/Default.vue'),
        children: [
            {
                path: '',
                component: () => import('@/app/dashboard/Dashboard.vue'),
                name: 'home',
                meta: {
                    title: 'Dashboard'
                },
            },
            {
                path: 'logout',
                component: () => import('@/framework/components/auth/Logout.vue'),
                name: 'auth.logout',
            },
        ],
    },
    {
        path: '/:catchAll(.*)',
        component: () => import('@/framework/components/pages/PageNotFound.vue'),
        name: '404',
        meta: {
            layout: 'Default'
        },
    }
];
