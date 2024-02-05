import middleware from '@/framework/routes/middleware';

export default [
    {
        beforeEnter: middleware.user,
        path: '',
        meta: {
            title: 'Users'
        },
        component: () => import('@/framework/components/layouts/Default.vue'),
        children: [
            {
                path: 'users',
                component: () => import('@/app/users/Users.vue'),
                name: 'users',
            },
            {
                path: 'users/create',
                component: () => import('@/app/users/User.vue'),
                name: 'users.create',
            },
            {
                path: 'users/:id',
                component: () => import('@/app/users/User.vue'),
                name: 'users.edit',
                props: true
            },
        ],
    },
];
