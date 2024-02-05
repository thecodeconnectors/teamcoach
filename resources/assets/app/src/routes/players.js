import middleware from '@/framework/routes/middleware';

export default [
    {
        beforeEnter: middleware.user,
        path: '',
        meta: {
            title: 'Players'
        },
        component: () => import('@/framework/components/layouts/Default.vue'),
        children: [
            {
                path: 'players',
                component: () => import('@/app/gamestats/players/Players.vue'),
                name: 'players',
            },
            {
                path: 'players/create',
                component: () => import('@/app/gamestats/players/Player.vue'),
                name: 'players.create',
            },
            {
                path: 'players/:id',
                component: () => import('@/app/gamestats/players/Player.vue'),
                name: 'players.edit',
                props: true
            },
        ],
    },
];
