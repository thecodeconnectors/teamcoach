import middleware from '@/framework/routes/middleware';

export default [
    {
        beforeEnter: middleware.user,
        path: '',
        meta: {
            title: 'Trainings'
        },
        component: () => import('@/framework/components/layouts/Default.vue'),
        children: [
            {
                path: 'training',
                component: () => import('@/app/gamestats/training/Trainings.vue'),
                name: 'training',
            },
            {
                path: 'training/create',
                component: () => import('@/app/gamestats/training/Training.vue'),
                name: 'training.create',
            },
            {
                path: 'training/:id',
                component: () => import('@/app/gamestats/training/Training.vue'),
                name: 'training.edit',
                props: true
            },
        ],
    },
];
