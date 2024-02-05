<template>
    <div aria-live="assertive" class="absolute z-80 inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
        <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
            <template v-for="(toast, index) in toasts" v-bind:key="index">
                <Toast :toast="toast" :index="index" @dismissToast="dismissToast(index)" />
            </template>
        </div>
    </div>
</template>

<script>
import {useStore} from '@/framework/store';
import {computed} from 'vue';
import Toast from '@/framework/components/common/notifications/Toast.vue';

export default {
    name: 'Toasts',
    components: {
        Toast,
    },
    setup() {

        const store = useStore();
        const toasts = computed(() => store.toasts);

        const dismissToast = (index) => {
            store.dismissToastMessage(index);
        };

        return {
            toasts,
            dismissToast,
        };
    },
};
</script>
