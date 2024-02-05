<template>
    <router-view v-slot="{ Component }">
        <template v-if="Component">
            <suspense>
                <component :is="Component" />
                <template #fallback>
                    <Spinner />
                </template>
            </suspense>
        </template>
    </router-view>
    <Toasts />
</template>
<script setup>
import Spinner from '@/framework/components/common/spinner/Spinner.vue';
import Toasts from '@/framework/components/common/notifications/Toasts.vue';
</script>
