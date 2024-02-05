<template>
    <div></div>
</template>

<script setup>
import {useStore} from '@/framework/store';
import {onMounted} from 'vue';
import {useRouter} from 'vue-router';
import {logout} from '@/framework/components/auth/auth.api.js';

const store = useStore();
const router = useRouter();

onMounted(async () => {
    await logout();
    store.clearUser();
    router.go(0);
});
</script>
