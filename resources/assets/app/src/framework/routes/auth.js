import middleware from '@/framework/routes/middleware';

export default [
    {
        beforeEnter: middleware.guest,
        path: '/auth',
        component: () => import('@/framework/components/layouts/Auth.vue'),
        children: [
            {
                path: 'login',
                component: () => import('@/framework/components/auth/Login.vue'),
                name: 'auth.login',
                meta: {
                    title: 'Login - Teamcoa.ch'
                },
            },
            {
                path: 'register',
                component: () => import('@/framework/components/auth/Register.vue'),
                name: 'auth.register',
                meta: {
                    title: 'Register'
                },
            },
            {
                path: 'forgot-password',
                component: () => import('@/framework/components/auth/ForgotPassword.vue'),
                name: 'auth.forgot-password',
                meta: {
                    title: 'Forgot Password - Teamcoa.ch'
                },
            },
            {
                path: 'reset-password/:token',
                component: () => import('@/framework/components/auth/ResetPassword.vue'),
                name: 'auth.reset-password',
                meta: {
                    title: 'Reset Password - Teamcoa.ch'
                },
            },
        ],
    },
];
