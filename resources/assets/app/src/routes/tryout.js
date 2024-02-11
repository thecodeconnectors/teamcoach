import middleware from '@/framework/routes/middleware';

export default [
    {
        beforeEnter: middleware.user,
        path: '/tryout',
        meta: {
            title: 'tryout - Admin'
        },
        component: () => import('@/tryout/Default.vue'),
    },
];
