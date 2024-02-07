export default [
    {
        path: '',
        meta: {
            title: 'Games'
        },
        component: () => import('@/framework/components/layouts/Public.vue'),
        children: [
            {
                path: 'games/public/:urlSecret',
                component: () => import('@/app/gamestats/games/GamePublic.vue'),
                name: 'games.public',
                props: true
            },
        ],
    },
];
