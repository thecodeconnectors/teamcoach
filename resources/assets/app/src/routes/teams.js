import middleware from '@/framework/routes/middleware';

export default [
    {
        beforeEnter: middleware.user,
        path: '',
        meta: {
            title: 'Teams'
        },
        component: () => import('@/framework/components/layouts/Default.vue'),
        children: [
            {
                path: 'teams',
                component: () => import('@/app/gamestats/teams/Teams.vue'),
                name: 'teams',
            },
            {
                path: 'teams/create',
                component: () => import('@/app/gamestats/teams/Team.vue'),
                name: 'teams.create',
            },
            {
                path: 'teams/:id',
                component: () => import('@/app/gamestats/teams/Team.vue'),
                name: 'teams.edit',
                props: true
            },
        ],
    },
];
