import middleware from '@/framework/routes/middleware';

export default [
    {
        beforeEnter: middleware.user,
        path: '',
        meta: {
            title: 'Games'
        },
        component: () => import('@/framework/components/layouts/Default.vue'),
        children: [
            {
                path: 'games',
                component: () => import('@/app/gamestats/games/Games.vue'),
                name: 'games',
            },
            {
                path: 'games/create',
                component: () => import('@/app/gamestats/games/Game.vue'),
                name: 'games.create',
            },
            {
                path: 'games/:id',
                component: () => import('@/app/gamestats/games/Game.vue'),
                name: 'games.edit',
                props: true
            },
            {
                path: 'games/:id/play',
                component: () => import('@/app/gamestats/games/GamePlay.vue'),
                name: 'games.play',
                props: true
            },
        ],
    },
];
