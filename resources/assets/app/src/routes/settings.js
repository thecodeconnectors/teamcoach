import middleware from '@/framework/routes/middleware';

export default [
    {
        beforeEnter: middleware.user,
        path: '',
        meta: {
            title: 'Settings - Admin'
        },
        component: () => import('@/framework/components/layouts/Default.vue'),
        children: [
            {
                path: 'settings',
                component: () => import('@/app/settings/Settings.vue'),
                name: 'settings',
            },
        ],
    },
];
